<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['phone'])) {
    $phone = trim($_GET['phone']);
    $stmt = mysqli_prepare($conn, "SELECT id, name, mobile, avatar, role FROM users WHERE mobile = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        send_success($user);
    } else {
        send_error('کاربری با این شماره یافت نشد', 404);
    }
    exit;
}

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = mysqli_prepare($conn, "SELECT id, name, mobile, avatar, role FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        send_success($user);
    } else {
        send_error('کاربری یافت نشد', 404);
    }
    exit;
}

// اگر هیچ پارامتری نبود، ادامه کد فعلی اجرا شود (برای dashboard و ...)

require_once '../includes/functions.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_profile();
        break;
    case 'PUT':
        handle_update_profile();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_profile() {
    $user_data = require_auth();
    global $conn;
    
    // Get user profile with stats
    $stmt = mysqli_prepare($conn, "
        SELECT u.*,
               (SELECT COUNT(*) FROM ads WHERE user_id = u.id AND status = 'active') as active_ads,
               (SELECT COUNT(*) FROM ads WHERE user_id = u.id) as total_ads,
               (SELECT SUM(views) FROM ads WHERE user_id = u.id) as total_views,
               (SELECT COUNT(*) FROM proposals WHERE business_id = u.id) as total_proposals
        FROM users u 
        WHERE u.id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $user_data['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($result);
    
    if (!$profile) {
        send_error('پروفایل یافت نشد', 404);
    }
    
    // Remove sensitive data
    unset($profile['created_at']);
    unset($profile['updated_at']);
    
    send_success($profile);
}

function handle_update_profile() {
    $user_data = require_auth();
    global $conn;
    
    // Handle avatar upload if present
    $avatar_filename = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $upload_result = upload_file($_FILES['avatar'], ['jpg', 'jpeg', 'png']);
        if ($upload_result['success']) {
            $avatar_filename = $upload_result['filename'];
        } else {
            send_error($upload_result['message']);
        }
    }
    
    // Get form data
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : null;
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : null;
    $city = isset($_POST['city']) ? sanitize_input($_POST['city']) : null;
    $bio = isset($_POST['bio']) ? sanitize_input($_POST['bio']) : null;
    
    // Validate email if provided
    if ($email && !validate_email($email)) {
        send_error('آدرس ایمیل نامعتبر است');
    }
    
    // Build update query
    $update_fields = [];
    $params = [];
    $param_types = "";
    
    if ($name) {
        $update_fields[] = "name = ?";
        $params[] = $name;
        $param_types .= "s";
    }
    
    if ($email !== null) {
        $update_fields[] = "email = ?";
        $params[] = $email;
        $param_types .= "s";
    }
    
    if ($city) {
        $update_fields[] = "city = ?";
        $params[] = $city;
        $param_types .= "s";
    }
    
    if ($bio !== null) {
        $update_fields[] = "bio = ?";
        $params[] = $bio;
        $param_types .= "s";
    }
    
    if ($avatar_filename) {
        $update_fields[] = "avatar = ?";
        $params[] = $avatar_filename;
        $param_types .= "s";
    }
    
    if (empty($update_fields)) {
        send_error('هیچ فیلدی برای به‌روزرسانی ارسال نشده است');
    }
    
    $params[] = $user_data['user_id'];
    $param_types .= "i";
    
    $query = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    
    if (mysqli_stmt_execute($stmt)) {
        // Get updated profile
        $updated_profile = get_user_by_id($user_data['user_id']);
        unset($updated_profile['created_at']);
        unset($updated_profile['updated_at']);
        
        send_success($updated_profile, 'پروفایل با موفقیت به‌روزرسانی شد');
    } else {
        send_error('خطا در به‌روزرسانی پروفایل');
    }
}
?>