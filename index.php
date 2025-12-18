<?php
$pageTitle = 'Trang chủ';
require_once 'includes/header.php';
require_once 'config/database.php';

$conn = getDBConnection();

// Lấy sản phẩm nổi bật (có sale_price)
$featuredProducts = [];
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active' AND p.sale_price IS NOT NULL 
        ORDER BY p.created_at DESC 
        LIMIT 8";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredProducts[] = $row;
    }
}
?>
<h1> Huỳnh Phan Đan Tú-chiều thứ 5</h1>
<?php
// Lấy sản phẩm mới nhất
$newProducts = [];
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active' 
        ORDER BY p.created_at DESC 
        LIMIT 8";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $newProducts[] = $row;
    }
}

$conn->close();
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Khám phá Bộ Sưu Tập Quần Áo Thể Thao</h2>
                <p>Chất lượng cao - Giá tốt - Giao hàng nhanh</p>
                <a href="products.php" class="btn btn-primary">Xem sản phẩm</a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Sản phẩm đang giảm giá</h2>
            <div class="products-grid">
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="product-card">
                            <?php if ($product['sale_price']): ?>
                                <span class="sale-badge">-<?php echo round((1 - $product['sale_price']/$product['price']) * 100); ?>%</span>
                            <?php endif; ?>
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($product['image'] ?: 'assets/images/placeholder.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">
                                    <a href="product-detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h3>
                                <div class="product-price">
                                    <?php if ($product['sale_price']): ?>
                                        <span class="old-price"><?php echo number_format($product['price']); ?>₫</span>
                                        <span class="current-price"><?php echo number_format($product['sale_price']); ?>₫</span>
                                    <?php else: ?>
                                        <span class="current-price"><?php echo number_format($product['price']); ?>₫</span>
                                    <?php endif; ?>
                                </div>
                                <a href="product-detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>" 
                                   class="btn btn-view">Xem chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-products">Chưa có sản phẩm nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- New Products -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Sản phẩm mới</h2>
            <div class="products-grid">
                <?php if (!empty($newProducts)): ?>
                    <?php foreach ($newProducts as $product): ?>
                        <div class="product-card">
                            <?php if ($product['sale_price']): ?>
                                <span class="sale-badge">-<?php echo round((1 - $product['sale_price']/$product['price']) * 100); ?>%</span>
                            <?php endif; ?>
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($product['image'] ?: 'assets/images/placeholder.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">
                                    <a href="product-detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h3>
                                <div class="product-price">
                                    <?php if ($product['sale_price']): ?>
                                        <span class="old-price"><?php echo number_format($product['price']); ?>₫</span>
                                        <span class="current-price"><?php echo number_format($product['sale_price']); ?>₫</span>
                                    <?php else: ?>
                                        <span class="current-price"><?php echo number_format($product['price']); ?>₫</span>
                                    <?php endif; ?>
                                </div>
                                <a href="product-detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>" 
                                   class="btn btn-view">Xem chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-products">Chưa có sản phẩm nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>

