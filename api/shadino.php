<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Returns proposals relevant to the logged-in user so the chat UI can show them under the "شادینو" DM.
// - If the current user is an ad owner, returns proposals submitted for that user's ads.
// - If the current user is a business, returns proposals submitted by that business.

$user = require_auth();
global $conn;

// Response container
$proposals = [];

// allow POST action=accept to accept a proposal (ad owner only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'accept') {
    $user = require_auth();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['proposal_id'])) {
        send_error('proposal_id is required', 400);
    }

    $proposal_id = intval($input['proposal_id']);
    if ($proposal_id <= 0) send_error('proposal_id invalid', 400);

    // load proposal and ad owner
    $stmt = mysqli_prepare($conn, "SELECT p.*, a.user_id as ad_owner_id, a.id as ad_id FROM proposals p JOIN ads a ON p.ad_id = a.id WHERE p.id = ?");
    mysqli_stmt_bind_param($stmt, "i", $proposal_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $proposal = mysqli_fetch_assoc($result);

    if (!$proposal) send_error('proposal not found', 404);
    if ($proposal['ad_owner_id'] != $user['id']) send_error('not authorized', 403);

    // accept proposal: set status accepted, close ad, reject others (reuse logic from proposals.php)
    mysqli_begin_transaction($conn);
    try {
        $stmt = mysqli_prepare($conn, "UPDATE proposals SET status = 'accepted' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $proposal_id);
        mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare($conn, "UPDATE ads SET status = 'closed' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $proposal['ad_id']);
        mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare($conn, "UPDATE proposals SET status = 'rejected' WHERE ad_id = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, "ii", $proposal['ad_id'], $proposal_id);
        mysqli_stmt_execute($stmt);

        // notify business
        $message = 'پیشنهاد شما پذیرفته شد';
        $stmt = mysqli_prepare($conn, "INSERT INTO notifications (user_id, title, message, type, related_id) VALUES (?, 'وضعیت پیشنهاد', ?, 'proposal', ?)");
        mysqli_stmt_bind_param($stmt, "isi", $proposal['business_id'], $message, $proposal_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($conn);
        send_success([], 'proposal accepted');
    } catch (Exception $e) {
        mysqli_rollback($conn);
        send_error('failed to accept proposal', 500);
    }
}

$action = $_GET['action'] ?? null;
$current_user = $user;

// Handle new proposal submissions
if ($action === 'propose') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['ad_id'])) {
        send_error('ad_id is required', 400);
    }

    $ad_id = intval($input['ad_id']);
    if ($ad_id <= 0) send_error('ad_id invalid', 400);

    // Insert the new proposal into the database
    $stmt = mysqli_prepare($conn, "INSERT INTO proposals (ad_id, business_id, status) VALUES (?, ?, 'pending')");
    mysqli_stmt_bind_param($stmt, "ii", $ad_id, $current_user['id']);
    mysqli_stmt_execute($stmt);

    $proposal_id = mysqli_insert_id($conn);

    // Fetch the newly created proposal for the response
    $stmt = mysqli_prepare($conn, "SELECT p.*, a.title as ad_title, u.name as ad_owner_name, u.id as ad_owner_id
        FROM proposals p
        JOIN ads a ON p.ad_id = a.id
        JOIN users u ON a.user_id = u.id
        WHERE p.id = ?");
    mysqli_stmt_bind_param($stmt, "i", $proposal_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $proposal = mysqli_fetch_assoc($result);

    if (!$proposal) send_error('proposal not found', 404);

    // Start a chat between the proposer and the ad owner
    $ad_owner_id = $proposal['ad_owner_id'];
    $proposer_id = $current_user['id'];

    // Ensure the smaller ID is always user1_id for consistency
    if ($ad_owner_id > $proposer_id) {
        [$ad_owner_id, $proposer_id] = [$proposer_id, $ad_owner_id];
    }

    // Check if a conversation already exists
    $stmt = $pdo->prepare("SELECT id FROM conversations WHERE user1_id = ? AND user2_id = ?");
    $stmt->execute([$ad_owner_id, $proposer_id]);
    $conversation = $stmt->fetch();

    if (!$conversation) {
        // Create a new conversation
        $stmt = $pdo->prepare("INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
        $stmt->execute([$ad_owner_id, $proposer_id]);
    }

    send_success(['proposal' => $proposal], 'proposal submitted');
}

if ($user['role'] === 'business') {
    $stmt = mysqli_prepare($conn, "
        SELECT p.*, a.title as ad_title, a.id as ad_id, u.name as ad_owner_name, u.id as ad_owner_id
        FROM proposals p
        JOIN ads a ON p.ad_id = a.id
        JOIN users u ON a.user_id = u.id
        WHERE p.business_id = ?
        ORDER BY p.created_at DESC
    ");
    mysqli_stmt_bind_param($stmt, "i", $user['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['extra_services'] = json_decode($row['extra_services'], true) ?: [];
        $proposals[] = $row;
    }
} else {
    // ad owner or regular user: return proposals for ads owned by this user
    $stmt = mysqli_prepare($conn, "
        SELECT p.*, a.title as ad_title, a.id as ad_id, u.name as business_name, u.id as business_id, u.avatar as business_avatar
        FROM proposals p
        JOIN ads a ON p.ad_id = a.id
        JOIN users u ON p.business_id = u.id
        WHERE a.user_id = ?
        ORDER BY p.created_at DESC
    ");
    mysqli_stmt_bind_param($stmt, "i", $user['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['extra_services'] = json_decode($row['extra_services'], true) ?: [];
        $proposals[] = $row;
    }
}

// Attach current user info for frontend logic
$payload = [
    'current_user' => [
        'id' => $user['id'],
        'role' => $user['role']
    ],
    'proposals' => $proposals
];

send_success($payload);

?>
