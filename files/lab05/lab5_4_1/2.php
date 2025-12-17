<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập dữ liệu thông qua URL</title>
</head>
<body>
<?php
echo "ID tương ứng là: " . ($_GET["id"] ?? "không có ID!");
?>

</body>
</html>