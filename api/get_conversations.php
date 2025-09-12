<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    send_error('Unauthorized', 401);
}
$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT c.id, c.created_at, IF(c.user1_id = ?, u2.id, u1.id) as other_user_id, IF(c.user1_id = ?, u2.username, u1.username) as other_username, IF(c.user1_id = ?, u2.profile_image, u1.profile_image) as other_profile_image, (SELECT message FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message, (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND sender_id != ? AND is_read = FALSE) as unread_count FROM conversations c JOIN users u1 ON c.user1_id = u1.id JOIN users u2 ON c.user2_id = u2.id WHERE c.user1_id = ? OR c.user2_id = ? ORDER BY c.updated_at DESC");
mysqli_stmt_bind_param($stmt, "iiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$conversations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $conversations[] = $row;
}
send_success(['conversations' => $conversations]);
