<?php
$pageTitle = 'Giỏ hàng';
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'config/session.php';

// Hiển thị thông báo
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

$cart = $_SESSION['cart'] ?? [];
$cartItems = [];
$total = 0;

if (!empty($cart)) {
    $conn = getDBConnection();
    foreach ($cart as $productId => $item) {
        // Lấy thông tin cập nhật từ database
        $stmt = $conn->prepare("SELECT id, name, price, sale_price, image, stock FROM products WHERE id = ? AND status = 'active'");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if ($product) {
            $finalPrice = $product['sale_price'] ? $product['sale_price'] : $product['price'];
            $quantity = min($item['quantity'], $product['stock']); // Đảm bảo không vượt quá stock
            $subtotal = $finalPrice * $quantity;
            $total += $subtotal;
            
            $cartItems[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $finalPrice,
                'quantity' => $quantity,
                'image' => $product['image'],
                'stock' => $product['stock'],
                'subtotal' => $subtotal
            ];
        }
    }
    $conn->close();
}
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Giỏ hàng của bạn</h1>
        
        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <p>Giỏ hàng của bạn đang trống.</p>
                <a href="products.php" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="cart-page">
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td class="product-cell">
                                        <img src="<?php echo htmlspecialchars($item['image'] ?: 'assets/images/placeholder.jpg'); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             onerror="this.src='assets/images/placeholder.jpg'">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </td>
                                    <td class="price-cell">
                                        <?php echo number_format($item['price']); ?>₫
                                    </td>
                                    <td class="quantity-cell">
                                        <form action="cart-action.php" method="POST" class="update-quantity-form">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                                   min="1" max="<?php echo $item['stock']; ?>" required>
                                            <button type="submit" class="btn-small">Cập nhật</button>
                                        </form>
                                    </td>
                                    <td class="subtotal-cell">
                                        <?php echo number_format($item['subtotal']); ?>₫
                                    </td>
                                    <td class="action-cell">
                                        <a href="cart-action.php?action=remove&product_id=<?php echo $item['id']; ?>" 
                                           class="btn-remove" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart-actions">
                        <a href="cart-action.php?action=clear" class="btn btn-secondary" 
                           onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?');">
                            Xóa toàn bộ
                        </a>
                        <a href="products.php" class="btn btn-secondary">Tiếp tục mua sắm</a>
                    </div>
                </div>
                
                <div class="cart-summary">
                    <h3>Tổng kết đơn hàng</h3>
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($total); ?>₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>30,000₫</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($total + 30000); ?>₫</span>
                    </div>
                    <?php if (isLoggedIn()): ?>
                        <a href="checkout.php" class="btn btn-primary btn-checkout">Thanh toán</a>
                    <?php else: ?>
                        <p class="login-prompt">Vui lòng <a href="login.php">đăng nhập</a> để thanh toán</p>
                        <a href="login.php" class="btn btn-primary">Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

