<?php
// File: Db.php

class Db {
    protected $pdh;

    public function __construct() {
        // Cấu hình kết nối CSDL (Giống như các bài lab trước)
        $host = "localhost";
        $dbname = "bookstore";
        $user = "root";
        $pass = "";

        try {
            $this->pdh = new PDO("mysql:host=$host; dbname=$dbname", $user, $pass);
            $this->pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdh->query("set names 'utf8'");
        } catch (PDOException $e) {
            echo "Lỗi kết nối CSDL: " . $e->getMessage();
            exit;
        }
    }

    /**
     * Thực thi truy vấn SELECT và trả về kết quả
     * @param string $sql Câu lệnh SQL
     * @param array $params Mảng tham số (nếu có)
     * @return array Kết quả truy vấn
     */
    protected function executeQuery($sql, $params = []) {
        $stm = $this->pdh->prepare($sql);
        $stm->execute($params);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thực thi truy vấn DML (INSERT, UPDATE, DELETE)
     * @param string $sql Câu lệnh SQL
     * @param array $params Mảng tham số
     * @return int Số dòng bị ảnh hưởng
     */
    protected function executeNonQuery($sql, $params = []) {
        $stm = $this->pdh->prepare($sql);
        $stm->execute($params);
        return $stm->rowCount();
    }
}
?>