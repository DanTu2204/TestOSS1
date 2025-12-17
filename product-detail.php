<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'config/session.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (!$slug) {
    header('Location: index.php');
    exit;
}

$conn = getDBConnection();

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.slug = ? AND p.status = 'active'");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header('Location: index.php');
    exit;
}

$pageTitle = $product['name'];

// Lấy sản phẩm liên quan
$relatedProducts = [];
$relatedSql = "SELECT p.*, c.name as category_name 
               FROM products p 
               LEFT JOIN categories c ON p.category_id = c.id 
               WHERE p.category_id = ? AND p.id != ? AND p.status = 'active' 
               LIMIT 4";
$relatedStmt = $conn->prepare($relatedSql);
$relatedStmt->bind_param("ii", $product['category_id'], $product['id']);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();
while ($row = $relatedResult->fetch_assoc()) {
    $relatedProducts[] = $row;
}

$conn->close();
?>

<main class="main-content">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Trang chủ</a> / 
            <a href="products.php?category=<?php echo htmlspecialchars($product['category_slug']); ?>">
                <?php echo htmlspecialchars($product['category_name']); ?>
            </a> / 
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <!-- Product Detail -->
        <div class="product-detail">
            <div class="product-detail-image">
                <img src="<?php echo htmlspecialchars($product['image'] ?: 'assets/images/placeholder.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                     onerror="this.src='assets/images/placeholder.jpg'">
            </div>
            <div class="product-detail-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-price">
                    <?php if ($product['sale_price']): ?>
                        <span class="old-price"><?php echo number_format($product['price']); ?>₫</span>
                        <span class="current-price"><?php echo number_format($product['sale_price']); ?>₫</span>
                        <span class="discount-badge">Giảm <?php echo round((1 - $product['sale_price']/$product['price']) * 100); ?>%</span>
                    <?php else: ?>
                        <span class="current-price"><?php echo number_format($product['price']); ?>₫</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <h3>Mô tả sản phẩm</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="product-stock">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="in-stock">Còn hàng (<?php echo $product['stock']; ?> sản phẩm)</span>
                    <?php else: ?>
                        <span class="out-of-stock">Hết hàng</span>
                    <?php endif; ?>
                </div>

                <form action="cart-action.php" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="quantity-selector">
                        <label>Số lượng:</label>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                    </div>
                    <?php if ($product['stock'] > 0): ?>
                        <button type="submit" class="btn btn-primary btn-add-cart">Thêm vào giỏ hàng</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-disabled" disabled>Hết hàng</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <section class="related-products">
                <h2 class="section-title">Sản phẩm liên quan</h2>
                <div class="products-grid">
                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="product-card">
                            <?php if ($related['sale_price']): ?>
                                <span class="sale-badge">-<?php echo round((1 - $related['sale_price']/$related['price']) * 100); ?>%</span>
                            <?php endif; ?>
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($related['image'] ?: 'assets/images/placeholder.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($related['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">
                                    <a href="product-detail.php?slug=<?php echo htmlspecialchars($related['slug']); ?>">
                                        <?php echo htmlspecialchars($related['name']); ?>
                                    </a>
                                </h3>
                                <div class="product-price">
                                    <?php if ($related['sale_price']): ?>
                                        <span class="old-price"><?php echo number_format($related['price']); ?>₫</span>
                                        <span class="current-price"><?php echo number_format($related['sale_price']); ?>₫</span>
                                    <?php else: ?>
                                        <span class="current-price"><?php echo number_format($related['price']); ?>₫</span>
                                    <?php endif; ?>
                                </div>
                                <a href="product-detail.php?slug=<?php echo htmlspecialchars($related['slug']); ?>" 
                                   class="btn btn-view">Xem chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

