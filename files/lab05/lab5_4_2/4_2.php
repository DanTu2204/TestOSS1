<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<fieldset>
    <legend>Form_4.2</legend>
    <form action="" method="get">
        Nhập tên sản phẩm cần tìm:
        <input type="text" name="ten" value="<?= htmlspecialchars($_GET['ten'] ?? '') ?>"><br><br>
        Cách tìm:
        <input type="radio" name="ct" value="Gan_dung" <?= (($_GET['ct'] ?? '') == 'Gan_dung') ? 'checked' : '' ?>> Gần đúng
        <input type="radio" name="ct" value="Chinh_xac" <?= (($_GET['ct'] ?? '') == 'Chinh_xac') ? 'checked' : '' ?>> Chính xác
        <br><br>
        Loại sản phẩm:<br>
        <input type="checkbox" name="loai[]" value="loai1" <?= (in_array('loai1', $_GET['loai'] ?? []) ? 'checked' : '') ?>> Loại 1<br>
        <input type="checkbox" name="loai[]" value="loai2" <?= (in_array('loai2', $_GET['loai'] ?? []) ? 'checked' : '') ?>> Loại 2<br>
        <input type="checkbox" name="loai[]" value="loai3" <?= (in_array('loai3', $_GET['loai'] ?? []) ? 'checked' : '') ?>> Loại 3<br>
        <input type="checkbox" name="loai[]" value="tatca" <?= (in_array('tatca', $_GET['loai'] ?? []) ? 'checked' : '') ?>> Tất cả<br><br>
        <input type="submit" value="Gửi">
    </form>
</fieldset>
<?php
if (isset($_GET['ten'])) {
    echo "Tên sản phẩm vừa nhập: " . htmlspecialchars($_GET['ten']) . "<br>";
}
if (isset($_GET['ct'])) {
    echo "Cách tìm: " . htmlspecialchars($_GET['ct']) . "<br>";
}
if (isset($_GET['loai'])) {
    echo "Loại sản phẩm: ";
    if (is_array($_GET['loai'])) {
        echo implode(", ", $_GET['loai']);  
    }
} else {
    echo "Chưa chọn loại.";
}
echo "<hr>";
print_r($_GET);
?>
</body>
</html>