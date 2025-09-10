<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('متد درخواست نامعتبر است', 405);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Debug log
error_log('Registration input: ' . json_encode($input));

// Validate required fields
$required_fields = ['name', 'mobile', 'password'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty(trim($input[$field]))) {
        send_error("فیلد $field الزامی است");
    }
}

$name = sanitize_input($input['name']);
$mobile = sanitize_input($input['mobile']);
$password = $input['password'];
$role = isset($input['role']) && !empty(trim($input['role'])) ? sanitize_input($input['role']) : 'customer';
$city = isset($input['city']) ? sanitize_input($input['city']) : null;
$email = isset($input['email']) ? sanitize_input($input['email']) : null;

// Debug log role
error_log('Role received: ' . $role);

// Validate mobile number
if (!validate_mobile($mobile)) {
    send_error('شماره موبایل نامعتبر است');
}

// Validate email if provided
if ($email && !validate_email($email)) {
    send_error('آدرس ایمیل نامعتبر است');
}

// Validate role
$valid_roles = ['customer', 'business', 'delivery'];
if (!in_array($role, $valid_roles)) {
    send_error('نوع حساب کاربری نامعتبر است');
}

// Validate password strength
if (strlen($password) < 6) {
    send_error('رمز عبور باید حداقل ۶ کاراکتر باشد');
}

// Check if user already exists
$existing_user = get_user_by_mobile($mobile);
if ($existing_user) {
    send_error('کاربری با این شماره موبایل قبلاً ثبت‌نام کرده است');
}

// Hash password
$hashed_password = hash_password($password);

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert user
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, mobile, password, role, city, email) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $mobile, $hashed_password, $role, $city, $email);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('خطا در ثبت کاربر');
    }
    
    $user_id = mysqli_insert_id($conn);
    
    // Insert user profile data if provided
    $profile_data = [];
    
    if ($role === 'business') {
        $business_name = isset($input['business_name']) ? sanitize_input($input['business_name']) : '';
        $business_category = isset($input['business_category']) ? sanitize_input($input['business_category']) : '';
        
        if ($business_name || $business_category) {
            $profile_data = [
                'business_name' => $business_name,
                'business_category' => $business_category,
                'business_address' => '',
                'business_license' => ''
            ];
        }
    } elseif ($role === 'delivery') {
        $vehicle_type = isset($input['vehicle_type']) ? sanitize_input($input['vehicle_type']) : '';
        $delivery_city = isset($input['delivery_city']) ? sanitize_input($input['delivery_city']) : '';
        
        if ($vehicle_type || $delivery_city) {
            $profile_data = [
                'vehicle_type' => $vehicle_type,
                'delivery_city' => $delivery_city,
                'license_number' => '',
                'experience_years' => 0
            ];
        }
    }
    
    // Insert profile data if exists
    if (!empty($profile_data)) {
        $profile_json = json_encode($profile_data, JSON_UNESCAPED_UNICODE);
        $stmt_profile = mysqli_prepare($conn, "INSERT INTO user_profiles (user_id, profile_data) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt_profile, "is", $user_id, $profile_json);
        
        if (!mysqli_stmt_execute($stmt_profile)) {
            throw new Exception('خطا در ثبت اطلاعات پروفایل');
        }
    }
    
    mysqli_commit($conn);
    
    // Set session for auto-login
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_mobile'] = $mobile;
    $_SESSION['user_role'] = $role;
    $_SESSION['role'] = $role;
    
    send_success([
        'user_id' => $user_id,
        'user' => [
            'id' => $user_id,
            'name' => $name,
            'mobile' => $mobile,
            'role' => $role,
            'city' => $city,
            'email' => $email
        ]
    ], 'ثبت‌نام با موفقیت انجام شد');
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    send_error($e->getMessage());
}
?>