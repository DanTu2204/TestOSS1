<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Quần Áo Thể Thao</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <a href="index.php">
                        <h1>🏃 SPORTS STORE</h1>
                    </a>
                </div>
                <div class="header-actions">
                    <?php if ($currentUser): ?>
                        <a href="profile.php" class="btn-user">Xin chào, <?php echo htmlspecialchars($currentUser['fullname'] ?: $currentUser['username']); ?></a>
                        <?php if (isAdmin()): ?>
                            <a href="admin/index.php" class="btn-admin">Admin</a>
                        <?php endif; ?>
                        <a href="logout.php" class="btn-logout">Đăng xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Đăng nhập</a>
                        <a href="register.php" class="btn-register">Đăng ký</a>
                    <?php endif; ?>
                    <a href="cart.php" class="btn-cart">
                        🛒 Giỏ hàng 
                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="products.php?category=ao-the-thao">Áo Thể Thao</a></li>
                    <li><a href="products.php?category=quan-the-thao">Quần Thể Thao</a></li>
                    <li><a href="products.php?category=giay-the-thao">Giày Thể Thao</a></li>
                    <li><a href="products.php?category=phu-kien">Phụ Kiện</a></li>
                    <li><a href="lab.php">Lab</a></li>
                    <li><a href="contact.php">Liên hệ</a></li>
                </ul>
            </nav>
        </div>
    </header>

