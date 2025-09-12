<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    send_error('Unauthorized', 401);
}
$user_id = $_SESSION['user_id'];
if (!isset($_GET['conversation_id'])) {
    send_error('Missing conversation_id');
}
$conversation_id = intval($_GET['conversation_id']);
$stmt = mysqli_prepare($conn, "SELECT id FROM conversations WHERE id = ? AND (user1_id = ? OR user2_id = ?)");
mysqli_stmt_bind_param($stmt, "iii", $conversation_id, $user_id, $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) === 0) {
    send_error('Unauthorized access to conversation', 403);
}
$stmt = mysqli_prepare($conn, "SELECT m.*, u.username, u.profile_image FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.conversation_id = ? ORDER BY m.created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $conversation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}
// Mark messages as read
mysqli_query($conn, "UPDATE messages SET is_read = TRUE WHERE conversation_id = $conversation_id AND sender_id != $user_id AND is_read = FALSE");
send_success(['messages' => $messages]);
