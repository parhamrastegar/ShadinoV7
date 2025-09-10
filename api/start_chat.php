<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user1_id = $_POST['user1_id'] ?? null;
    $user2_id = $_POST['user2_id'] ?? null;

    if (!$user1_id || !$user2_id) {
        die('Invalid input');
    }

    // Ensure the smaller ID is always user1_id for consistency
    if ($user1_id > $user2_id) {
        [$user1_id, $user2_id] = [$user2_id, $user1_id];
    }

    // Check if a conversation already exists
    $stmt = $pdo->prepare("SELECT id FROM conversations WHERE user1_id = ? AND user2_id = ?");
    $stmt->execute([$user1_id, $user2_id]);
    $conversation = $stmt->fetch();

    if (!$conversation) {
        // Create a new conversation
        $stmt = $pdo->prepare("INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
        $stmt->execute([$user1_id, $user2_id]);
        $conversation_id = $pdo->lastInsertId();
    } else {
        $conversation_id = $conversation['id'];
    }

    // Redirect to the chat page
    header("Location: /chat.php?conversation_id=$conversation_id");
    exit;
}

die('Invalid request method');
