<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('متد درخواست نامعتبر است', 405);
}

// Destroy session
session_destroy();

send_success([], 'خروج با موفقیت انجام شد');
?>
