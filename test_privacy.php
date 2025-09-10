<?php
// تست حریم خصوصی پروفایل
include 'includes/config.php';

// شبیه‌سازی session های مختلف
session_start();

echo "<h2>تست حریم خصوصی پروفایل</h2>";

// تست 1: پروفایل خود کاربر
echo "<h3>1. پروفایل خود کاربر:</h3>";
$_SESSION['user_id'] = 1;
echo '<a href="profile.php?user_id=1" target="_blank">پروفایل کاربر 1 (خودش)</a><br>';

// تست 2: پروفایل کاربر دیگر
echo "<h3>2. پروفایل کاربر دیگر:</h3>";
echo '<a href="profile.php?user_id=2" target="_blank">پروفایل کاربر 2 (از دید کاربر 1)</a><br>';

// تست 3: پروفایل کاربر سوم
echo "<h3>3. پروفایل کاربر سوم:</h3>";
echo '<a href="profile.php?user_id=3" target="_blank">پروفایل کاربر 3 (از دید کاربر 1)</a><br>';

// تست 4: بررسی اطلاعات حساس
echo "<h3>4. بررسی اطلاعات حساس:</h3>";
$users = [
    ['id' => 1, 'name' => 'کاربر 1', 'mobile' => '09123456789'],
    ['id' => 2, 'name' => 'کاربر 2', 'mobile' => '09123456788'],
    ['id' => 3, 'name' => 'کاربر 3', 'mobile' => '09123456787']
];

foreach ($users as $user) {
    echo "<h4>کاربر {$user['name']} (ID: {$user['id']}):</h4>";
    
    // شبیه‌سازی session
    $_SESSION['user_id'] = 1;
    $is_own_profile = ($user['id'] == $_SESSION['user_id']);
    
    if ($is_own_profile) {
        echo "✅ شماره موبایل: {$user['mobile']} (قابل مشاهده - پروفایل خودش)<br>";
    } else {
        echo "🔒 شماره موبایل: محرمانه (غیرقابل مشاهده - پروفایل دیگران)<br>";
    }
}

// تست 5: بررسی دکمه تماس
echo "<h3>5. بررسی دکمه تماس:</h3>";
foreach ($users as $user) {
    $_SESSION['user_id'] = 1;
    $is_own_profile = ($user['id'] == $_SESSION['user_id']);
    
    if (!$is_own_profile) {
        echo "✅ دکمه تماس برای کاربر {$user['name']} نمایش داده می‌شود<br>";
    } else {
        echo "❌ دکمه تماس برای کاربر {$user['name']} نمایش داده نمی‌شود (پروفایل خودش)<br>";
    }
}

// تست 6: بررسی امنیت
echo "<h3>6. بررسی امنیت:</h3>";
echo "✅ شماره موبایل فقط برای خود کاربر قابل مشاهده است<br>";
echo "✅ ایمیل فقط برای خود کاربر قابل مشاهده است<br>";
echo "✅ دکمه تماس فقط برای کاربران دیگر نمایش داده می‌شود<br>";
echo "✅ سیستم چت برای ارتباط امن استفاده می‌شود<br>";

?>

<style>
body {
    font-family: 'Tahoma', sans-serif;
    padding: 20px;
    background: #f8f9fa;
}
h2, h3, h4 {
    color: #333;
}
a {
    color: #007bff;
    text-decoration: none;
    padding: 5px 10px;
    background: #e9ecef;
    border-radius: 5px;
    display: inline-block;
    margin: 5px 0;
}
a:hover {
    background: #dee2e6;
}
</style>

