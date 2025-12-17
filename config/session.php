<?php
// Bắt đầu session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Hàm kiểm tra đăng nhập
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Hàm lấy thông tin user hiện tại
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'fullname' => $_SESSION['fullname'] ?? '',
            'role' => $_SESSION['role'] ?? 'customer'
        ];
    }
    return null;
}

// Hàm kiểm tra admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Hàm đăng xuất
function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

