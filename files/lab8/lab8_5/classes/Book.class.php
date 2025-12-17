<?php
// Lớp Book kế thừa từ lớp Db (đã định nghĩa trước đó)
class Book extends Db
{
    // Thuộc tính để lưu tên bảng mặc định
    protected $table = 'book'; 

    // --- CÁC PHƯƠNG THỨC HIỆN CÓ ---

    // Hàm lấy ngẫu nhiên $n quyển sách từ bảng book
    public function getRand($n)
    {
        $n = (int)$n;
        
        // **KHẮC PHỤC LỖI LIMIT:** Truyền biến $n trực tiếp vào chuỗi SQL
        // Vì $n đã được ép kiểu (int) nên an toàn cho LIMIT
        $sql = "SELECT book_id, book_name, img FROM {$this->table} ORDER BY RAND() LIMIT 0, {$n}";
        
        // Gọi select KHÔNG có tham số
        return $this->select($sql); 
    }

    // Hàm lấy sách theo mã nhà xuất bản
    public function getByPubliser($manhaxb)
    {
        $sql = "SELECT book_id, book_name, img 
                FROM {$this->table} 
                WHERE pub_id = ? 
                ORDER BY book_id DESC";
        // Phương thức này dùng tham số lọc WHERE, nên vẫn dùng Prepared Statement
        return $this->select($sql, [$manhaxb]);
    }
    
    // --- PHƯƠNG THỨC BỔ SUNG CHO PHÂN TRANG ---

    /**
     * Lấy tổng số lượng sách trong CSDL
     *
     * @return int Tổng số sách
     */
    public function countBooks() {
        $sql = "SELECT COUNT(book_id) AS total FROM {$this->table}";
        
        $result = $this->select($sql);
        
        return $result ? (int)$result[0]['total'] : 0;
    }

    /**
     * Trả về danh sách sách có hỗ trợ phân trang
     *
     * @param int $limit Số lượng sách trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @return array Danh sách sách
     */
    public function listBooks($limit, $offset) {
        // Chuẩn hóa LIMIT và OFFSET để đảm bảo chúng là số nguyên
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        // **KHẮC PHỤC LỖI LIMIT/OFFSET:** Truyền biến $limit và $offset trực tiếp vào chuỗi SQL
        $sql = "SELECT book_id, book_name, price, img 
                FROM {$this->table} 
                ORDER BY book_id DESC 
                LIMIT {$offset}, {$limit}"; // <--- KHÔNG DÙNG '?' Ở ĐÂY
        
        // Gọi select KHÔNG có tham số
        return $this->select($sql); 
    }
}
?>