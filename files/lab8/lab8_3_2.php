<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quản lý Sách - CRUD PDO</title>
    <style>
        #container {
            width: 1000px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div id="container">
        <h1>📖 Quản lý Sách (Thêm, Xóa, Hiển thị)</h1>

        <?php
        // ------------------- KẾT NỐI CSDL -------------------
        try {
            $pdh = new PDO("mysql:host=localhost; dbname=bookstore", "root", "");
            $pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdh->query("set names 'utf8'");
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Lỗi kết nối CSDL: " . $e->getMessage() . "</p>";
            exit;
        }

        // ------------------- HÀM LẤY DANH SÁCH KHÓA NGOẠI -------------------
        function getForeignKeys($pdh, $table, $id_col, $name_col) {
            $stm = $pdh->prepare("SELECT $id_col, $name_col FROM $table ORDER BY $name_col");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }

        $publishers = getForeignKeys($pdh, 'publisher', 'pub_id', 'pub_name');
        $categories = getForeignKeys($pdh, 'category', 'cat_id', 'cat_name');

        // ------------------- XỬ LÝ XÓA SÁCH -------------------
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM book WHERE book_id = :id";
            $stm = $pdh->prepare($sql);
            $stm->execute([':id' => $id]);

            if ($stm->rowCount() > 0) {
                echo "<p style='color: green;'>✅ Đã xóa sách có mã: **$id**</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Không tìm thấy hoặc không thể xóa sách có mã: **$id**</p>";
            }
        }

        // ------------------- XỬ LÝ THÊM SÁCH -------------------
        if (isset($_POST["sm"]) && $_POST["sm"] === 'Insert') { 
            $book_name = trim($_POST["book_name"]);
            $description = trim($_POST["description"]);
            $price = trim($_POST["price"]);
            $pub_id = trim($_POST["pub_id"]);
            $cat_id = trim($_POST["cat_id"]);
            $img = trim($_POST["img"]); // Giả sử nhập link ảnh

            if (!empty($book_name) && !empty($price) && !empty($pub_id) && !empty($cat_id)) {
                
                // Lưu ý: book_id thường là AUTO_INCREMENT, nên không cần chèn.
                // Nếu book_id không phải AUTO_INCREMENT, bạn cần cung cấp một giá trị.
                
                $sql = "INSERT INTO book(book_name, description, price, pub_id, cat_id, img) 
                        VALUES(:name, :desc, :price, :pub_id, :cat_id, :img)";
                
                $arr = [
                    ":name" => $book_name, 
                    ":desc" => $description, 
                    ":price" => $price, 
                    ":pub_id" => $pub_id, 
                    ":cat_id" => $cat_id,
                    ":img" => $img
                ];
                
                $stm = $pdh->prepare($sql);
                
                try {
                    $stm->execute($arr);
                    $n = $stm->rowCount();
                    echo "<p style='color: green;'>✅ Đã thêm **$n** sách mới: **$book_name**</p>";
                } catch (PDOException $e) {
                    echo "<p style='color: red;'>❌ Lỗi thêm sách: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p style='color: orange;'>⚠️ Vui lòng nhập đầy đủ Tên sách, Giá, NXB và Loại sách.</p>";
            }
        }
        ?>

        <h2> Thêm Sách Mới</h2>
        <form action="lab8_3_2.php" method="post">
            <table>
                <tr>
                    <td>Tên sách:</td>
                    <td><input type="text" name="book_name" required size="50"/></td>
                </tr>
                <tr>
                    <td>Giá (Price):</td>
                    <td><input type="number" name="price" required /></td>
                </tr>
                <tr>
                    <td>Nhà xuất bản (NXB):</td>
                    <td>
                        <select name="pub_id" required>
                            <option value="">-- Chọn NXB --</option>
                            <?php foreach($publishers as $pub): ?>
                                <option value="<?php echo htmlspecialchars($pub['pub_id']); ?>">
                                    <?php echo htmlspecialchars($pub['pub_name']); ?>
                                </option>
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
                                <option value="<?php echo htmlspecialchars($cat['cat_id']); ?>">
                                    <?php echo htmlspecialchars($cat['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Link ảnh (Img):</td>
                    <td><input type="text" name="img" size="50"/></td>
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
        <?php
        // ------------------- LẤY DANH SÁCH SÁCH (SELECT) -------------------
        // Sử dụng JOIN để lấy tên NXB và Loại sách thay vì chỉ ID
        $sql_select = "
            SELECT 
                b.book_id, b.book_name, b.price, b.img,
                p.pub_name, 
                c.cat_name
            FROM book b
            JOIN publisher p ON b.pub_id = p.pub_id
            JOIN category c ON b.cat_id = c.cat_id
            ORDER BY b.book_id DESC
        ";
        $stm = $pdh->prepare($sql_select);
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC); 
        ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Tên sách</th>
                <th>Giá</th>
                <th>NXB</th>
                <th>Loại sách</th>
                <th>Ảnh</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                <td><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</td>
                <td><?php echo htmlspecialchars($row['pub_name']); ?></td>
                <td><?php echo htmlspecialchars($row['cat_name']); ?></td>
                <td><?php echo empty($row['img']) ? 'N/A' : '<img src="' . htmlspecialchars($row['img']) . '" width="50" height="50" alt="Ảnh sách"/>'; ?></td>
                <td>
                    <a href="lab8_5.php?action=delete&id=<?php echo urlencode($row['book_id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sách <?php echo htmlspecialchars($row['book_name']); ?> không?');" style="color: red;">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>