<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Simple router based on request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // list portfolios for a user
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    if ($user_id <= 0) send_error('user_id required', 400);

    // ensure table exists
    $tbl_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_portfolios'");
    if (!$tbl_check || mysqli_num_rows($tbl_check) == 0) {
        send_success(['portfolios' => []], 'portfolio table not installed');
    }

    $stmt = $conn->prepare('SELECT id, title, description, image, created_at FROM user_portfolios WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $items = [];
    while ($row = $res->fetch_assoc()) {
        $row['image_url'] = 'uploads/' . $row['image'];
        $items[] = $row;
    }

    send_success(['portfolios' => $items]);

} elseif ($method === 'POST') {
    // create portfolio item (multipart/form-data expected)
    $user = require_auth();
    $user_id = $user['id'];

    // Validate fields
    $title = isset($_POST['title']) ? sanitize_input($_POST['title']) : '';
    $description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';

    if (empty($title)) send_error('title required', 400);

    // handle file upload
    if (!isset($_FILES['image'])) send_error('image required', 400);
    $upload = upload_file($_FILES['image'], ['jpg','jpeg','png','gif']);
    if (!$upload['success']) send_error($upload['message'], 400);

    $image_filename = $upload['filename'];

    $stmt = $conn->prepare('INSERT INTO user_portfolios (user_id, title, description, image, created_at) VALUES (?, ?, ?, ?, NOW())');
    $stmt->bind_param('isss', $user_id, $title, $description, $image_filename);
    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        send_success(['id' => $new_id, 'image_url' => 'uploads/' . $image_filename]);
    } else {
        send_error('db insert failed: ' . mysqli_error($conn), 500);
    }

} elseif ($method === 'DELETE') {
    // delete portfolio item by id (only owner)
    parse_str(file_get_contents('php://input'), $input);
    $id = isset($input['id']) ? intval($input['id']) : 0;
    if ($id <= 0) send_error('id required', 400);

    $user = require_auth();
    $user_id = $user['id'];

    // verify ownership
    $stmt = $conn->prepare('SELECT image FROM user_portfolios WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if (!$row) send_error('not found or not owner', 404);

    // delete file
    $file = UPLOAD_PATH . $row['image'];
    if (is_file($file)) @unlink($file);

    $del = $conn->prepare('DELETE FROM user_portfolios WHERE id = ?');
    $del->bind_param('i', $id);
    if ($del->execute()) send_success([], 'deleted');
    else send_error('delete failed', 500);

} else {
    send_error('method not allowed', 405);
}


?>
