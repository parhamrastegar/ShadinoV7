<?php
// Simple test to check if images are accessible
echo "<h2>تست ساده دسترسی به عکس‌ها</h2>";

// List all files in uploads
$files = glob('uploads/*.jpg');
echo "<p><strong>تعداد فایل‌های .jpg:</strong> " . count($files) . "</p>";

if (empty($files)) {
    echo "<p style='color: red;'>❌ هیچ فایل .jpg یافت نشد!</p>";
} else {
    echo "<h3>فایل‌های موجود:</h3>";
    foreach ($files as $file) {
        $filename = basename($file);
        echo "<div style='border: 1px solid #ccc; margin: 10px 0; padding: 10px; border-radius: 5px;'>";
        echo "<h4>$filename</h4>";
        echo "<p><strong>مسیر کامل:</strong> $file</p>";
        echo "<p><strong>وجود فایل:</strong> " . (file_exists($file) ? '✅ بله' : '❌ خیر') . "</p>";
        
        if (file_exists($file)) {
            echo "<p><strong>اندازه:</strong> " . filesize($file) . " بایت</p>";
            echo "<p><strong>قابل خواندن:</strong> " . (is_readable($file) ? '✅ بله' : '❌ خیر') . "</p>";
            
            // Test image display
            echo "<div style='margin: 10px 0;'>";
            echo "<strong>تست نمایش:</strong><br>";
            echo "<img src='$file' style='max-width: 200px; height: auto; border: 2px solid #ccc;' ";
            echo "alt='$filename' ";
            echo "onerror=\"this.style.border='3px solid red'; this.alt='خطا در بارگذاری'; console.error('Image error:', this.src);\" ";
            echo "onload=\"this.style.border='3px solid green'; console.log('Image success:', this.src);\">";
            echo "</div>";
        }
        echo "</div>";
    }
}

// Test database connection
echo "<h3>تست اتصال به دیتابیس:</h3>";
try {
    include 'includes/config.php';
    echo "<p>✅ اتصال به دیتابیس موفق</p>";
    
    // Check portfolio table
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_portfolios'");
    if (mysqli_num_rows($table_check) > 0) {
        echo "<p>✅ جدول user_portfolios موجود است</p>";
        
        // Get portfolio count
        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM user_portfolios");
        $row = mysqli_fetch_assoc($result);
        echo "<p><strong>تعداد نمونه کارها:</strong> " . $row['count'] . "</p>";
    } else {
        echo "<p>❌ جدول user_portfolios موجود نیست</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ خطا در اتصال به دیتابیس: " . $e->getMessage() . "</p>";
}
?>
