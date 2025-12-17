<?php
$pageTitle = 'Sản phẩm';
require_once 'includes/header.php';
require_once 'config/database.php';

$conn = getDBConnection();

// Lấy category từ URL
$categorySlug = isset($_GET['category']) ? $_GET['category'] : '';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Lấy danh mục
$categories = [];
$sql = "SELECT * FROM categories ORDER BY name";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Xây dựng query
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active'";

$params = [];
if ($categorySlug) {
    $sql .= " AND c.slug = ?";
    $params[] = $categorySlug;
}

if ($searchQuery) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $searchTerm = "%$searchQuery%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Đếm tổng số sản phẩm
$countSql = $sql;
$stmt = $conn->prepare($countSql);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalProducts = $stmt->get_result()->num_rows;
$totalPages = ceil($totalProducts / $perPage);

// Lấy sản phẩm với phân trang
$sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $types = str_repeat('s', count($params) - 2) . 'ii';
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$conn->close();
?>

<main class="main-content">
    <div class="container">
        <div class="products-page">
            <!-- Sidebar danh mục -->
            <aside class="products-sidebar">
                <h3>Danh mục</h3>
                <ul class="category-list">
                    <li><a href="products.php" class="<?php echo !$categorySlug ? 'active' : ''; ?>">Tất cả</a></li>
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="products.php?category=<?php echo htmlspecialchars($cat['slug']); ?>" 
                               class="<?php echo $categorySlug === $cat['slug'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- Danh sách sản phẩm -->
            <div class="products-content">
                <!-- Tìm kiếm -->
                <div class="products-header">
                    <form method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." 
                               value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <?php if ($categorySlug): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($categorySlug); ?>">
                        <?php endif; ?>
                        <button type="submit">Tìm kiếm</button>
                    </form>
                </div>

                <!-- Kết quả -->
                <div class="products-info">
                    <p>Tìm thấy <?php echo $totalProducts; ?> sản phẩm</p>
                </div>

                <!-- Grid sản phẩm -->
                <div class="products-grid">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
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
                        <p class="no-products">Không tìm thấy sản phẩm nào.</p>
                    <?php endif; ?>
                </div>

                <!-- Phân trang -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo $categorySlug ? '&category=' . htmlspecialchars($categorySlug) : ''; ?><?php echo $searchQuery ? '&search=' . htmlspecialchars($searchQuery) : ''; ?>">« Trước</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?page=<?php echo $i; ?><?php echo $categorySlug ? '&category=' . htmlspecialchars($categorySlug) : ''; ?><?php echo $searchQuery ? '&search=' . htmlspecialchars($searchQuery) : ''; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo $categorySlug ? '&category=' . htmlspecialchars($categorySlug) : ''; ?><?php echo $searchQuery ? '&search=' . htmlspecialchars($searchQuery) : ''; ?>">Sau »</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

