<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('متد درخواست نامعتبر است', 405);
}

// Check authentication
$user_data = require_auth();

$user_id = $user_data['id'];
$role = $user_data['role'];

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Prepare profile data based on role
$profile_data = [];

if ($role === 'business') {
    $business_name = isset($input['business_name']) ? sanitize_input($input['business_name']) : '';
    $business_category = isset($input['business_category']) ? sanitize_input($input['business_category']) : '';
    $business_address = isset($input['business_address']) ? sanitize_input($input['business_address']) : '';
    $business_license = isset($input['business_license']) ? sanitize_input($input['business_license']) : '';
    
    $profile_data = [
        'business_name' => $business_name,
        'business_category' => $business_category,
        'business_address' => $business_address,
        'business_license' => $business_license
    ];
} elseif ($role === 'delivery') {
    $vehicle_type = isset($input['vehicle_type']) ? sanitize_input($input['vehicle_type']) : '';
    $delivery_city = isset($input['delivery_city']) ? sanitize_input($input['delivery_city']) : '';
    $license_number = isset($input['license_number']) ? sanitize_input($input['license_number']) : '';
    $experience_years = isset($input['experience_years']) ? intval($input['experience_years']) : 0;
    
    $profile_data = [
        'vehicle_type' => $vehicle_type,
        'delivery_city' => $delivery_city,
        'license_number' => $license_number,
        'experience_years' => $experience_years
    ];
}

if (empty($profile_data)) {
    send_error('هیچ اطلاعات پروفایلی برای به‌روزرسانی ارسال نشده است');
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    $profile_json = json_encode($profile_data, JSON_UNESCAPED_UNICODE);
    
    // Check if profile exists
    $stmt_check = mysqli_prepare($conn, "SELECT id FROM user_profiles WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt_check, "i", $user_id);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result) > 0) {
        // Update existing profile
        $stmt = mysqli_prepare($conn, "UPDATE user_profiles SET profile_data = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $profile_json, $user_id);
    } else {
        // Insert new profile
        $stmt = mysqli_prepare($conn, "INSERT INTO user_profiles (user_id, profile_data) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "is", $user_id, $profile_json);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('خطا در به‌روزرسانی اطلاعات پروفایل');
    }
    
    mysqli_commit($conn);
    
    send_success([
        'profile_data' => $profile_data
    ], 'اطلاعات پروفایل با موفقیت به‌روزرسانی شد');
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    send_error($e->getMessage());
}
?>
