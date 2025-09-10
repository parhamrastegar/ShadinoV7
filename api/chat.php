<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    if ($method === 'POST' && $action === 'close') {
        // Close a chat
        $conversation_id = $_POST['conversation_id'] ?? null;
        if (!$conversation_id) {
            throw new Exception('Conversation ID is required');
        }

        $stmt = $pdo->prepare("UPDATE conversations SET closed = 1 WHERE id = ?");
        $stmt->execute([$conversation_id]);

        echo json_encode(['success' => true, 'message' => 'Chat closed successfully']);
    } elseif ($method === 'GET' && $action === 'list') {
        // List all chats
        $user_id = $_GET['user_id'] ?? null;
        if (!$user_id) {
            throw new Exception('User ID is required');
        }

        $stmt = $pdo->prepare(
            "SELECT c.id, c.closed, 
                    CASE WHEN c.user1_id = ? THEN u2.username ELSE u1.username END AS partner_name
             FROM conversations c
             JOIN users u1 ON c.user1_id = u1.id
             JOIN users u2 ON c.user2_id = u2.id
             WHERE c.user1_id = ? OR c.user2_id = ?"
        );
        $stmt->execute([$user_id, $user_id, $user_id]);
        $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'chats' => $chats]);
    } else {
        throw new Exception('Invalid request method or action');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
