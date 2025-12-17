<?php
// Đảm bảo file chứa class Db đã được nạp

class Cart extends Db {
    // Tên biến Session để lưu trữ giỏ hàng
    const CART_SESSION_KEY = 'lab8_5_user_cart';

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION[self::CART_SESSION_KEY])) {
            $_SESSION[self::CART_SESSION_KEY] = [];
        }
        // Gọi constructor của lớp cha (Db) để kết nối CSDL
        parent::__construct(); 
    }

    /**
     * Thêm/cập nhật số lượng sản phẩm vào giỏ hàng
     */
    public function add($bookId, $quantity = 1, $book_info = []) {
        if (isset($_SESSION[self::CART_SESSION_KEY][$bookId])) {
            $_SESSION[self::CART_SESSION_KEY][$bookId]['quantity'] += $quantity;
        } else {
            $_SESSION[self::CART_SESSION_KEY][$bookId] = [
                'book_id' => $bookId,
                'quantity' => $quantity,
                'book_name' => $book_info['name'] ?? 'Sản phẩm', 
                'price' => $book_info['price'] ?? 0,
            ];
        }
    }

    /**
     * Lấy danh sách các mục trong giỏ hàng để hiển thị
     */
    public function getItems() {
        return $_SESSION[self::CART_SESSION_KEY];
    }

    /**
     * Tính tổng số tiền của giỏ hàng
     */
    public function getTotal() {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    /**
     * Phương thức thực hiện thanh toán (lưu vào bảng order và order_detail)
     *
     * @param int $userId ID người dùng (user_id trong bảng order)
     * @param array $consigneeInfo Thông tin người nhận hàng (tên, địa chỉ, sđt)
     * @return bool
     */
    public function checkout($userId, $consigneeInfo) {
        $items = $this->getItems();
        if (empty($items)) {
            return false;
        }

        // Bắt đầu transaction (tùy chọn) để đảm bảo tính toàn vẹn
        // $this->dbh->beginTransaction(); 

        // 1. Thêm Order mới vào bảng 'order'
        // Các cột: order_id, user_id, order_date, consignee_name, consignee_add, consignee_phone
        $orderSql = "INSERT INTO `order` (user_id, order_date, consignee_name, consignee_add, consignee_phone) 
                     VALUES (?, NOW(), ?, ?, ?)";
        
        $orderData = [
            $userId,
            $consigneeInfo['name'],
            $consigneeInfo['address'],
            $consigneeInfo['phone']
        ];
        
        // Sử dụng insert(), nó trả về số dòng bị ảnh hưởng (1)
        $this->insert($orderSql, $orderData); 
        
        // Lấy Order ID vừa tạo (Db class của bạn không có lastInsertId(), đây là lỗ hổng cần khắc phục)
        // **KHUYẾN CÁO**: Class Db cần được bổ sung phương thức lastInsertId()
        // TẠM THỜI: Giả sử bạn có thể lấy Order ID bằng cách nào đó (ví dụ: truy vấn MAX(order_id))
        
        // Để demo, ta sẽ giả sử có một biến $orderId được lấy
        // *** Nếu class Db của bạn có phương thức lastInsertId(), hãy dùng nó! ***
        
        // GIẢ SỬ $orderId = ...; 

        // 2. Thêm chi tiết đơn hàng vào bảng 'order_detail'
        // $detailSql = "INSERT INTO `order_detail` (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)";
        // ... (thực thi)

        // 3. Xóa giỏ hàng khỏi Session
        $this->clear(); 
        
        // $this->dbh->commit(); // Kết thúc transaction
        return true;
    }
    
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear() {
        unset($_SESSION[self::CART_SESSION_KEY]);
        $_SESSION[self::CART_SESSION_KEY] = [];
    }
}