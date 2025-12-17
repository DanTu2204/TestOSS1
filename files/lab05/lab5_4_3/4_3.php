<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab5_4_3 - Đăng ký thành viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body class="container">
    <h2>Đăng ký thành viên</h2>
    <form style="background-color: #f9f9f9;" action="" method="post" enctype="multipart/form-data" class="form-control">
        <label for="username">Tên đăng nhập (*):</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mật khẩu (*):</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Nhập lại mật khẩu (*):</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <label for="gender">Giới tính (*):</label><br>
        <input type="radio" id="male" name="gender" value="Nam" required>
        <label for="male">Nam</label><br>
        <input type="radio" id="female" name="gender" value="Nữ" required>
        <label for="female">Nữ</label><br><br>

        <label for="hobbies">Sở thích:</label><br>
        <textarea id="hobbies" name="hobbies"></textarea><br><br>

        <label for="image">Hình ảnh (tùy chọn):</label>
        <input type="file" id="image" name="image" accept="image/*"><br><br>

        <label for="province">Tỉnh (*):</label>
        <select id="province" name="province" required>
            <option value="">Chọn tỉnh</option>
            <option value="Hà Nội">Hà Nội</option>
            <option value="Hồ Chí Minh">Hồ Chí Minh</option>
            <option value="Đà Nẵng">Đà Nẵng</option>
            <option value="Khánh Hòa">Khánh Hòa</option>
            <!-- Thêm các tỉnh khác nếu cần -->
        </select><br><br>

        <input type="submit" value="Đăng ký" class="btn btn-primary">
        <input type="reset" value="Reset" class="btn btn-danger">


    </form>


<?php
if (isset($_POST["submit"])) {
    $errors = [];

    // Kiểm tra bắt buộc
    if (empty($_POST["username"])) $errors[] = "Tên đăng nhập là bắt buộc.";
    if (empty($_POST["password"])) $errors[] = "Mật khẩu là bắt buộc.";
    if (empty($_POST["repassword"])) $errors[] = "Vui lòng nhập lại mật khẩu.";
    if (empty($_POST["gender"])) $errors[] = "Vui lòng chọn giới tính.";
    if (empty($_POST["province"])) $errors[] = "Vui lòng chọn tỉnh.";

    // Kiểm tra mật khẩu trùng nhau
    if (!empty($_POST["password"]) && $_POST["password"] !== $_POST["repassword"]) {
        $errors[] = "Mật khẩu và nhập lại mật khẩu không trùng khớp.";
    }

    // Kiểm tra ảnh
    if (!empty($_FILES["image"]["name"])) {
        $allowed = ["jpg", "jpeg", "png", "bmp", "gif"];
        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors[] = "Hình ảnh không hợp lệ. Chỉ chấp nhận: jpg, png, bmp, gif.";
        } else {
            $target = "uploads/" . basename($_FILES["image"]["name"]);
            if (!is_dir("uploads")) mkdir("uploads");
            move_uploaded_file($_FILES["image"]["tmp_name"], $target);
        }
    }

    // Xuất kết quả
    if (empty($errors)) {
        echo "<h3>Thông tin đăng ký hợp lệ:</h3>";
        echo "Tên đăng nhập: " . htmlspecialchars($_POST["username"]) . "<br>";
        echo "Giới tính: " . htmlspecialchars($_POST["gender"]) . "<br>";
        echo "Tỉnh: " . htmlspecialchars($_POST["province"]) . "<br>";

        if (!empty($_POST["hobby"])) {
            echo "Sở thích: " . htmlspecialchars($_POST["hobby"]) . "<br>";
        }

        if (!empty($_FILES["image"]["name"])) {
            echo "Hình ảnh:<br><img src='$target' width='150'><br>";
        }
    } else {
        echo "<h3>Dữ liệu không hợp lệ:</h3><ul>";
        foreach ($errors as $err) {
            echo "<li>" . htmlspecialchars($err) . "</li>";
        }
        echo "</ul>";
    }


}
?>
</body>
</html>
