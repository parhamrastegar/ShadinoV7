<?php
require_once '../includes/functions.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_messages();
        break;
    case 'POST':
        handle_send_message();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_messages() {
    $user_data = require_auth();
    global $conn;
    
    // Get conversation list
    if (!isset($_GET['conversation_id'])) {
        $stmt = mysqli_prepare($conn, "
            SELECT DISTINCT 
                CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END as contact_id,
                u.name as contact_name,
                u.avatar as contact_avatar,
                a.title as ad_title,
                a.id as ad_id,
                (SELECT message FROM messages m2 
                 WHERE (m2.sender_id = ? AND m2.receiver_id = contact_id) 
                    OR (m2.sender_id = contact_id AND m2.receiver_id = ?)
                 ORDER BY m2.created_at DESC LIMIT 1) as last_message,
                (SELECT created_at FROM messages m2 
                 WHERE (m2.sender_id = ? AND m2.receiver_id = contact_id) 
                    OR (m2.sender_id = contact_id AND m2.receiver_id = ?)
                 ORDER BY m2.created_at DESC LIMIT 1) as last_message_time,
                (SELECT COUNT(*) FROM messages m2 
                 WHERE m2.sender_id = contact_id AND m2.receiver_id = ? AND m2.is_read = FALSE) as unread_count
            FROM messages m
            JOIN users u ON u.id = CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END
            LEFT JOIN ads a ON m.ad_id = a.id
            WHERE sender_id = ? OR receiver_id = ?
            ORDER BY last_message_time DESC
        ");
        if (!stmt_bind_params($stmt, "iiiiiiiii", [$user_data['id'], $user_data['id'], $user_data['id'], $user_data['id'], $user_data['id'], $user_data['id'], $user_data['id'], $user_data['id'], $user_data['id']])) {
            send_error('خطا در اجرای پرس‌وجو');
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $conversations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $conversations[] = $row;
        }
        
        send_success($conversations);
    }
    
    // Get messages in a conversation
    $conversation_id = intval($_GET['conversation_id']);
    $ad_id = isset($_GET['ad_id']) ? intval($_GET['ad_id']) : null;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 50;
    $offset = ($page - 1) * $per_page;
    
    $query = "
        SELECT m.*, 
               s.name as sender_name, s.avatar as sender_avatar,
               r.name as receiver_name, r.avatar as receiver_avatar
        FROM messages m
        JOIN users s ON m.sender_id = s.id
        JOIN users r ON m.receiver_id = r.id
        WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))
    ";
    
    $params = [$user_data['id'], $conversation_id, $conversation_id, $user_data['id']];
    $param_types = "iiii";
    
    if ($ad_id) {
        $query .= " AND ad_id = ?";
        $params[] = $ad_id;
        $param_types .= "i";
    }
    
    $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $param_types .= "ii";
    
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) send_error('خطا در آماده‌سازی پرس‌وجو');
    if (!stmt_bind_params($stmt, $param_types, $params)) {
        send_error('خطا در پارامتردهی پرس‌وجو');
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
    
    // Mark messages as read
    $stmt = mysqli_prepare($conn, "
        UPDATE messages SET is_read = TRUE 
        WHERE sender_id = ? AND receiver_id = ? AND is_read = FALSE
    ");
    if (!stmt_bind_params($stmt, "ii", [$conversation_id, $user_data['id']])) {
        send_error('خطا در بستن پارامترها');
    }
    mysqli_stmt_execute($stmt);
    
    send_success(array_reverse($messages)); // Reverse to show oldest first
}

function handle_send_message() {
    $user_data = require_auth();
    global $conn;
    
    // Handle file upload if present
    $file_path = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_result = upload_file($_FILES['file'], ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);
        if ($upload_result['success']) {
            $file_path = $upload_result['filename'];
        } else {
            send_error($upload_result['message']);
        }
    }
    
    // Get form data
    $receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
    $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
    $ad_id = isset($_POST['ad_id']) ? intval($_POST['ad_id']) : null;
    
    // Validate required fields
    if (!$receiver_id) {
        send_error('گیرنده پیام مشخص نشده است');
    }
    
    if (empty($message) && !$file_path) {
        send_error('پیام یا فایل الزامی است');
    }
    
    if ($receiver_id == $user_data['id']) {
        send_error('نمی‌توانید به خودتان پیام بفرستید');
    }
    
    // Check if receiver exists
    $receiver = get_user_by_id($receiver_id);
    if (!$receiver) {
        send_error('گیرنده یافت نشد', 404);
    }
    
    // Insert message
    $stmt = mysqli_prepare($conn, "
        INSERT INTO messages (sender_id, receiver_id, ad_id, message, file_path) 
        VALUES (?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iiiss", 
        $user_data['id'], $receiver_id, $ad_id, $message, $file_path
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $message_id = mysqli_insert_id($conn);
        
        // Send notification to receiver
        $stmt = mysqli_prepare($conn, "
            INSERT INTO notifications (user_id, title, message, type, related_id) 
            VALUES (?, 'پیام جدید', 'پیام جدیدی دریافت کردید', 'message', ?)
        ");
        mysqli_stmt_bind_param($stmt, "ii", $receiver_id, $message_id);
        mysqli_stmt_execute($stmt);
        
        send_success(['message_id' => $message_id], 'پیام با موفقیت ارسال شد');
    } else {
        send_error('خطا در ارسال پیام');
    }
}
?>