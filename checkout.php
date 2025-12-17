<?php
$pageTitle = 'Thanh toán';
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'config/session.php';

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

$error = '';
$success = '';

// Lấy thông tin user
$currentUser = getCurrentUser();
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $currentUser['id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$conn->close();

// Lấy giỏ hàng
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

// Tính tổng tiền
$cartItems = [];
$total = 0;
$conn = getDBConnection();
foreach ($cart as $productId => $item) {
    $stmt = $conn->prepare("SELECT id, name, price, sale_price, stock FROM products WHERE id = ? AND status = 'active'");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        $finalPrice = $product['sale_price'] ? $product['sale_price'] : $product['price'];
        $quantity = min($item['quantity'], $product['stock']);
        $subtotal = $finalPrice * $quantity;
        $total += $subtotal;
        
        $cartItems[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $finalPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
$conn->close();
$shippingFee = 30000;
$grandTotal = $total + $shippingFee;

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shippingName = trim($_POST['shipping_name'] ?? '');
    $shippingPhone = trim($_POST['shipping_phone'] ?? '');
    $shippingAddress = trim($_POST['shipping_address'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    
    if (empty($shippingName) || empty($shippingPhone) || empty($shippingAddress)) {
        $error = 'Vui lòng điền đầy đủ thông tin giao hàng!';
    } else {
        $conn = getDBConnection();
        $conn->begin_transaction();
        
        try {
            // Tạo đơn hàng
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_name, shipping_phone, shipping_address, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("idssss", $currentUser['id'], $grandTotal, $shippingName, $shippingPhone, $shippingAddress, $notes);
            $stmt->execute();
            $orderId = $conn->insert_id;
            
            // Thêm chi tiết đơn hàng
            foreach ($cartItems as $item) {
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
                $stmt->execute();
                
                // Cập nhật số lượng tồn kho
                $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->bind_param("ii", $item['quantity'], $item['id']);
                $stmt->execute();
            }
            
            $conn->commit();
            
            // Xóa giỏ hàng
            $_SESSION['cart'] = [];
            $_SESSION['success'] = 'Đặt hàng thành công! Cảm ơn bạn đã mua sắm.';
            header('Location: order-success.php?order_id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!';
        }
        
        $conn->close();
    }
}
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Thanh toán</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="checkout-page">
            <div class="checkout-form">
                <h2>Thông tin giao hàng</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Họ và tên người nhận: *</label>
                        <input type="text" name="shipping_name" 
                               value="<?php echo htmlspecialchars($_POST['shipping_name'] ?? $user['fullname'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Số điện thoại: *</label>
                        <input type="tel" name="shipping_phone" 
                               value="<?php echo htmlspecialchars($_POST['shipping_phone'] ?? $user['phone'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Địa chỉ giao hàng: *</label>
                        <textarea name="shipping_address" rows="3" required><?php echo htmlspecialchars($_POST['shipping_address'] ?? $user['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Ghi chú:</label>
                        <textarea name="notes" rows="3"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Xác nhận đặt hàng</button>
                </form>
            </div>
            
            <div class="checkout-summary">
                <h2>Đơn hàng của bạn</h2>
                <div class="order-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                            <span><?php echo $item['quantity']; ?> x <?php echo number_format($item['price']); ?>₫</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($total); ?>₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span><?php echo number_format($shippingFee); ?>₫</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($grandTotal); ?>₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

