<?php
require_once '../includes/functions.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_deliveries();
        break;
    case 'POST':
        handle_create_delivery();
        break;
    case 'PUT':
        handle_update_delivery();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_deliveries() {
    $user_data = require_auth();
    global $conn;
    
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;
    
    // Different queries based on user role
    if ($user_data['role'] === 'delivery') {
        // Get deliveries assigned to this delivery person
        $stmt = mysqli_prepare($conn, "
            SELECT d.*, a.title as ad_title, a.category,
                   c.name as customer_name, c.mobile as customer_mobile,
                   b.name as business_name, b.mobile as business_mobile
            FROM deliveries d
            JOIN ads a ON d.ad_id = a.id
            JOIN users c ON d.customer_id = c.id
            JOIN users b ON d.business_id = b.id
            WHERE d.delivery_id = ?
            ORDER BY d.created_at DESC
            LIMIT ? OFFSET ?
        ");
        mysqli_stmt_bind_param($stmt, "iii", $user_data['user_id'], $per_page, $offset);
    } 
    elseif ($user_data['role'] === 'business') {
        // Get deliveries for this business
        $stmt = mysqli_prepare($conn, "
            SELECT d.*, a.title as ad_title, a.category,
                   c.name as customer_name, c.mobile as customer_mobile,
                   del.name as delivery_name, del.mobile as delivery_mobile
            FROM deliveries d
            JOIN ads a ON d.ad_id = a.id
            JOIN users c ON d.customer_id = c.id
            JOIN users del ON d.delivery_id = del.id
            WHERE d.business_id = ?
            ORDER BY d.created_at DESC
            LIMIT ? OFFSET ?
        ");
        mysqli_stmt_bind_param($stmt, "iii", $user_data['user_id'], $per_page, $offset);
    }
    elseif ($user_data['role'] === 'customer') {
        // Get deliveries for this customer
        $stmt = mysqli_prepare($conn, "
            SELECT d.*, a.title as ad_title, a.category,
                   b.name as business_name, b.mobile as business_mobile,
                   del.name as delivery_name, del.mobile as delivery_mobile
            FROM deliveries d
            JOIN ads a ON d.ad_id = a.id
            JOIN users b ON d.business_id = b.id
            JOIN users del ON d.delivery_id = del.id
            WHERE d.customer_id = ?
            ORDER BY d.created_at DESC
            LIMIT ? OFFSET ?
        ");
        mysqli_stmt_bind_param($stmt, "iii", $user_data['user_id'], $per_page, $offset);
    }
    else {
        send_error('نقش کاربری نامعتبر است', 403);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $deliveries = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $deliveries[] = $row;
    }
    
    // Get total count
    $count_query = "SELECT COUNT(*) as total FROM deliveries WHERE ";
    if ($user_data['role'] === 'delivery') {
        $count_query .= "delivery_id = ?";
    } elseif ($user_data['role'] === 'business') {
        $count_query .= "business_id = ?";
    } else {
        $count_query .= "customer_id = ?";
    }
    
    $stmt = mysqli_prepare($conn, $count_query);
    mysqli_stmt_bind_param($stmt, "i", $user_data['user_id']);
    mysqli_stmt_execute($stmt);
    $count_result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    send_success([
        'deliveries' => $deliveries,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]
    ]);
}

function handle_create_delivery() {
    $user_data = require_auth();
    
    // Only businesses can create delivery requests
    if ($user_data['role'] !== 'business') {
        send_error('فقط کسب‌وکارها می‌توانند درخواست تحویل ثبت کنند', 403);
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required_fields = ['ad_id', 'delivery_id', 'customer_id', 'pickup_address', 'delivery_address'];
    foreach ($required_fields as $field) {
        if (!isset($input[$field]) || empty(trim($input[$field]))) {
            send_error("فیلد $field الزامی است");
        }
    }
    
    $ad_id = intval($input['ad_id']);
    $delivery_id = intval($input['delivery_id']);
    $customer_id = intval($input['customer_id']);
    $pickup_address = sanitize_input($input['pickup_address']);
    $delivery_address = sanitize_input($input['delivery_address']);
    $notes = isset($input['notes']) ? sanitize_input($input['notes']) : null;
    
    // Validate that the ad belongs to this business
    $stmt = mysqli_prepare($conn, "SELECT * FROM ads WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $ad_id, $user_data['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ad = mysqli_fetch_assoc($result);
    
    if (!$ad) {
        send_error('آگهی یافت نشد یا متعلق به شما نیست', 404);
    }
    
    // Validate delivery person
    $delivery_person = get_user_by_id($delivery_id);
    if (!$delivery_person || $delivery_person['role'] !== 'delivery') {
        send_error('پیک یافت نشد', 404);
    }
    
    // Validate customer
    $customer = get_user_by_id($customer_id);
    if (!$customer || $customer['role'] !== 'customer') {
        send_error('مشتری یافت نشد', 404);
    }
    
    // Insert delivery
    $stmt = mysqli_prepare($conn, "
        INSERT INTO deliveries (ad_id, delivery_id, customer_id, business_id, pickup_address, delivery_address, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iiiisss", 
        $ad_id, $delivery_id, $customer_id, $user_data['user_id'], 
        $pickup_address, $delivery_address, $notes
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $delivery_id = mysqli_insert_id($conn);
        
        // Send notifications
        $stmt = mysqli_prepare($conn, "
            INSERT INTO notifications (user_id, title, message, type, related_id) 
            VALUES (?, 'تحویل جدید', 'تحویل جدیدی به شما اختصاص یافت', 'delivery', ?)
        ");
        mysqli_stmt_bind_param($stmt, "ii", $delivery_person['id'], $delivery_id);
        mysqli_stmt_execute($stmt);
        
        $stmt = mysqli_prepare($conn, "
            INSERT INTO notifications (user_id, title, message, type, related_id) 
            VALUES (?, 'تحویل در راه', 'سفارش شما در حال تحویل است', 'delivery', ?)
        ");
        mysqli_stmt_bind_param($stmt, "ii", $customer_id, $delivery_id);
        mysqli_stmt_execute($stmt);
        
        send_success(['delivery_id' => $delivery_id], 'درخواست تحویل با موفقیت ثبت شد');
    } else {
        send_error('خطا در ثبت درخواست تحویل');
    }
}

function handle_update_delivery() {
    $user_data = require_auth();
    
    // Get delivery ID from URL
    $delivery_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$delivery_id) {
        send_error('شناسه تحویل نامعتبر است');
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Get delivery details
    $stmt = mysqli_prepare($conn, "SELECT * FROM deliveries WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delivery_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $delivery = mysqli_fetch_assoc($result);
    
    if (!$delivery) {
        send_error('تحویل یافت نشد', 404);
    }
    
    // Only delivery person can update delivery status
    if ($user_data['user_id'] !== $delivery['delivery_id']) {
        send_error('شما مجاز به به‌روزرسانی این تحویل نیستید', 403);
    }
    
    // Validate status
    if (!isset($input['status']) || !in_array($input['status'], ['assigned', 'picked_up', 'delivered', 'cancelled'])) {
        send_error('وضعیت نامعتبر است');
    }
    
    $status = $input['status'];
    $notes = isset($input['notes']) ? sanitize_input($input['notes']) : $delivery['notes'];
    
    // Update delivery
    $stmt = mysqli_prepare($conn, "UPDATE deliveries SET status = ?, notes = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $status, $notes, $delivery_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Send notification to customer and business
        $status_messages = [
            'assigned' => 'تحویل اختصاص یافت',
            'picked_up' => 'سفارش تحویل گرفته شد',
            'delivered' => 'سفارش تحویل داده شد',
            'cancelled' => 'تحویل لغو شد'
        ];
        
        $message = $status_messages[$status];
        
        // Notify customer
        $stmt = mysqli_prepare($conn, "
            INSERT INTO notifications (user_id, title, message, type, related_id) 
            VALUES (?, 'وضعیت تحویل', ?, 'delivery', ?)
        ");
        mysqli_stmt_bind_param($stmt, "isi", $delivery['customer_id'], $message, $delivery_id);
        mysqli_stmt_execute($stmt);
        
        // Notify business
        $stmt = mysqli_prepare($conn, "
            INSERT INTO notifications (user_id, title, message, type, related_id) 
            VALUES (?, 'وضعیت تحویل', ?, 'delivery', ?)
        ");
        mysqli_stmt_bind_param($stmt, "isi", $delivery['business_id'], $message, $delivery_id);
        mysqli_stmt_execute($stmt);
        
        send_success([], 'وضعیت تحویل با موفقیت به‌روزرسانی شد');
    } else {
        send_error('خطا در به‌روزرسانی وضعیت تحویل');
    }
}
?>