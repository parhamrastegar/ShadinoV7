<?php
// تست سیستم امتیازدهی
include 'includes/config.php';

echo "<h2>تست سیستم امتیازدهی شادینو</h2>";

// بررسی وجود جداول
$tables = ['user_ratings', 'user_stats'];
foreach ($tables as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "✅ جدول $table موجود است<br>";
    } else {
        echo "❌ جدول $table موجود نیست<br>";
    }
}

// بررسی کاربران موجود
echo "<h3>کاربران موجود:</h3>";
$result = mysqli_query($conn, "SELECT id, name, role FROM users LIMIT 5");
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: {$row['id']} - نام: {$row['name']} - نقش: {$row['role']}<br>";
}

// بررسی آمار کاربران
echo "<h3>آمار کاربران:</h3>";
$result = mysqli_query($conn, "
    SELECT u.name, us.total_ratings, us.average_rating, us.total_comments
    FROM users u
    LEFT JOIN user_stats us ON u.id = us.user_id
    LIMIT 5
");
while ($row = mysqli_fetch_assoc($result)) {
    echo "نام: {$row['name']} - امتیازات: {$row['total_ratings']} - میانگین: {$row['average_rating']} - نظرات: {$row['total_comments']}<br>";
}

// بررسی نظرات موجود
echo "<h3>نظرات موجود:</h3>";
$result = mysqli_query($conn, "
    SELECT ur.rating, ur.comment, u1.name as user_name, u2.name as rater_name
    FROM user_ratings ur
    JOIN users u1 ON ur.user_id = u1.id
    JOIN users u2 ON ur.rater_id = u2.id
    ORDER BY ur.created_at DESC
    LIMIT 5
");
while ($row = mysqli_fetch_assoc($result)) {
    echo "کاربر: {$row['user_name']} - امتیازدهنده: {$row['rater_name']} - امتیاز: {$row['rating']} - نظر: {$row['comment']}<br>";
}

echo "<h3>لینک‌های تست:</h3>";
echo '<a href="profile.php?user_id=1">پروفایل کاربر 1</a><br>';
echo '<a href="profile.php?user_id=2">پروفایل کاربر 2</a><br>';
echo '<a href="profile.php?user_id=3">پروفایل کاربر 3</a><br>';
echo '<a href="profile.php">پروفایل من</a><br>';
?>
