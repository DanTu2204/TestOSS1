<?php
$pageTitle = 'Admin - Chi tiết đơn';
require_once __DIR__ . '/_auth.php';

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT o.*, u.username, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    $conn->close();
    header('Location: index.php');
    exit;
}

$orderItems = [];
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->bind_param('i', $orderId);
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
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Đơn #<?php echo $order['id']; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main class="main-content">
        <div class="container">
            <div class="order-detail-page">
                <div class="order-info">
                    <h2>Thông tin đơn hàng #<?php echo $order['id']; ?></h2>
                    <p><strong>Khách:</strong> <?php echo htmlspecialchars($order['shipping_name']); ?> (<?php echo htmlspecialchars($order['email'] ?? ''); ?>)</p>
                    <p><strong>SĐT:</strong> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                    <p><strong>Địa chỉ:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                    <p><strong>Trạng thái:</strong> <?php echo $statusLabels[$order['status']] ?? $order['status']; ?></p>
                    <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_amount']); ?>₫</p>
                    <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
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
                                        <img src="<?php echo htmlspecialchars($item['image'] ?: '../assets/images/placeholder.jpg'); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             onerror="this.src='../assets/images/placeholder.jpg'">
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
                <a href="index.php" class="btn btn-secondary">← Quay lại danh sách</a>
            </div>
        </div>
    </main>
</body>
</html>
