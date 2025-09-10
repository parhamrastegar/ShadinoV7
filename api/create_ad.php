<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../makeAds.php');
    exit();
}

// Get form data
$user_id = $_SESSION['user_id'];
$category = isset($_POST['category']) ? sanitize_input($_POST['category']) : '';
$title = isset($_POST['title']) ? sanitize_input($_POST['title']) : '';
$description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';
$budget_min = isset($_POST['budget_min']) ? intval($_POST['budget_min']) : 0;
$budget_max = isset($_POST['budget_max']) ? intval($_POST['budget_max']) : 0;
$city = isset($_POST['city']) ? sanitize_input($_POST['city']) : '';
$event_date = isset($_POST['event_date']) ? sanitize_input($_POST['event_date']) : '';
$deadline = isset($_POST['deadline']) ? sanitize_input($_POST['deadline']) : '';
$priority = isset($_POST['priority']) ? sanitize_input($_POST['priority']) : '';

// Debug: Log the received data
error_log("Received form data: " . print_r($_POST, true));

// Validate required fields
$errors = [];
if (empty($category)) $errors[] = 'دسته‌بندی الزامی است';
if (empty($title)) $errors[] = 'عنوان آگهی الزامی است';
if (empty($description)) $errors[] = 'توضیحات الزامی است';
if ($budget_min <= 0) $errors[] = 'حداقل بودجه باید بیشتر از صفر باشد';
if ($budget_max <= 0) $errors[] = 'حداکثر بودجه باید بیشتر از صفر باشد';
if ($budget_max < $budget_min) $errors[] = 'حداکثر بودجه باید بیشتر از حداقل بودجه باشد';
if (empty($city)) $errors[] = 'شهر الزامی است';
if (empty($event_date)) $errors[] = 'تاریخ مراسم الزامی است';
if (empty($deadline)) $errors[] = 'مهلت پیشنهاد الزامی است';

// Check if there are validation errors
if (!empty($errors)) {
    $_SESSION['ad_errors'] = $errors;
    $_SESSION['ad_form_data'] = $_POST;
    header('Location: ../makeAds.php?error=validation');
    exit();
}

// Validate category
$valid_categories = ['cake', 'decoration', 'photography', 'music', 'ceremony', 'catering', 'other'];
if (!in_array($category, $valid_categories)) {
    $_SESSION['ad_errors'] = ['دسته‌بندی نامعتبر است'];
    header('Location: ../makeAds.php?error=invalid_category');
    exit();
}

// Validate dates
$event_date_obj = DateTime::createFromFormat('Y-m-d', $event_date);
$deadline_obj = DateTime::createFromFormat('Y-m-d', $deadline);
$now = new DateTime();

if (!$event_date_obj || !$deadline_obj) {
    $_SESSION['ad_errors'] = ['فرمت تاریخ نامعتبر است'];
    header('Location: ../makeAds.php?error=invalid_date');
    exit();
}

if ($event_date_obj < $now) {
    $_SESSION['ad_errors'] = ['تاریخ مراسم نمی‌تواند در گذشته باشد'];
    header('Location: ../makeAds.php?error=past_event_date');
    exit();
}

if ($deadline_obj < $now) {
    $_SESSION['ad_errors'] = ['مهلت پیشنهاد نمی‌تواند در گذشته باشد'];
    header('Location: ../makeAds.php?error=past_deadline');
    exit();
}

if ($deadline_obj > $event_date_obj) {
    $_SESSION['ad_errors'] = ['مهلت پیشنهاد باید قبل از تاریخ مراسم باشد'];
    header('Location: ../makeAds.php?error=deadline_after_event');
    exit();
}

// Handle file uploads if any
$images = [];
if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $_FILES['images']['name'][$key],
                'type' => $_FILES['images']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['images']['error'][$key],
                'size' => $_FILES['images']['size'][$key]
            ];
            
            $upload_result = upload_file($file, ['jpg', 'jpeg', 'png', 'gif']);
            if ($upload_result['success']) {
                $images[] = $upload_result['filename'];
            }
        }
    }
}

// Insert ad into database
$stmt = $conn->prepare("
    INSERT INTO ads (user_id, title, description, category, budget_min, budget_max, city, event_date, deadline, images, status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())
");

$images_json = !empty($images) ? json_encode($images) : null;

mysqli_stmt_bind_param($stmt, "isssiissss", 
    $user_id, $title, $description, $category, $budget_min, $budget_max, $city, $event_date, $deadline, $images_json
);

if (mysqli_stmt_execute($stmt)) {
    $ad_id = mysqli_insert_id($conn);
    
    // Clear any previous errors
    unset($_SESSION['ad_errors']);
    unset($_SESSION['ad_form_data']);
    
    // Set success message
    $_SESSION['ad_success'] = 'آگهی با موفقیت ایجاد شد! 🎉';
    
    // Redirect to the created ad
    header("Location: ../openedAds.php?id={$ad_id}&created=1");
    exit();
} else {
    $_SESSION['ad_errors'] = ['خطا در ایجاد آگهی: ' . mysqli_error($conn)];
    $_SESSION['ad_form_data'] = $_POST;
    header('Location: ../makeAds.php?error=database');
    exit();
}
?>
