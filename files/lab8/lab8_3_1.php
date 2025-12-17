<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quản lý loại sách - CRUD PDO</title>
    <style>
        /* Khung chứa nội dung chính */
        #container {
            width: 700px;
            margin: 0 auto; /* căn giữa trang */
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
        <h1> Quản lý Loại Sách (Category)</h1>

        <?php
        // ------------------- KẾT NỐI CSDL -------------------
        try {
            $pdh = new PDO("mysql:host=localhost; dbname=bookstore", "root", "");
            $pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Bật chế độ báo lỗi
            $pdh->query("set names 'utf8'");
        } catch (Exception $e) {
            echo "<p style='color: red;'> Lỗi kết nối CSDL: " . $e->getMessage() . "</p>";
            exit;
        }

        // --- XỬ LÝ SỬA/XÓA TỪ URL ---
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            $id = $_GET['id'] ?? '';

            if ($action === 'delete' && $id) {
                // XỬ LÝ XÓA
                $sql = "DELETE FROM category WHERE cat_id = :id";
                $stm = $pdh->prepare($sql);
                $stm->execute([':id' => $id]);
                if ($stm->rowCount() > 0) {
                    echo "<p style='color: green;'> Đã xóa loại sách có mã: $id</p>";
                } else {
                    echo "<p style='color: orange;'> Không tìm thấy hoặc không thể xóa loại sách có mã: $id</p>";
                }
            }
        }

        // --- XỬ LÝ DỮ LIỆU TỪ FORM (INSERT/UPDATE) ---
        $edit_id = ''; // Biến lưu mã loại sách đang được sửa
        $edit_name = ''; // Biến lưu tên loại sách đang được sửa
        $form_action = 'Insert'; // Mặc định là Insert

        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            // Lấy dữ liệu cho chức năng SỬA (Hiển thị lên form)
            $sql = "SELECT cat_id, cat_name FROM category WHERE cat_id = :id";
            $stm = $pdh->prepare($sql);
            $stm->execute([':id' => $_GET['id']]);
            $row = $stm->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $edit_id = $row['cat_id'];
                $edit_name = $row['cat_name'];
                $form_action = 'Update';
            }
        }
        
        if (isset($_POST["sm"])) {
            $cat_id = trim($_POST["cat_id"]);
            $cat_name = trim($_POST["cat_name"]);
            $submit_value = $_POST["sm"];

            if ($submit_value === 'Insert') {
                // XỬ LÝ THÊM LOẠI SÁCH (INSERT)
                if (!empty($cat_id) && !empty($cat_name)) {
                    $sql = "INSERT INTO category(cat_id, cat_name) VALUES(:cat_id, :cat_name)";
                    $arr = array(":cat_id" => $cat_id, ":cat_name" => $cat_name);
                    $stm = $pdh->prepare($sql);
                    
                    try {
                        $stm->execute($arr);
                        $n = $stm->rowCount();
                        echo "<p style='color: green;'> Đã thêm $n loại sách: $cat_id</p>";
                    } catch (PDOException $e) {
                        echo "<p style='color: red;'> Lỗi thêm: Có thể mã loại $cat_id đã tồn tại!</p>";
                    }
                } else {
                    echo "<p style='color: orange;'> Vui lòng nhập đầy đủ Mã loại và Tên loại.</p>";
                }
            } elseif ($submit_value === 'Update') {
                // XỬ LÝ SỬA LOẠI SÁCH (UPDATE)
                if (!empty($cat_id) && !empty($cat_name)) {
                    $sql = "UPDATE category SET cat_name = :cat_name WHERE cat_id = :cat_id";
                    $arr = array(":cat_id" => $cat_id, ":cat_name" => $cat_name);
                    $stm = $pdh->prepare($sql);
                    $stm->execute($arr);
                    $n = $stm->rowCount();

                    if ($n > 0) {
                         echo "<p style='color: green;'> Đã cập nhật $n loại sách có mã $cat_id.</p>";
                    } else {
                        echo "<p style='color: orange;'> Không có dữ liệu nào được thay đổi.</p>";
                    }
                    // Chuyển form về chế độ Insert sau khi Update xong
                    $edit_id = '';
                    $edit_name = '';
                    $form_action = 'Insert';
                }
            }
        }
        ?>

        <h2><?php echo $form_action; ?> Loại Sách</h2>
        <form action="lab8_3.php" method="post">
            <table>
                <tr>
                    <td>Mã loại:</td>
                    <td><input type="text" name="cat_id" value="<?php echo htmlspecialchars($edit_id); ?>" <?php if($form_action === 'Update') echo 'readonly'; ?> />
                        <?php if($form_action === 'Update') echo '<small>(Không thể sửa Mã)</small>'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Tên loại:</td>
                    <td><input type="text" name="cat_name" value="<?php echo htmlspecialchars($edit_name); ?>" required /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="sm" value="<?php echo $form_action; ?>" style="padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;" />
                        <?php if($form_action === 'Update'): ?>
                            <a href="lab8_3.php" style="padding: 10px; margin-left: 10px; background-color: #f44336; color: white; text-decoration: none;">Hủy</a>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </form>

        <hr />

        <h2>Danh sách Loại Sách</h2>
        <?php
        // ------------------- LẤY DANH SÁCH LOẠI SÁCH (SELECT) -------------------
        $stm = $pdh->prepare("select * from category ORDER BY cat_id ASC");
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC); 
        ?>

        <table>
            <tr>
                <th>Mã loại</th>
                <th>Tên loại</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['cat_id']); ?></td>
                <td><?php echo htmlspecialchars($row['cat_name']); ?></td>
                <td>
                    <a href="lab8_3.php?action=edit&id=<?php echo urlencode($row['cat_id']); ?>">Sửa</a> |
                    <a href="lab8_3.php?action=delete&id=<?php echo urlencode($row['cat_id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa loại sách <?php echo htmlspecialchars($row['cat_name']); ?> không?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>