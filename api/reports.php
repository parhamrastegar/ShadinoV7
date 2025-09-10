<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_reports();
        break;
    case 'POST':
        handle_create_report();
        break;
    case 'PUT':
        handle_update_report();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_reports() {
    global $conn;
    $user_data = require_auth();
    
    // Only admin can view all reports (for now, any user can view their own reports)
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;
    
    $stmt = mysqli_prepare($conn, "
        SELECT r.*, 
               reporter.name as reporter_name,
               reported.name as reported_user_name,
               a.title as ad_title
        FROM reports r
        JOIN users reporter ON r.reporter_id = reporter.id
        LEFT JOIN users reported ON r.reported_user_id = reported.id
        LEFT JOIN ads a ON r.ad_id = a.id
        WHERE r.reporter_id = ?
        ORDER BY r.created_at DESC
        LIMIT ? OFFSET ?
    ");
    mysqli_stmt_bind_param($stmt, "iii", $user_data['id'], $per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $reports = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reports[] = $row;
    }
    
    // Get total count
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM reports WHERE reporter_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_data['id']);
    mysqli_stmt_execute($stmt);
    $count_result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    send_success([
        'reports' => $reports,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]
    ]);
}

function handle_create_report() {
    global $conn;
    $user_data = require_auth();
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!isset($input['reason']) || empty(trim($input['reason']))) {
        send_error('دلیل گزارش الزامی است');
    }
    
    $reason = sanitize_input($input['reason']);
    $description = isset($input['description']) ? sanitize_input($input['description']) : null;
    $reported_user_id = isset($input['reported_user_id']) ? intval($input['reported_user_id']) : null;
    $ad_id = isset($input['ad_id']) ? intval($input['ad_id']) : null;
    
    // Validate reason
    $valid_reasons = ['spam', 'inappropriate', 'fraud', 'other'];
    if (!in_array($reason, $valid_reasons)) {
        send_error('دلیل گزارش نامعتبر است');
    }
    
    // At least one of reported_user_id or ad_id must be provided
    if (!$reported_user_id && !$ad_id) {
        send_error('باید کاربر یا آگهی مورد گزارش مشخص شود');
    }
    
    // Validate reported user
    if ($reported_user_id) {
        if ($reported_user_id == $user_data['id']) {
            send_error('نمی‌توانید خودتان را گزارش کنید');
        }
        
        $reported_user = get_user_by_id($reported_user_id);
        if (!$reported_user) {
            send_error('کاربر مورد گزارش یافت نشد', 404);
        }
    }
    
    // Validate ad
    if ($ad_id) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM ads WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $ad_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $ad = mysqli_fetch_assoc($result);
        
        if (!$ad) {
            send_error('آگهی مورد گزارش یافت نشد', 404);
        }
    }
    
    // Check if user already reported this item
    $check_query = "SELECT id FROM reports WHERE reporter_id = ?";
    $check_params = [$user_data['id']];
    $check_types = "i";
    
    if ($reported_user_id) {
        $check_query .= " AND reported_user_id = ?";
        $check_params[] = $reported_user_id;
        $check_types .= "i";
    }
    
    if ($ad_id) {
        $check_query .= " AND ad_id = ?";
        $check_params[] = $ad_id;
        $check_types .= "i";
    }
    
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, $check_types, ...$check_params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_fetch_assoc($result)) {
        send_error('شما قبلاً این مورد را گزارش کرده‌اید');
    }
    
    // Insert report
    $stmt = mysqli_prepare($conn, "
        INSERT INTO reports (reporter_id, reported_user_id, ad_id, reason, description) 
        VALUES (?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iiiss", 
        $user_data['id'], $reported_user_id, $ad_id, $reason, $description
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $report_id = mysqli_insert_id($conn);
        send_success(['report_id' => $report_id], 'گزارش با موفقیت ثبت شد');
    } else {
        send_error('خطا در ثبت گزارش');
    }
}

function handle_update_report() {
    // This would be for admin functionality to update report status
    // For now, we'll just return an error
    send_error('این عملیات در دسترس نیست', 501);
}
?>