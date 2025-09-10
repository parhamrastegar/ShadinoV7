<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    send_error('متد درخواست نامعتبر است', 405);
}

// Check authentication
$user_data = require_auth();

$user_id = $user_data['id'];

// Get user profile data
$stmt = mysqli_prepare($conn, "SELECT profile_data FROM user_profiles WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$profile_row = mysqli_fetch_assoc($result);

$profile_data = [];
if ($profile_row && $profile_row['profile_data']) {
    $profile_data = json_decode($profile_row['profile_data'], true);
}

send_success([
    'user' => [
        'id' => $user_data['id'],
        'name' => $user_data['name'],
        'mobile' => $user_data['mobile'],
        'role' => $user_data['role'],
        'city' => $user_data['city'],
        'email' => $user_data['email'],
        'avatar' => $user_data['avatar'],
        'bio' => $user_data['bio']
    ],
    'profile_data' => $profile_data
], 'اطلاعات پروفایل دریافت شد');
?>
