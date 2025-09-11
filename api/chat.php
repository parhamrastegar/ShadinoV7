<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    send_error('Unauthorized', 401);
}

$user_id = $_SESSION['user_id'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['conversation_id'])) {
            // Get messages for a specific conversation
            getMessages($_GET['conversation_id'], $user_id);
        } elseif (isset($_GET['with_user'])) {
            // Get or create conversation with specific user
            getOrCreateConversation($user_id, $_GET['with_user']);
        } else {
            // Get all conversations for current user
            getConversations($user_id);
        }
        break;

    case 'POST':
        // Send a new message
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['conversation_id']) || !isset($data['message'])) {
            send_error('Missing required fields');
        }
        sendMessage($data['conversation_id'], $user_id, $data['message']);
        break;

    default:
        send_error('Method not allowed', 405);
}

function getMessages($conversation_id, $user_id) {
    global $conn;
    
    // Verify user is part of conversation
    $stmt = mysqli_prepare($conn, 
        "SELECT id FROM conversations 
        WHERE id = ? AND (user1_id = ? OR user2_id = ?)");
    mysqli_stmt_bind_param($stmt, "iii", $conversation_id, $user_id, $user_id);
    mysqli_stmt_execute($stmt);
    
    if (!mysqli_stmt_fetch($stmt)) {
        send_error('Unauthorized access to conversation', 403);
    }

    // Get messages
    $stmt = mysqli_prepare($conn, 
        "SELECT m.*, u.username, u.profile_image 
        FROM messages m 
        JOIN users u ON m.sender_id = u.id 
        WHERE m.conversation_id = ? 
        ORDER BY m.created_at ASC");
    mysqli_stmt_bind_param($stmt, "i", $conversation_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }

    // Mark messages as read
    mysqli_query($conn, 
        "UPDATE messages 
        SET is_read = TRUE 
        WHERE conversation_id = ? 
        AND sender_id != ? 
        AND is_read = FALSE");

    send_success(['messages' => $messages]);
}

function getConversations($user_id) {
    global $conn;
    
    $stmt = mysqli_prepare($conn, 
        "SELECT 
            c.id,
            c.created_at,
            IF(c.user1_id = ?, u2.id, u1.id) as other_user_id,
            IF(c.user1_id = ?, u2.username, u1.username) as other_username,
            IF(c.user1_id = ?, u2.profile_image, u1.profile_image) as other_profile_image,
            (SELECT message FROM messages 
             WHERE conversation_id = c.id 
             ORDER BY created_at DESC LIMIT 1) as last_message,
            (SELECT COUNT(*) FROM messages 
             WHERE conversation_id = c.id 
             AND sender_id != ? 
             AND is_read = FALSE) as unread_count
        FROM conversations c
        JOIN users u1 ON c.user1_id = u1.id
        JOIN users u2 ON c.user2_id = u2.id
        WHERE c.user1_id = ? OR c.user2_id = ?
        ORDER BY c.updated_at DESC");
    
    mysqli_stmt_bind_param($stmt, "iiiiii", 
        $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $conversations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $conversations[] = $row;
    }

    send_success(['conversations' => $conversations]);
}

function getOrCreateConversation($user1_id, $user2_id) {
    global $conn;
    
    // Check if conversation exists
    $stmt = mysqli_prepare($conn, 
        "SELECT id FROM conversations 
        WHERE (user1_id = ? AND user2_id = ?) 
        OR (user1_id = ? AND user2_id = ?)");
    mysqli_stmt_bind_param($stmt, "iiii", 
        $user1_id, $user2_id, $user2_id, $user1_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        send_success(['conversation_id' => $row['id']]);
        return;
    }

    // Create new conversation
    $stmt = mysqli_prepare($conn, 
        "INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $user1_id, $user2_id);
    
    if (mysqli_stmt_execute($stmt)) {
        send_success(['conversation_id' => mysqli_insert_id($conn)]);
    } else {
        send_error('Failed to create conversation');
    }
}

function sendMessage($conversation_id, $sender_id, $message) {
    global $conn;
    
    // Verify user is part of conversation
    $stmt = mysqli_prepare($conn, 
        "SELECT id FROM conversations 
        WHERE id = ? AND (user1_id = ? OR user2_id = ?)");
    mysqli_stmt_bind_param($stmt, "iii", $conversation_id, $sender_id, $sender_id);
    mysqli_stmt_execute($stmt);
    
    if (!mysqli_stmt_fetch($stmt)) {
        send_error('Unauthorized access to conversation', 403);
    }

    // Insert message
    $stmt = mysqli_prepare($conn, 
        "INSERT INTO messages (conversation_id, sender_id, message) 
        VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iis", $conversation_id, $sender_id, $message);
    
    if (mysqli_stmt_execute($stmt)) {
        // Update conversation timestamp
        mysqli_query($conn, 
            "UPDATE conversations 
            SET updated_at = CURRENT_TIMESTAMP 
            WHERE id = $conversation_id");
        
        send_success(['message_id' => mysqli_insert_id($conn)]);
    } else {
        send_error('Failed to send message');
    }
}
