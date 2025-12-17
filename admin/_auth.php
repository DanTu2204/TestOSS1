<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';

// Bảo vệ trang quản trị
if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}
?>
