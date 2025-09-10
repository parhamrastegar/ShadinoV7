<?php
// اسکریپت ایجاد جداول سیستم امتیازدهی
include 'includes/config.php';

echo "<h2>ایجاد جداول سیستم امتیازدهی</h2>";

try {
    // ایجاد جدول user_ratings
    $sql1 = "CREATE TABLE IF NOT EXISTS user_ratings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        rater_id INT NOT NULL,
        rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        comment TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (rater_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_rating (user_id, rater_id),
        INDEX idx_user_id (user_id),
        INDEX idx_rater_id (rater_id),
        INDEX idx_rating (rating),
        INDEX idx_created_at (created_at)
    )";
    
    if (mysqli_query($conn, $sql1)) {
        echo "✅ جدول user_ratings با موفقیت ایجاد شد<br>";
    } else {
        echo "❌ خطا در ایجاد جدول user_ratings: " . mysqli_error($conn) . "<br>";
    }
    
    // ایجاد جدول user_stats
    $sql2 = "CREATE TABLE IF NOT EXISTS user_stats (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL UNIQUE,
        total_ratings INT DEFAULT 0,
        average_rating DECIMAL(3,2) DEFAULT 0.00,
        total_comments INT DEFAULT 0,
        last_rating_date DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_average_rating (average_rating)
    )";
    
    if (mysqli_query($conn, $sql2)) {
        echo "✅ جدول user_stats با موفقیت ایجاد شد<br>";
    } else {
        echo "❌ خطا در ایجاد جدول user_stats: " . mysqli_error($conn) . "<br>";
    }
    
    // ایجاد رکوردهای آمار برای کاربران موجود
    $sql3 = "INSERT IGNORE INTO user_stats (user_id) SELECT id FROM users";
    
    if (mysqli_query($conn, $sql3)) {
        echo "✅ رکوردهای آمار برای کاربران موجود ایجاد شد<br>";
    } else {
        echo "❌ خطا در ایجاد رکوردهای آمار: " . mysqli_error($conn) . "<br>";
    }
    
    // اضافه کردن داده‌های نمونه
    $sql4 = "INSERT IGNORE INTO user_ratings (user_id, rater_id, rating, comment) VALUES
    (1, 2, 5, 'خدمات عالی و کیفیت بالا'),
    (1, 3, 4, 'خیلی راضی بودم'),
    (2, 1, 5, 'پیشنهادات خوبی ارائه داد'),
    (2, 3, 3, 'قابل قبول بود'),
    (3, 1, 4, 'تحویل به موقع'),
    (3, 2, 5, 'بسیار سریع و دقیق')";
    
    if (mysqli_query($conn, $sql4)) {
        echo "✅ داده‌های نمونه اضافه شد<br>";
    } else {
        echo "❌ خطا در اضافه کردن داده‌های نمونه: " . mysqli_error($conn) . "<br>";
    }
    
    // بروزرسانی آمار کاربران
    $sql5 = "UPDATE user_stats SET 
        total_ratings = (SELECT COUNT(*) FROM user_ratings WHERE user_id = user_stats.user_id),
        average_rating = (SELECT AVG(rating) FROM user_ratings WHERE user_id = user_stats.user_id),
        total_comments = (SELECT COUNT(*) FROM user_ratings WHERE user_id = user_stats.user_id AND comment IS NOT NULL AND comment != ''),
        last_rating_date = (SELECT MAX(created_at) FROM user_ratings WHERE user_id = user_stats.user_id)";
    
    if (mysqli_query($conn, $sql5)) {
        echo "✅ آمار کاربران بروزرسانی شد<br>";
    } else {
        echo "❌ خطا در بروزرسانی آمار: " . mysqli_error($conn) . "<br>";
    }
    
    echo "<h3>✅ نصب کامل شد!</h3>";
    echo "<p><a href='profile.php'>برو به پروفایل</a></p>";
    echo "<p><a href='test_rating_system.php'>تست سیستم</a></p>";
    
} catch (Exception $e) {
    echo "❌ خطای کلی: " . $e->getMessage();
}
?>
