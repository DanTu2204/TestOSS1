<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab8_1 - PDO - MySQL</title>
</head>

<body>
    <h1>PDO - Truy vấn</h1>
    <?php
    // ------------------- KẾT NỐI CSDL -------------------
    // a. Kết nối tới csdl
    try {
        // Tạo đối tượng PDO kết nối đến database 'bookstore' với user 'root'
        $pdh = new PDO("mysql:host=localhost; dbname=bookstore", "root", "");
        // Thiết lập bộ mã UTF-8 để hiển thị tiếng Việt đúng
        $pdh->query("set names 'utf8'");
        echo "Kết nối CSDL thành công!<br>";
    } catch (Exception $e) {
        // Nếu kết nối thất bại thì báo lỗi và dừng chương trình
        echo "Lỗi kết nối CSDL: " . $e->getMessage();
        exit;
    }
    ?>

    <hr />

    <h2>1. Truy vấn CATEGORY</h2>
    <?php
    // b. Viết sql, c. Thực thi sql bằng query()
    $stm = $pdh->query("select * from category"); 
    // d.i. Lấy số dòng kết quả SELECT
    echo "Số dòng trả về:" . $stm->rowCount() . ""; 
    // e.i. Lấy tất cả dữ liệu: FETCH_ASSOC (Mảng kết hợp)
    $rows1 = $stm->fetchAll(PDO::FETCH_ASSOC); 

    // Duyệt qua từng dòng và in ra mã loại + tên loại
    foreach ($rows1 as $row) {
        echo "<br>" . $row["cat_id"] . "-" . $row["cat_name"];
    }
    ?>
    <hr />

    <h2>2. Truy vấn PUBLISHER</h2>
    <?php
    $stm = $pdh->query("select * from publisher");
    echo "Số dòng trả về: " . $stm->rowCount() . "";
    // e.i. Lấy tất cả dữ liệu: FETCH_OBJ (Đối tượng)
    $rows2 = $stm->fetchAll(PDO::FETCH_OBJ); 

    // Duyệt qua từng dòng và in ra mã NXB + tên NXB
    foreach ($rows2 as $row) {
        echo "<br>" . $row->pub_id . "-" . $row->pub_name;
    }
    ?>

    <hr />

    <h2>3. Truy vấn BOOK</h2>
    <?php
    // b. Viết sql
    $sql = "select * from book where book_name like '%a%' "; // tìm sách có chữ 'a' trong tên
    $stm = $pdh->query($sql);
    echo "Số dòng trả về: " . $stm->rowCount() . "";
    // e.i. Lấy tất cả dữ liệu: FETCH_NUM (Mảng số)
    $rows3 = $stm->fetchAll(PDO::FETCH_NUM); 

    // Duyệt qua từng dòng và in ra cột 0 (id) và cột 1 (tên sách)
    foreach ($rows3 as $row) {
        echo "<br>" . $row[0] . "-" . $row[1];
    }
    ?>

    <hr />

    <h2>4. Lấy Từng Dòng và Vòng lặp WHILE</h2>
    <?php
    $stm = $pdh->query("select * from category");
    echo "Số dòng trả về: " . $stm->rowCount() . "";
    
    // e.ii. Lấy một dòng: fetch() tuần tự
    echo "<p>Lấy 2 dòng đầu tiên tuần tự:</p>";
    $row = $stm->fetch(PDO::FETCH_ASSOC); // lấy 1 dòng đầu tiên
    echo "Dòng 1: "; print_r($row);
    echo "<br>";
    $row = $stm->fetch(PDO::FETCH_ASSOC); // lấy dòng tiếp theo
    echo "Dòng 2: "; print_r($row);
    
    echo "<p>Duyệt các dòng còn lại bằng vòng lặp while:</p>";
    $stm = $pdh->query("select * from publisher"); // Thực hiện lại truy vấn
    // Duyệt qua từng dòng bằng vòng lặp while
    while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
        echo "<br>" . $row["pub_id"] . "-" . $row["pub_name"];
    }
    ?>

    <hr />


</body>

</html>