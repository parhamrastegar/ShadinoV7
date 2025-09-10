<?php
// تست مستقیم API ratings
include 'includes/config.php';

echo "<h2>تست مستقیم API ratings</h2>";

// تست 1: بررسی جداول
echo "<h3>1. بررسی جداول:</h3>";
$tables = ['user_ratings', 'user_stats'];
foreach ($tables as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "✅ جدول $table موجود است<br>";
    } else {
        echo "❌ جدول $table موجود نیست<br>";
    }
}

// تست 2: تست GET request
echo "<h3>2. تست GET request:</h3>";
if (isset($_GET['test_get'])) {
    $_GET['user_id'] = 1;
    include 'api/ratings.php';
    exit;
}

echo '<a href="?test_get=1" class="btn">تست GET API</a><br><br>';

// تست 3: تست POST request
echo "<h3>3. تست POST request:</h3>";
if (isset($_POST['test_post'])) {
    // شبیه‌سازی POST request
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    
    $test_data = [
        'user_id' => 1,
        'rating' => 5,
        'comment' => 'تست API'
    ];
    
    // شبیه‌سازی input stream
    $GLOBALS['HTTP_RAW_POST_DATA'] = json_encode($test_data);
    
    // بازنویسی file_get_contents('php://input')
    function mock_file_get_contents($filename) {
        if ($filename === 'php://input') {
            return $GLOBALS['HTTP_RAW_POST_DATA'];
        }
        return file_get_contents($filename);
    }
    
    // جایگزینی موقت
    if (!function_exists('file_get_contents_original')) {
        function file_get_contents_original($filename) {
            return file_get_contents($filename);
        }
    }
    
    include 'api/ratings.php';
    exit;
}

echo '<form method="post">
    <input type="hidden" name="test_post" value="1">
    <button type="submit" class="btn">تست POST API</button>
</form><br>';

// تست 4: بررسی فایل API
echo "<h3>4. بررسی فایل API:</h3>";
if (file_exists('api/ratings.php')) {
    echo "✅ فایل api/ratings.php موجود است<br>";
    
    // بررسی syntax
    $syntax_check = shell_exec('php -l api/ratings.php 2>&1');
    if (strpos($syntax_check, 'No syntax errors') !== false) {
        echo "✅ Syntax فایل API صحیح است<br>";
    } else {
        echo "❌ خطای syntax در فایل API:<br>";
        echo "<pre>$syntax_check</pre>";
    }
} else {
    echo "❌ فایل api/ratings.php موجود نیست<br>";
}

// تست 5: بررسی توابع
echo "<h3>5. بررسی توابع:</h3>";
if (function_exists('get_user_by_id')) {
    echo "✅ تابع get_user_by_id موجود است<br>";
} else {
    echo "❌ تابع get_user_by_id موجود نیست<br>";
}

if (function_exists('get_user_stats')) {
    echo "✅ تابع get_user_stats موجود است<br>";
} else {
    echo "❌ تابع get_user_stats موجود نیست<br>";
}

// تست 6: بررسی session
echo "<h3>6. بررسی session:</h3>";
session_start();
if (isset($_SESSION['user_id'])) {
    echo "✅ کاربر وارد شده است (ID: " . $_SESSION['user_id'] . ")<br>";
} else {
    echo "❌ کاربر وارد نشده است<br>";
    echo '<a href="login.php">ورود به سیستم</a><br>';
}

?>

<style>
.btn {
    display: inline-block;
    padding: 10px 20px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin: 5px;
}
.btn:hover {
    background: #0056b3;
}
pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    overflow-x: auto;
}
</style>
