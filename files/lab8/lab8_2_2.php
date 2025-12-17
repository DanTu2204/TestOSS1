<?php
// File: index.php (Giao diện sử dụng class Book)

require_once 'Book.php';
$bookManager = new Book();

// ------------------- XỬ LÝ SỰ KIỆN FORM -------------------
$message = '';

// Xử lý XÓA
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $count = $bookManager->deleteBook($id);
    if ($count > 0) {
        $message = "<p style='color: green;'>✅ Đã xóa sách có mã: **$id**</p>";
    } else {
        $message = "<p style='color: orange;'>⚠️ Không tìm thấy hoặc không thể xóa sách có mã: **$id**</p>";
    }
}

// Xử lý THÊM
if (isset($_POST["sm"]) && $_POST["sm"] === 'Insert') { 
    $data = [
        'book_name' => trim($_POST["book_name"]),
        'description' => trim($_POST["description"]),
        'price' => trim($_POST["price"]),
        'pub_id' => trim($_POST["pub_id"]),
        'cat_id' => trim($_POST["cat_id"]),
        'img' => trim($_POST["img"]),
    ];

    if (!empty($data['book_name']) && !empty($data['price'])) {
        try {
            $count = $bookManager->addBook($data);
            $message = "<p style='color: green;'> Đã thêm **$count** sách mới: **{$data['book_name']}**</p>";
        } catch (PDOException $e) {
             // Lỗi thường là do khóa ngoại không tồn tại
             $message = "<p style='color: red;'> Lỗi thêm sách: Vui lòng kiểm tra lại ID NXB/Loại sách.</p>";
        }
    } else {
        $message = "<p style='color: orange;'> Vui lòng nhập đầy đủ Tên sách và Giá.</p>";
    }
}

// Lấy dữ liệu cần thiết cho form và bảng
$publishers = $bookManager->getForeignKeys('publisher', 'pub_id', 'pub_name');
$categories = $bookManager->getForeignKeys('category', 'cat_id', 'cat_name');
$rows = $bookManager->getAllBooks();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quản lý Sách - OOP PDO</title>
    <style>
        #container { width: 1000px; margin: 0 auto; font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div id="container">
        <h1>📖 Quản lý Sách (OOP Class Book)</h1>
        <?php echo $message; ?>

        <h2>Thêm Sách Mới</h2>
        <form action="lab8_2_2.php" method="post">
            <table>
                <tr><td>Tên sách:</td><td><input type="text" name="book_name" required size="50"/></td></tr>
                <tr><td>Giá (Price):</td><td><input type="number" name="price" required /></td></tr>
                <tr>
                    <td>Nhà xuất bản (NXB):</td>
                    <td>
                        <select name="pub_id" required>
                            <option value="">-- Chọn NXB --</option>
                            <?php foreach($publishers as $pub): ?>
                                <option value="<?php echo htmlspecialchars($pub['pub_id']); ?>"><?php echo htmlspecialchars($pub['pub_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Loại sách:</td>
                    <td>
                        <select name="cat_id" required>
                            <option value="">-- Chọn Loại sách --</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['cat_id']); ?>"><?php echo htmlspecialchars($cat['cat_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="sm" value="Insert" style="padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer;" />
                    </td>
                </tr>
            </table>
        </form>

        <hr />

        <h2>📊 Danh sách Sách Hiện có</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tên sách</th>
                <th>Giá</th>
                <th>NXB</th>
                <th>Loại sách</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                <td><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</td>
                <td><?php echo htmlspecialchars($row['pub_name']); ?></td>
                <td><?php echo htmlspecialchars($row['cat_name']); ?></td>
                <td>
                    <a href="index.php?action=delete&id=<?php echo urlencode($row['book_id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sách <?php echo htmlspecialchars($row['book_name']); ?> không?');" style="color: red;">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>