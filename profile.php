<?php
$pageTitle = 'Tài khoản';
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'config/session.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$currentUser = getCurrentUser();
$conn = getDBConnection();

// Lấy thông tin user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $currentUser['id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Lấy đơn hàng
$orders = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $currentUser['id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$conn->close();
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Tài khoản của tôi</h1>
        <div class="profile-page">
            <div class="profile-sidebar">
                <h3>Thông tin tài khoản</h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($user['fullname'] ?: 'Chưa cập nhật'); ?></p>
                <p><strong>SĐT:</strong> <?php echo htmlspecialchars($user['phone'] ?: 'Chưa cập nhật'); ?></p>
            </div>
            <div class="profile-content">
                <h2>Đơn hàng của tôi</h2>
                <?php if (empty($orders)): ?>
                    <p>Bạn chưa có đơn hàng nào.</p>
                <?php else: ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo number_format($order['total_amount']); ?>₫</td>
                                    <td>
                                        <?php
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipped' => 'Đã giao hàng',
                                            'delivered' => 'Đã nhận',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        echo $statusLabels[$order['status']] ?? $order['status'];
                                        ?>
                                    </td>
                                    <td>
                                        <a href="order-detail.php?id=<?php echo $order['id']; ?>">Xem chi tiết</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

