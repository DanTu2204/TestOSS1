<?php
/**
 * Setup script - Chạy file này một lần để tạo password hash cho admin
 * Sau khi chạy xong, xóa file này để bảo mật
 */

// Tạo password hash cho admin
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Hash cho Admin</h2>";
echo "<p>Password: admin123</p>";
echo "<p>Hash: <strong>" . $hash . "</strong></p>";
echo "<hr>";
echo "<p>Copy hash trên và cập nhật vào database trong bảng users cho user admin, hoặc cập nhật file database.sql</p>";
echo "<p style='color: red;'><strong>Quan trọng: Xóa file setup.php sau khi sử dụng!</strong></p>";
?>

