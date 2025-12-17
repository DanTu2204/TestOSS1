<?php
// Đảm bảo file chứa class Db đã được nạp (giả sử là Db.php)

class News extends Db {
    protected $table = 'news'; // Tên bảng: news

    /**
     * Trả về danh sách các tin tức (có thể phân trang)
     *
     * @param int $limit Số lượng tin tức trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu (cho phân trang)
     * @return array Danh sách các tin tức
     */
    public function listNews($limit = 10, $offset = 0) {
        $sql = "SELECT id, title, short_content, img FROM {$this->table} ORDER BY id DESC LIMIT :offset, :limit";
        $data = [
            'offset' => $offset,
            'limit' => $limit
        ];
        // Lưu ý: Class Db của bạn có thể chỉ hỗ trợ placeholder '?'
        // Nếu select() báo lỗi, hãy thay :offset, :limit bằng ?, ? và dùng $data = [$offset, $limit]
        return $this->select($sql, $data);
    }

    /**
     * Trả về chi tiết một tin tức theo ID
     *
     * @param int $id ID của tin tức
     * @return array|null Thông tin chi tiết của tin tức
     */
    public function detail($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $data = [$id];
        
        $result = $this->select($sql, $data);
        
        return $result ? $result[0] : null; 
    }

    // --- PHƯƠNG THỨC BỔ SUNG KHẮC PHỤC LỖI TẠI index.php ---

    /**
     * Lấy danh sách các tin tức nổi bật (hot=1)
     *
     * @param int $limit Số lượng tin cần lấy
     * @return array Danh sách tin tức
     */
    public function getHotNews($limit = 10) {
        $limit = (int)$limit;
        
        // Truy vấn lấy tin tức có hot=1 và giới hạn số lượng
        // Sử dụng kỹ thuật chèn biến trực tiếp vào SQL (sau khi đã ép kiểu int)
        // để tránh lỗi ràng buộc tham số LIMIT
        $sql = "SELECT id, title, short_content, img 
                FROM {$this->table} 
                WHERE hot = 1 
                ORDER BY id DESC 
                LIMIT 0, {$limit}";
        
        // Gọi select không có tham số ràng buộc
        return $this->select($sql);
    }
}