<?php
$pageTitle = 'Chi tiết đơn hàng';
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'config/session.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$currentUser = getCurrentUser();

$conn = getDBConnection();

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $orderId, $currentUser['id']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header('Location: profile.php');
    exit;
}

// Lấy chi tiết đơn hàng
$orderItems = [];
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orderItems[] = $row;
}

$conn->close();

$statusLabels = [
    'pending' => 'Chờ xử lý',
    'processing' => 'Đang xử lý',
    'shipped' => 'Đã giao hàng',
    'delivered' => 'Đã nhận',
    'cancelled' => 'Đã hủy'
];
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Chi tiết đơn hàng #<?php echo $order['id']; ?></h1>
        
        <div class="order-detail-page">
            <div class="order-info">
                <h2>Thông tin đơn hàng</h2>
                <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Trạng thái:</strong> <?php echo $statusLabels[$order['status']] ?? $order['status']; ?></p>
                <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_amount']); ?>₫</p>
                
                <h3>Thông tin giao hàng</h3>
                <p><strong>Người nhận:</strong> <?php echo htmlspecialchars($order['shipping_name']); ?></p>
                <p><strong>SĐT:</strong> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                <?php if ($order['notes']): ?>
                    <p><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="order-items">
                <h2>Sản phẩm</h2>
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($item['image'] ?: 'assets/images/placeholder.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                         onerror="this.src='assets/images/placeholder.jpg'">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </td>
                                <td><?php echo number_format($item['price']); ?>₫</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'] * $item['quantity']); ?>₫</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="order-actions">
            <a href="profile.php" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

