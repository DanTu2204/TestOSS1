<?php
// Lưu ý: Biến $book (đối tượng Book) đã được khởi tạo ở index.php

// Cấu hình số sách muốn hiển thị trên mỗi trang
$booksPerPage = 8; 

// --- BƯỚC 1: XÁC ĐỊNH THAM SỐ LỌC (Filtering) ---
$cat_id = getIndex("cat_id", "all");
$pub_id = getIndex("pub_id", "all");

// SQL cơ sở và mảng tham số (sẽ dùng cho cả COUNT và SELECT)
$base_sql = "SELECT * FROM book WHERE 1 ";
$arr = array();

// Thêm điều kiện lọc vào SQL và mảng tham số (dùng placeholder tên)
if ($cat_id != "all") {
    $base_sql .= " AND cat_id = :cat_id ";
    $arr[":cat_id"] = $cat_id;
}

if ($pub_id != "all") {
    $base_sql .= " AND pub_id = :pub_id ";
    $arr[":pub_id"] = $pub_id;
}


// --- BƯỚC 2: TÍNH TOÁN TỔNG SỐ KẾT QUẢ ĐỂ PHÂN TRANG (Count Records) ---

// Tạo SQL để đếm (chỉ cần COUNT(book_id) và các điều kiện lọc)
// Tận dụng $base_sql và $arr
$count_sql = str_replace("SELECT *", "SELECT COUNT(book_id) AS total", $base_sql);

// Thực hiện đếm
$count_result = $book->select($count_sql, $arr);
$totalBooks = $count_result ? (int)$count_result[0]['total'] : 0;
$totalPages = ceil($totalBooks / $booksPerPage);


// --- BƯỚC 3: XỬ LÝ PHÂN TRANG (Pagination Parameters) ---

$currentPage = getIndex('page', 1); 
$currentPage = (int)$currentPage;

if ($currentPage < 1) {
    $currentPage = 1;
}
if ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
} elseif ($totalPages == 0) {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $booksPerPage;


// --- BƯỚC 4: TRUY VẤN DỮ LIỆU CỦA TRANG HIỆN TẠI (Fetch Current Page Data) ---

// Thêm sắp xếp và LIMIT/OFFSET vào SQL
// Sử dụng hàm listBooks đã được sửa để truyền LIMIT/OFFSET trực tiếp vào SQL (để tránh lỗi ràng buộc)
// Chúng ta sẽ lấy dữ liệu bằng cách sử dụng các tham số lọc, sau đó truyền LIMIT/OFFSET
$data_sql = $base_sql . " ORDER BY book_id DESC LIMIT {$offset}, {$booksPerPage}";

// Lấy danh sách sách của trang hiện tại (chỉ truyền tham số lọc)
$list = $book->select($data_sql, $arr);


// --- BƯỚC 5: HIỂN THỊ KẾT QUẢ VÀ SÁCH ---

echo "<h3>Có " . $totalBooks . " kết quả (Trang {$currentPage} / {$totalPages})</h3>";

foreach($list as $r)
{
 ?>
 <div class="book">
<img src="<?php echo BASE_URL; ?>/image/book/<?php echo $r['img']; ?>" alt="<?php echo $r['book_name']; ?>">
 <p><?php echo $r["book_name"];?></p>
 <p>Giá: <?php echo number_format($r['price'] ?? 0); ?> VND</p>
 </div>
 <?php 
}

// --- BƯỚC 6: GIAO DIỆN PHÂN TRANG (Navigation) ---

if ($totalPages > 1): ?>
    <div class="pagination">
    <?php
    // Xây dựng URL cơ sở, giữ lại tham số lọc
    $baseURL = "index.php?ac=list";
    if ($cat_id != "all") $baseURL .= "&cat_id={$cat_id}";
    if ($pub_id != "all") $baseURL .= "&pub_id={$pub_id}";
    ?>

    <?php if ($currentPage > 1): ?>
        <a href="<?php echo $baseURL; ?>&page=<?php echo $currentPage - 1; ?>">&laquo; Trước</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i == $currentPage): ?>
            <span class="current-page"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="<?php echo $baseURL; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
        <a href="<?php echo $baseURL; ?>&page=<?php echo $currentPage + 1; ?>">Sau &raquo;</a>
    <?php endif; ?>

    </div>
<?php endif; ?>