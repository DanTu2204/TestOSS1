<?php
// File: Book.php

require_once 'Db.php'; // Đảm bảo đã nhúng class Db

class Book extends Db {
    
    // ------------------- 1. HIỂN THỊ (SELECT - READ) -------------------
    /**
     * Lấy tất cả sách với tên NXB và Loại sách (JOIN)
     * @return array Mảng các sách
     */
    public function getAllBooks() {
        $sql = "
            SELECT 
                b.book_id, b.book_name, b.price, b.img,
                p.pub_name, 
                c.cat_name
            FROM book b
            JOIN publisher p ON b.pub_id = p.pub_id
            JOIN category c ON b.cat_id = c.cat_id
            ORDER BY b.book_id DESC
        ";
        return $this->executeQuery($sql);
    }
    
    /**
     * Lấy danh sách cho Select Box (Nhà xuất bản/Loại sách)
     */
    public function getForeignKeys($table, $id_col, $name_col) {
        $sql = "SELECT $id_col, $name_col FROM $table ORDER BY $name_col";
        return $this->executeQuery($sql);
    }

    // ------------------- 2. THÊM (INSERT - CREATE) -------------------
    /**
     * Thêm sách mới vào CSDL
     * @param array $data Dữ liệu sách (name, price, pub_id, cat_id, etc.)
     * @return int Số dòng đã được thêm (0 hoặc 1)
     */
    public function addBook($data) {
        $sql = "INSERT INTO book(book_name, description, price, pub_id, cat_id, img) 
                VALUES(:name, :desc, :price, :pub_id, :cat_id, :img)";
        
        $params = [
            ":name" => $data['book_name'],
            ":desc" => $data['description'] ?? '', // Xử lý trường optional
            ":price" => $data['price'],
            ":pub_id" => $data['pub_id'],
            ":cat_id" => $data['cat_id'],
            ":img" => $data['img'] ?? ''
        ];
        
        return $this->executeNonQuery($sql, $params);
    }

    // ------------------- 3. XÓA (DELETE) -------------------
    /**
     * Xóa sách theo ID
     * @param int $book_id Mã sách cần xóa
     * @return int Số dòng đã bị xóa
     */
    public function deleteBook($book_id) {
        $sql = "DELETE FROM book WHERE book_id = :id";
        return $this->executeNonQuery($sql, [':id' => $book_id]);
    }
    
    // ------------------- CHỨC NĂNG SỬA (UPDATE) - BỔ SUNG -------------------
    // Mặc dù yêu cầu chỉ là Thêm/Hiển thị/Xóa, tôi bổ sung thêm chức năng Sửa cho hoàn chỉnh
    public function updateBook($book_id, $data) {
        $sql = "UPDATE book 
                SET book_name = :name, description = :desc, price = :price, 
                    pub_id = :pub_id, cat_id = :cat_id, img = :img
                WHERE book_id = :id";
        
        $params = [
            ":id" => $book_id,
            ":name" => $data['book_name'],
            ":desc" => $data['description'] ?? '',
            ":price" => $data['price'],
            ":pub_id" => $data['pub_id'],
            ":cat_id" => $data['cat_id'],
            ":img" => $data['img'] ?? ''
        ];
        
        return $this->executeNonQuery($sql, $params);
    }
}
?>