<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    send_error('Unauthorized', 401);
}
$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['conversation_id']) || !isset($data['message'])) {
    send_error('Missing required fields');
}
$conversation_id = intval($data['conversation_id']);
$message = trim($data['message']);
$stmt = mysqli_prepare($conn, "SELECT id FROM conversations WHERE id = ? AND (user1_id = ? OR user2_id = ?)");
mysqli_stmt_bind_param($stmt, "iii", $conversation_id, $user_id, $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) === 0) {
    send_error('Unauthorized access to conversation', 403);
}
$stmt = mysqli_prepare($conn, "INSERT INTO messages (conversation_id, sender_id, message) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "iis", $conversation_id, $user_id, $message);
if (mysqli_stmt_execute($stmt)) {
    mysqli_query($conn, "UPDATE conversations SET updated_at = CURRENT_TIMESTAMP WHERE id = $conversation_id");
    send_success(['message_id' => mysqli_insert_id($conn)]);
} else {
    send_error('Failed to send message');
}
