<?php
// تست API سیستم امتیازدهی
include 'includes/config.php';

echo "<h2>تست API سیستم امتیازدهی</h2>";

// تست 1: بررسی وجود جداول
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

// تست 2: بررسی API endpoint
echo "<h3>2. تست API endpoint:</h3>";
$api_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api/ratings.php';
echo "URL API: <a href='$api_url' target='_blank'>$api_url</a><br>";

// تست 3: بررسی فایل API
echo "<h3>3. بررسی فایل API:</h3>";
if (file_exists('api/ratings.php')) {
    echo "✅ فایل api/ratings.php موجود است<br>";
} else {
    echo "❌ فایل api/ratings.php موجود نیست<br>";
}

// تست 4: بررسی دسترسی به API
echo "<h3>4. تست دسترسی به API:</h3>";
$test_url = $api_url . '?user_id=1';
echo "تست URL: <a href='$test_url' target='_blank'>$test_url</a><br>";

// تست 5: بررسی JavaScript Console
echo "<h3>5. تست JavaScript:</h3>";
echo '<button onclick="testAPI()" class="btn btn-primary">تست API با JavaScript</button>';
echo '<div id="apiResult" style="margin-top: 10px;"></div>';

// تست 6: بررسی CORS
echo "<h3>6. بررسی CORS Headers:</h3>";
$headers = get_headers($api_url);
foreach ($headers as $header) {
    if (strpos($header, 'Access-Control') !== false) {
        echo "✅ $header<br>";
    }
}

?>

<script>
function testAPI() {
    const resultDiv = document.getElementById('apiResult');
    resultDiv.innerHTML = 'در حال تست...';
    
    fetch('api/ratings.php?user_id=1')
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text();
    })
    .then(data => {
        console.log('Response data:', data);
        resultDiv.innerHTML = '<pre>' + data + '</pre>';
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = '<div class="alert alert-danger">خطا: ' + error.message + '</div>';
    });
}
</script>

<style>
.btn {
    padding: 10px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.alert {
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
}
.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    overflow-x: auto;
}
</style>
