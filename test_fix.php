<?php
// تست رفع مشکل redeclaration
echo "<h2>تست رفع مشکل redeclaration</h2>";

// تست 1: بررسی include files
echo "<h3>1. بررسی include files:</h3>";
try {
    include 'includes/config.php';
    echo "✅ includes/config.php بارگذاری شد<br>";
    
    include 'includes/functions.php';
    echo "✅ includes/functions.php بارگذاری شد<br>";
    
    include 'api/ratings.php';
    echo "✅ api/ratings.php بارگذاری شد<br>";
    
    echo "✅ همه فایل‌ها بدون خطا بارگذاری شدند<br>";
} catch (Exception $e) {
    echo "❌ خطا: " . $e->getMessage() . "<br>";
}

// تست 2: بررسی توابع
echo "<h3>2. بررسی توابع:</h3>";
$functions = ['get_user_stats', 'update_user_stats', 'get_existing_rating', 'get_rating_by_id'];
foreach ($functions as $func) {
    if (function_exists($func)) {
        echo "✅ تابع $func موجود است<br>";
    } else {
        echo "❌ تابع $func موجود نیست<br>";
    }
}

// تست 3: تست API
echo "<h3>3. تست API:</h3>";
if (isset($_GET['test_api'])) {
    $_GET['user_id'] = 1;
    include 'api/ratings.php';
    exit;
}

echo '<a href="?test_api=1" class="btn">تست API</a><br><br>';

// تست 4: بررسی syntax
echo "<h3>4. بررسی syntax:</h3>";
$files = ['includes/functions.php', 'api/ratings.php'];
foreach ($files as $file) {
    $syntax_check = shell_exec("php -l $file 2>&1");
    if (strpos($syntax_check, 'No syntax errors') !== false) {
        echo "✅ $file syntax صحیح است<br>";
    } else {
        echo "❌ $file syntax خطا دارد:<br>";
        echo "<pre>$syntax_check</pre>";
    }
}

?>

<style>
.btn {
    display: inline-block;
    padding: 10px 20px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin: 5px;
}
.btn:hover {
    background: #218838;
}
pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    overflow-x: auto;
    font-size: 12px;
}
</style>
