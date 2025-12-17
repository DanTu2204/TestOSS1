<!DOCTYPE html PUBLIC "-//W3CDTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab8_2 - PDO - MySQL - select - insert - parameter</title>
</head>

<body>
    <h1>PDO - Truy vấn Tham số Đặt chỗ (SELECT, INSERT, UPDATE, DELETE)</h1>
    <?php
    // ------------------- KẾT NỐI CSDL -------------------
    try {
        // Tạo đối tượng PDO kết nối đến database 'bookstore' với user 'root'
        $pdh = new PDO("mysql:host=localhost; dbname=bookstore", "root", "");
        // Thiết lập bộ mã UTF-8 để hiển thị tiếng Việt đúng
        $pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Bật chế độ báo lỗi (tốt cho debug)
        $pdh->query("set names 'utf8'");
        echo "Kết nối CSDL thành công!<br>";
    } catch (Exception $e) {
        // Nếu kết nối thất bại thì báo lỗi và dừng chương trình
        echo "Lỗi kết nối CSDL: " . $e->getMessage();
        exit;
    }
    ?>

    <hr />

    <h2>1. Truy vấn SELECT với Tham số</h2>
    <?php
    // a. Viết sql có tham số đặt chỗ (Named Placeholder: :ten)
    $search = "a"; 
    $sql = "select * from publisher where pub_name like :ten"; 
    
    // b. Sử dụng phương thức PDO->prepare($sql)
    $stm = $pdh->prepare($sql); 
    
    // Sử dụng bindValue() để gán giá trị
    $stm->bindValue(":ten", "%$search%"); 
    
    // d. Thực thi sql bằng phương thức execute()
    $stm->execute(); 
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC); 

    // In kết quả ra màn hình
    echo "Tìm kiếm NXB chứa ký tự '$search' (" . $stm->rowCount() . " dòng):";
    echo "<pre>";
    print_r($rows);
    echo "</pre>";
    ?>

    <hr />

    <h2>2. Truy vấn INSERT với Tham số</h2>
    <?php
    // Chuẩn bị dữ liệu cho INSERT và đảm bảo không bị lỗi khóa chính
    $ma = "LS_2"; 
    $ten = "Loại Sách Mới"; 
    
    // Xóa dữ liệu cũ nếu tồn tại để có thể chèn lại
    $pdh->exec("DELETE FROM category WHERE cat_id = '$ma'");

    // a. Viết sql có tham số đặt chỗ (Named Placeholders)
    $sql = "INSERT INTO category(cat_id, cat_name) VALUES(:maloai, :tenloai)"; 
    
    // b. Sử dụng phương thức PDO->prepare($sql)
    $stm = $pdh->prepare($sql); 
    
    // c. Tạo mảng các tham số đặt chỗ trước tương ứng với các tham số trong sql
    $arr = array(":maloai" => $ma, ":tenloai" => $ten); 

    // d. Thực thi sql bằng phương thức PDOStatement->execute($array)
    $stm->execute($arr); 
    $n = $stm->rowCount(); 

    // In thông báo kết quả
    echo "Đã thêm $n loại sách: $ma - $ten";
    ?>

    <hr />

    <h2>3. Truy vấn UPDATE với Tham số</h2>
    <?php
    // a. Viết sql có tham số đặt chỗ
    $sql_update = "UPDATE category SET cat_name = :new_name WHERE cat_id = :id_to_update";
    
    // Chuẩn bị dữ liệu
    $new_name = "Sách Cập Nhật - " . date("H:i:s");
    $id_to_update = $ma; // Cập nhật bản ghi vừa thêm
    
    // b. prepare
    $stm_update = $pdh->prepare($sql_update);
    
    // c. Tạo mảng tham số
    $arr_update = array(":new_name" => $new_name, ":id_to_update" => $id_to_update);
    
    // d. execute
    $stm_update->execute($arr_update);
    $n_update = $stm_update->rowCount();
    
    echo "Đã cập nhật $n_update dòng (ID: $id_to_update) với tên mới: $new_name";
    ?>

    <hr />

    <h2>4. Truy vấn DELETE với Tham số</h2>
    <?php
    // a. Viết sql có tham số đặt chỗ
    $sql_delete = "DELETE FROM category WHERE cat_id = :id_to_delete";
    
    // Chuẩn bị dữ liệu
    $id_to_delete = $ma; // Xóa bản ghi vừa cập nhật
    
    // b. prepare
    $stm_delete = $pdh->prepare($sql_delete);
    
    // c. Tạo mảng tham số
    $arr_delete = array(":id_to_delete" => $id_to_delete);
    
    // d. execute
    $stm_delete->execute($arr_delete);
    $n_delete = $stm_delete->rowCount();
    
    echo "Đã xóa $n_delete dòng (ID: $id_to_delete).";
    ?>
    
</body>

</html>