<?php
// Cấu hình kết nối database
define('DB_HOST', 'sql100.infinityfree.com');
define('DB_USER', 'if0_40705785');
define('DB_PASS', 'EE6dBwNtKRjbq6h');
define('DB_NAME', 'if0_40705785_oss');

// Kết nối database
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $conn->set_charset("utf8mb4");
        
        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        die("Lỗi kết nối: " . $e->getMessage());
    }
}

// Đóng kết nối
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}
?>

