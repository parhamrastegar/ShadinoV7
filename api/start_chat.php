<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    send_error('Unauthorized', 401);
}
$user_id = $_SESSION['user_id'];
if (!isset($_GET['with_user'])) {
    send_error('Missing with_user parameter');
}
$other_id = intval($_GET['with_user']);
if ($other_id === $user_id) {
    send_error('Cannot start chat with yourself');
}
$stmt = mysqli_prepare($conn, "SELECT id FROM conversations WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
mysqli_stmt_bind_param($stmt, "iiii", $user_id, $other_id, $other_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    send_success(['conversation_id' => $row['id']]);
} else {
    $stmt = mysqli_prepare($conn, "INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $other_id);
    if (mysqli_stmt_execute($stmt)) {
        send_success(['conversation_id' => mysqli_insert_id($conn)]);
    } else {
        send_error('Failed to create conversation');
    }
}
