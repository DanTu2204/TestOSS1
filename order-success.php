<?php
$pageTitle = 'Đặt hàng thành công';
require_once 'includes/header.php';
require_once 'config/session.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
?>

<main class="main-content">
    <div class="container">
        <div class="success-page">
            <div class="success-icon">✓</div>
            <h1>Đặt hàng thành công!</h1>
            <p>Cảm ơn bạn đã mua sắm tại Sports Store.</p>
            <?php if ($orderId): ?>
                <p>Mã đơn hàng của bạn: <strong>#<?php echo $orderId; ?></strong></p>
            <?php endif; ?>
            <p>Chúng tôi sẽ xử lý đơn hàng và liên hệ với bạn sớm nhất có thể.</p>
            <div class="success-actions">
                <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                <a href="profile.php" class="btn btn-secondary">Xem đơn hàng</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

