<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Helper function for sanitizing input
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_proposals();
        break;
    case 'POST':
        handle_create_proposal();
        break;
    case 'PUT':
        handle_update_proposal();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_proposals() {
    global $conn;
    
    // Get proposals for a specific ad
    if (isset($_GET['ad_id'])) {
        $ad_id = intval($_GET['ad_id']);
        
        $stmt = mysqli_prepare($conn, "
            SELECT p.*, u.name as business_name, u.mobile as business_mobile, 
                   u.avatar as business_avatar, u.city as business_city
            FROM proposals p
            JOIN users u ON p.business_id = u.id
            WHERE p.ad_id = ?
            ORDER BY p.created_at DESC
        ");
        mysqli_stmt_bind_param($stmt, "i", $ad_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $proposals = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['extra_services'] = json_decode($row['extra_services'], true) ?: [];
            $proposals[] = $row;
        }
        
        send_success($proposals);
    }
    
    // Get proposals by business (for business dashboard)
    $user_data = require_auth();
    
    if ($user_data['role'] !== 'business') {
        send_error('فقط کسب‌وکارها می‌توانند پیشنهادات خود را مشاهده کنند', 403);
    }
    
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;
    
    $stmt = mysqli_prepare($conn, "
        SELECT p.*, a.title as ad_title, a.category, a.city as ad_city, a.event_date,
               u.name as customer_name
        FROM proposals p
        JOIN ads a ON p.ad_id = a.id
        JOIN users u ON a.user_id = u.id
        WHERE p.business_id = ?
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?
    ");
    mysqli_stmt_bind_param($stmt, "iii", $user_data['id'], $per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $proposals = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['extra_services'] = json_decode($row['extra_services'], true) ?: [];
        $proposals[] = $row;
    }
    
    // Get total count
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM proposals WHERE business_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_data['id']);
    mysqli_stmt_execute($stmt);
    $count_result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    send_success([
        'proposals' => $proposals,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]
    ]);
}

