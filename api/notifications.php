<?php
require_once '../includes/functions.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_notifications();
        break;
    case 'PUT':
        handle_mark_as_read();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_notifications() {
    $user_data = require_auth();
    global $conn;
    
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
    
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT ? OFFSET ?
    ");
    mysqli_stmt_bind_param($stmt, "iii", $user_data['user_id'], $per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $notifications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
    
    // Get unread count
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND is_read = FALSE");
    mysqli_stmt_bind_param($stmt, "i", $user_data['user_id']);
    mysqli_stmt_execute($stmt);
    $unread_result = mysqli_stmt_get_result($stmt);
    $unread_count = mysqli_fetch_assoc($unread_result)['unread_count'];
    
    // Get total count
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM notifications WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_data['user_id']);
    mysqli_stmt_execute($stmt);
    $count_result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    send_success([
        'notifications' => $notifications,
        'unread_count' => $unread_count,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]
    ]);
}

function handle_mark_as_read() {
    $user_data = require_auth();
    global $conn;
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['notification_id'])) {
        // Mark specific notification as read
        $notification_id = intval($input['notification_id']);
        
        $stmt = mysqli_prepare($conn, "UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $notification_id, $user_data['user_id']);
        
        if (mysqli_stmt_execute($stmt)) {
            send_success([], 'نوتیفیکیشن به عنوان خوانده شده علامت‌گذاری شد');
        } else {
            send_error('خطا در به‌روزرسانی نوتیفیکیشن');
        }
    } elseif (isset($input['mark_all_read']) && $input['mark_all_read']) {
        // Mark all notifications as read
        $stmt = mysqli_prepare($conn, "UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_data['user_id']);
        
        if (mysqli_stmt_execute($stmt)) {
            send_success([], 'تمام نوتیفیکیشن‌ها به عنوان خوانده شده علامت‌گذاری شدند');
        } else {
            send_error('خطا در به‌روزرسانی نوتیفیکیشن‌ها');
        }
    } else {
        send_error('پارامتر نامعتبر است');
    }
}
?>