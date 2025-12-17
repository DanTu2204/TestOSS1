<?php
$pageTitle = 'Admin - Đơn hàng';
require_once __DIR__ . '/_auth.php';

// Cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $allowed = ['pending','processing','shipped','delivered','cancelled'];
    if (in_array($status, $allowed, true)) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $orderId);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Đã cập nhật trạng thái đơn #' . $orderId;
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại.';
        }
        $conn->close();
        header('Location: index.php');
        exit;
    }
}

// Lấy danh sách đơn hàng
$conn = getDBConnection();
$sql = "SELECT o.*, u.username, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
$result = $conn->query($sql);
$orders = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
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
    <title>Admin - Đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout { padding: 30px 0; }
        .admin-header { display:flex; justify-content: space-between; align-items:center; margin-bottom:20px; }
        .orders-table th { background:#667eea; color:#fff; }
        .status-select { padding:6px; }
        .admin-actions { display:flex; gap:10px; align-items:center; }
    </style>
</head>
<body>
    <main class="main-content admin-layout">
        <div class="container">
            <div class="admin-header">
                <h1>Quản lý đơn hàng</h1>
                <div class="admin-actions">
                    <span>Xin chào, <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?></span>
                    <a class="btn btn-secondary" href="../index.php">Về trang chủ</a>
                    <a class="btn btn-secondary" href="logout.php">Đăng xuất</a>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="profile-content">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Khách</th>
                            <th>Email</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="7">Chưa có đơn hàng.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['shipping_name'] ?: ($order['username'] ?? 'N/A')); ?></td>
                                    <td><?php echo htmlspecialchars($order['email'] ?? ''); ?></td>
                                    <td><?php echo number_format($order['total_amount']); ?>₫</td>
                                    <td>
                                        <form method="POST" action="" style="display:flex; gap:5px; align-items:center;">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <select name="status" class="status-select">
                                                <?php foreach ($statusLabels as $key => $label): ?>
                                                    <option value="<?php echo $key; ?>" <?php echo $order['status'] === $key ? 'selected' : ''; ?>>
                                                        <?php echo $label; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-small">Lưu</button>
                                        </form>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td><a href="order-detail.php?id=<?php echo $order['id']; ?>">Xem</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
