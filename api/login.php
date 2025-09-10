<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('متد درخواست نامعتبر است', 405);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($input['mobile']) || empty(trim($input['mobile']))) {
    send_error('شماره موبایل الزامی است');
}

if (!isset($input['password']) || empty(trim($input['password']))) {
    send_error('رمز عبور الزامی است');
}

$mobile = sanitize_input($input['mobile']);
$password = $input['password'];

// Validate mobile number
if (!validate_mobile($mobile)) {
    send_error('شماره موبایل نامعتبر است');
}

// Check if user exists
$user = get_user_by_mobile($mobile);
if (!$user) {
    send_error('کاربری با این شماره موبایل یافت نشد');
}

// Check if user is active (optional check)
if (isset($user['is_active']) && !$user['is_active']) {
    send_error('حساب کاربری شما غیرفعال است');
}

// Verify password
if (!verify_password($password, $user['password'])) {
    send_error('رمز عبور اشتباه است');
}

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_mobile'] = $user['mobile'];
$_SESSION['user_role'] = $user['role'];

send_success([
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'mobile' => $user['mobile'],
        'role' => $user['role'],
        'city' => $user['city'],
        'email' => $user['email'],
        'avatar' => $user['avatar']
    ]
], 'ورود با موفقیت انجام شد');
?>