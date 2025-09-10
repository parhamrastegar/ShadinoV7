<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
    echo json_encode(['success' => false, 'users' => []]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, mobile, avatar FROM users WHERE name LIKE CONCAT('%', ?, '%') OR mobile LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param('ss', $q, $q);
$stmt->execute();
$result = $stmt->get_result();
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
echo json_encode(['success' => true, 'users' => $users]);
