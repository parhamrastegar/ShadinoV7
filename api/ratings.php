<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_ratings();
        break;
    case 'POST':
        handle_create_rating();
        break;
    case 'PUT':
        handle_update_rating();
        break;
    case 'DELETE':
        handle_delete_rating();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_ratings() {
    global $conn;
    
    // Get user ID from URL parameter
    if (!isset($_GET['user_id'])) {
        send_error('شناسه کاربر الزامی است', 400);
    }
    
    $user_id = intval($_GET['user_id']);
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
    
    // Get user info
    $user = get_user_by_id($user_id);
    if (!$user) {
        send_error('کاربر یافت نشد', 404);
    }
    
    // Get user stats
    $stats = get_user_stats($user_id);
    
    // Get ratings with pagination
    $offset = ($page - 1) * $per_page;
    $stmt = mysqli_prepare($conn, "
        SELECT ur.*, u.name as rater_name, u.avatar as rater_avatar
        FROM user_ratings ur
        JOIN users u ON ur.rater_id = u.id
        WHERE ur.user_id = ?
        ORDER BY ur.created_at DESC
        LIMIT ? OFFSET ?
    ");
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $ratings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ratings[] = $row;
    }
    
    // Get total count
    $count_stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM user_ratings WHERE user_id = ?");
    mysqli_stmt_bind_param($count_stmt, "i", $user_id);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    send_success([
        'user' => $user,
        'stats' => $stats,
        'ratings' => $ratings,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]
    ]);
}

function handle_create_rating() {
    global $conn;
    
    // Check authentication
    $current_user = require_auth();
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!isset($input['user_id']) || !isset($input['rating'])) {
        send_error('شناسه کاربر و امتیاز الزامی است', 400);
    }
    
    $user_id = intval($input['user_id']);
    $rating = intval($input['rating']);
    $comment = isset($input['comment']) ? trim($input['comment']) : '';
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        send_error('امتیاز باید بین ۱ تا ۵ باشد', 400);
    }
    
    // Check if user exists
    $target_user = get_user_by_id($user_id);
    if (!$target_user) {
        send_error('کاربر یافت نشد', 404);
    }
    
    // Check if user is trying to rate themselves
    if ($user_id == $current_user['id']) {
        send_error('نمی‌توانید به خودتان امتیاز دهید', 400);
    }
    
    // Check if rating already exists
    $existing_rating = get_existing_rating($user_id, $current_user['id']);
    
    if ($existing_rating) {
        send_error('شما قبلاً به این کاربر امتیاز داده‌اید. برای تغییر امتیاز از درخواست PUT استفاده کنید', 400);
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert rating
        $stmt = mysqli_prepare($conn, "
            INSERT INTO user_ratings (user_id, rater_id, rating, comment) 
            VALUES (?, ?, ?, ?)
        ");
        mysqli_stmt_bind_param($stmt, "iiis", $user_id, $current_user['id'], $rating, $comment);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('خطا در ثبت امتیاز');
        }
        
        // Update user stats
        update_user_stats($user_id);
        
        mysqli_commit($conn);
        
        send_success([
            'rating_id' => mysqli_insert_id($conn),
            'message' => 'امتیاز با موفقیت ثبت شد'
        ]);
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        send_error($e->getMessage());
    }
}

function handle_update_rating() {
    global $conn;
    
    // Check authentication
    $current_user = require_auth();
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['rating_id'])) {
        send_error('شناسه امتیاز الزامی است', 400);
    }
    
    $rating_id = intval($input['rating_id']);
    $rating = isset($input['rating']) ? intval($input['rating']) : null;
    $comment = isset($input['comment']) ? trim($input['comment']) : null;
    
    // Validate rating
    if ($rating !== null && ($rating < 1 || $rating > 5)) {
        send_error('امتیاز باید بین ۱ تا ۵ باشد', 400);
    }
    
    // Check if rating exists and belongs to current user
    $existing_rating = get_rating_by_id($rating_id);
    if (!$existing_rating) {
        send_error('امتیاز یافت نشد', 404);
    }
    
    if ($existing_rating['rater_id'] != $current_user['id']) {
        send_error('شما مجاز به ویرایش این امتیاز نیستید', 403);
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Update rating
        $update_fields = [];
        $params = [];
        $types = '';
        
        if ($rating !== null) {
            $update_fields[] = "rating = ?";
            $params[] = $rating;
            $types .= 'i';
        }
        
        if ($comment !== null) {
            $update_fields[] = "comment = ?";
            $params[] = $comment;
            $types .= 's';
        }
        
        if (empty($update_fields)) {
            send_error('هیچ فیلدی برای بروزرسانی مشخص نشده است', 400);
        }
        
        $update_fields[] = "updated_at = CURRENT_TIMESTAMP";
        $params[] = $rating_id;
        $types .= 'i';
        
        $stmt = mysqli_prepare($conn, "
            UPDATE user_ratings 
            SET " . implode(', ', $update_fields) . " 
            WHERE id = ?
        ");
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('خطا در بروزرسانی امتیاز');
        }
        
        // Update user stats
        update_user_stats($existing_rating['user_id']);
        
        mysqli_commit($conn);
        
        send_success(['message' => 'امتیاز با موفقیت بروزرسانی شد']);
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        send_error($e->getMessage());
    }
}

function handle_delete_rating() {
    global $conn;
    
    // Check authentication
    $current_user = require_auth();
    
    if (!isset($_GET['rating_id'])) {
        send_error('شناسه امتیاز الزامی است', 400);
    }
    
    $rating_id = intval($_GET['rating_id']);
    
    // Check if rating exists and belongs to current user
    $existing_rating = get_rating_by_id($rating_id);
    if (!$existing_rating) {
        send_error('امتیاز یافت نشد', 404);
    }
    
    if ($existing_rating['rater_id'] != $current_user['id']) {
        send_error('شما مجاز به حذف این امتیاز نیستید', 403);
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Delete rating
        $stmt = mysqli_prepare($conn, "DELETE FROM user_ratings WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $rating_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('خطا در حذف امتیاز');
        }
        
        // Update user stats
        update_user_stats($existing_rating['user_id']);
        
        mysqli_commit($conn);
        
        send_success(['message' => 'امتیاز با موفقیت حذف شد']);
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        send_error($e->getMessage());
    }
}

// Helper functions (moved to includes/functions.php to avoid redeclaration)
?>
