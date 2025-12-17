<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tìm kiếm Sách với PDO và Tham số</title>
    <style>
        #container {
            width: 800px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #e6e6e6;
        }
    </style>
</head>

<body>
    <div id="container">
        <h1>🔍 Tìm kiếm Sách theo Tên</h1>

        <form action="lab8_2_1.php" method="GET">
            <label for="search_term">Nhập tên sách cần tìm:</label>
            <input type="text" id="search_term" name="search_term" 
                   value="<?php echo htmlspecialchars($_GET['search_term'] ?? ''); ?>" 
                   size="50" required />
            <input type="submit" value="Tìm kiếm" />
        </form>

        <hr />

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

        // ------------------- XỬ LÝ TÌM KIẾM -------------------
        if (isset($_GET['search_term']) && !empty(trim($_GET['search_term']))) {
            $searchTerm = trim($_GET['search_term']);
            
            // a. Viết SQL có tham số đặt chỗ
            // (book_name LIKE :term) giúp ngăn chặn SQL Injection và tìm kiếm linh hoạt
            $sql = "SELECT book_id, book_name, price FROM book WHERE book_name LIKE :term"; 
            
            // b. Sử dụng phương thức PDO->prepare($sql)
            $stm = $pdh->prepare($sql); 
            
            // c. Tạo và gán giá trị cho tham số (Thêm % ở đầu và cuối cho LIKE)
            $arr = array(":term" => "%$searchTerm%"); 

            try {
                // d. Thực thi sql bằng phương thức execute($array)
                $stm->execute($arr); 
                $rows = $stm->fetchAll(PDO::FETCH_ASSOC); 
                $count = $stm->rowCount();

                echo "<h2>Kết quả tìm kiếm cho: \"$searchTerm\" ($count kết quả)</h2>";

                if ($count > 0) {
                    // Hiển thị kết quả dưới dạng bảng
                    echo "<table>";
                    echo "<tr><th>ID Sách</th><th>Tên Sách</th><th>Giá</th></tr>";
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['book_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['book_name']) . "</td>";
                        echo "<td>" . number_format($row['price'], 0, ',', '.') . " VNĐ</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div class='message'>Không tìm thấy sách nào khớp với từ khóa.</div>";
                }

            } catch (PDOException $e) {
                echo "<p style='color: red;'>❌ Lỗi truy vấn: " . $e->getMessage() . "</p>";
            }

        } else if (isset($_GET['search_term'])) {
            // Trường hợp người dùng bấm tìm kiếm nhưng để trống
            echo "<div class='message'>Vui lòng nhập từ khóa tìm kiếm.</div>";
        } else {
            // Trường hợp mới vào trang
            echo "<div class='message'>Nhập từ khóa vào ô tìm kiếm và nhấn nút để xem kết quả.</div>";
        }
        ?>
    </div>
</body>

</html>