function handle_create_proposal() {
    global $conn;
    
    // For public proposal submissions, we don't require authentication
    // but we need to validate the input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        send_error('داده‌های ارسالی نامعتبر است', 400);
    }
    
    // Validate required fields
    if (empty($input['ad_id']) || empty($input['description']) || empty($input['price']) || empty($input['contact'])) {
        send_error('تمام فیلدهای ضروری باید پر شوند', 400);
    }
    
    // Validate ad_id
    $ad_id = intval($input['ad_id']);
    if ($ad_id <= 0) {
        send_error('شناسه آگهی نامعتبر است', 400);
    }
    
    // Check if ad exists and is active
    $stmt = mysqli_prepare($conn, "SELECT id, status FROM ads WHERE id = ? AND status = 'active'");
    mysqli_stmt_bind_param($stmt, "i", $ad_id);
    mysqli_stmt_execute($stmt);
    $ad_result = mysqli_stmt_get_result($stmt);
    $ad = mysqli_fetch_assoc($ad_result);
    
    if (!$ad) {
        send_error('آگهی یافت نشد یا غیرفعال است', 404);
    }
    
    // Create a temporary business user or find existing one
    $business_name = isset($input['business_name']) ? sanitize_input($input['business_name']) : 'کاربر مهمان';
    
    // Try to find existing user with this business name, or create a new one
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE name = ? AND role = 'business' LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $business_name);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);
    $existing_user = mysqli_fetch_assoc($user_result);
    
    if ($existing_user) {
        $business_id = $existing_user['id'];
    } else {
        // Create a new temporary business user
        $temp_mobile = 'guest_' . time() . '_' . rand(1000, 9999);
        $temp_password = password_hash('temp123', PASSWORD_DEFAULT);
        
        $stmt = mysqli_prepare($conn, "
            INSERT INTO users (name, mobile, password, role, city, is_active) 
            VALUES (?, ?, ?, 'business', 'نامشخص', 1)
        ");
        mysqli_stmt_bind_param($stmt, "sss", $business_name, $temp_mobile, $temp_password);
        
        if (mysqli_stmt_execute($stmt)) {
            $business_id = mysqli_insert_id($conn);
        } else {
            // Fallback to default business ID if creation fails
            $business_id = 1;
        }
    }
    
    // Check if proposal already exists from this business
    $stmt = mysqli_prepare($conn, "SELECT id FROM proposals WHERE ad_id = ? AND business_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $ad_id, $business_id);
    mysqli_stmt_execute($stmt);
    $existing_result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($existing_result) > 0) {
        send_error('شما قبلاً برای این آگهی پیشنهاد داده‌اید', 400);
    }
    
    // Get input data (support both JSON and FormData)
    $input = [];
    if ($_SERVER['CONTENT_TYPE'] && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
    } else {
        $input = $_POST;
    }
    
    // Validate required fields
    $required_fields = ['ad_id', 'price', 'description', 'contact'];
    foreach ($required_fields as $field) {
        if (!isset($input[$field]) || empty(trim($input[$field]))) {
            send_error("فیلد $field الزامی است");
        }
    }
    
    $ad_id = intval($input['ad_id']);
    $price = intval($input['price']);
    $description = sanitize_input($input['description']);
    $contact = sanitize_input($input['contact']);
    $address = isset($input['address']) ? sanitize_input($input['address']) : null;
    $delivery_time = isset($input['delivery_time']) ? sanitize_input($input['delivery_time']) : null;
    $extra_services = isset($input['extra_services']) ? sanitize_input($input['extra_services']) : '';
    
    // Convert extra_services to JSON if it's not already
    if (!empty($extra_services) && !is_array(json_decode($extra_services, true))) {
        $extra_services = json_encode([$extra_services]);
    } elseif (empty($extra_services)) {
        $extra_services = '[]';
    }
    
    // Validate price
    if ($price <= 0) {
        send_error('قیمت نامعتبر است');
    }
    
    // Check if ad exists and is active
    $stmt = mysqli_prepare($conn, "SELECT * FROM ads WHERE id = ? AND status = 'active'");
    mysqli_stmt_bind_param($stmt, "i", $ad_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ad = mysqli_fetch_assoc($result);
    
    if (!$ad) {
        send_error('آگهی یافت نشد یا غیرفعال است', 404);
    }
    
    // Check if business already submitted a proposal for this ad
    $stmt = mysqli_prepare($conn, "SELECT id FROM proposals WHERE ad_id = ? AND business_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $ad_id, $business_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_fetch_assoc($result)) {
        send_error('شما قبلاً برای این آگهی پیشنهاد ثبت کرده‌اید');
    }
    
    // Extract data from input
    $price = intval($input['price']);
    $description = sanitize_input($input['description']);
    $contact = sanitize_input($input['contact']);
    $address = isset($input['address']) ? sanitize_input($input['address']) : '';
    $delivery_time = isset($input['delivery_time']) ? sanitize_input($input['delivery_time']) : '';
    $extra_services = isset($input['extra_services']) ? $input['extra_services'] : '[]';
    
    // Insert proposal
    $stmt = mysqli_prepare($conn, "
        INSERT INTO proposals (ad_id, business_id, price, description, extra_services, contact, address, delivery_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iiisssss", 
        $ad_id, $business_id, $price, $description, 
        $extra_services, $contact, $address, $delivery_time
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $proposal_id = mysqli_insert_id($conn);
        
        // Create a new conversation by sending the first message
        // This ensures a chat is created between business and customer
        $proposal_message = "🎯 **پیشنهاد جدید**\n\n💰 قیمت: " . number_format($price) . " تومان\n📝 توضیحات: $description";
        if ($address) $proposal_message .= "\n📍 آدرس: $address";
        if ($delivery_time) $proposal_message .= "\n⏰ زمان تحویل: $delivery_time";
        if ($extra_services && $extra_services != '[]') {
            $services = json_decode($extra_services, true);
            if (is_array($services)) {
                $proposal_message .= "\n🎁 خدمات اضافی: " . implode(', ', $services);
            }
        }
        $proposal_message .= "\n📞 تماس: $contact";
        
        // Insert the proposal message into messages table
        $stmt = mysqli_prepare($conn, "
            INSERT INTO messages (sender_id, receiver_id, ad_id, message, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        mysqli_stmt_bind_param($stmt, "iiis", 
            $business_id, $ad['user_id'], $ad_id, $proposal_message
        );
        mysqli_stmt_execute($stmt);
        
        // Send notification to ad owner
        $stmt = mysqli_prepare($conn, "
            INSERT INTO notifications (user_id, title, message, type, related_id) 
            VALUES (?, 'پیشنهاد جدید', 'پیشنهاد جدیدی برای آگهی شما ثبت شد', 'proposal', ?)
        ");
        mysqli_stmt_bind_param($stmt, "ii", $ad['user_id'], $proposal_id);
        mysqli_stmt_execute($stmt);
        
        send_success(['proposal_id' => $proposal_id], 'پیشنهاد با موفقیت ثبت شد');
    } else {
        send_error('خطا در ثبت پیشنهاد');
    }
}

function handle_update_proposal() {
    global $conn;
    $user_data = require_auth();
    
    // Get proposal ID from URL
    $proposal_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$proposal_id) {
        send_error('شناسه پیشنهاد نامعتبر است');
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Check if user owns this proposal or is the ad owner
    $stmt = mysqli_prepare($conn, "
        SELECT p.*, a.user_id as ad_owner_id 
        FROM proposals p 
        JOIN ads a ON p.ad_id = a.id 
        WHERE p.id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $proposal_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $proposal = mysqli_fetch_assoc($result);
    
    if (!$proposal) {
        send_error('پیشنهاد یافت نشد', 404);
    }
    
    // Business can update their own proposal
    if ($user_data['id'] == $proposal['business_id']) {
        // Update proposal details
        $update_fields = [];
        $params = [];
        $param_types = "";
        
        if (isset($input['price'])) {
            $update_fields[] = "price = ?";
            $params[] = intval($input['price']);
            $param_types .= "i";
        }
        
        if (isset($input['description'])) {
            $update_fields[] = "description = ?";
            $params[] = sanitize_input($input['description']);
            $param_types .= "s";
        }
        
        if (isset($input['contact'])) {
            $update_fields[] = "contact = ?";
            $params[] = sanitize_input($input['contact']);
            $param_types .= "s";
        }
        
        if (isset($input['address'])) {
            $update_fields[] = "address = ?";
            $params[] = sanitize_input($input['address']);
            $param_types .= "s";
        }
        
        if (isset($input['delivery_time'])) {
            $update_fields[] = "delivery_time = ?";
            $params[] = sanitize_input($input['delivery_time']);
            $param_types .= "s";
        }
        
        if (isset($input['extra_services'])) {
            $update_fields[] = "extra_services = ?";
            $params[] = json_encode($input['extra_services']);
            $param_types .= "s";
        }
        
        if (empty($update_fields)) {
            send_error('هیچ فیلدی برای به‌روزرسانی ارسال نشده است');
        }
        
        $params[] = $proposal_id;
        $param_types .= "i";
        
        $query = "UPDATE proposals SET " . implode(', ', $update_fields) . " WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
        
        if (mysqli_stmt_execute($stmt)) {
            send_success([], 'پیشنهاد با موفقیت به‌روزرسانی شد');
        } else {
            send_error('خطا در به‌روزرسانی پیشنهاد');
        }
    }
    // Ad owner can accept/reject proposal
    elseif ($user_data['id'] == $proposal['ad_owner_id']) {
        if (!isset($input['status']) || !in_array($input['status'], ['accepted', 'rejected'])) {
            send_error('وضعیت نامعتبر است');
        }
        
        $status = $input['status'];
        
        mysqli_begin_transaction($conn);
        
        try {
            // Update proposal status
            $stmt = mysqli_prepare($conn, "UPDATE proposals SET status = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $status, $proposal_id);
            mysqli_stmt_execute($stmt);
            
            // If accepted, close the ad and reject other proposals
            if ($status === 'accepted') {
                $stmt = mysqli_prepare($conn, "UPDATE ads SET status = 'closed' WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $proposal['ad_id']);
                mysqli_stmt_execute($stmt);
                
                $stmt = mysqli_prepare($conn, "UPDATE proposals SET status = 'rejected' WHERE ad_id = ? AND id != ?");
                mysqli_stmt_bind_param($stmt, "ii", $proposal['ad_id'], $proposal_id);
                mysqli_stmt_execute($stmt);
            }
            
            // Send notification to business
            $message = $status === 'accepted' ? 'پیشنهاد شما پذیرفته شد' : 'پیشنهاد شما رد شد';
            $stmt = mysqli_prepare($conn, "
                INSERT INTO notifications (user_id, title, message, type, related_id) 
                VALUES (?, 'وضعیت پیشنهاد', ?, 'proposal', ?)
            ");
            mysqli_stmt_bind_param($stmt, "isi", $proposal['business_id'], $message, $proposal_id);
            mysqli_stmt_execute($stmt);
            
            mysqli_commit($conn);
            
            send_success([], 'وضعیت پیشنهاد با موفقیت به‌روزرسانی شد');
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            send_error('خطا در به‌روزرسانی وضعیت پیشنهاد');
        }
    }
    else {
        send_error('شما مجاز به انجام این عملیات نیستید', 403);
    }
}
?>