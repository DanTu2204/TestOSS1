<?php
// Đảm bảo file chứa class Db đã được nạp

class User extends Db {
    protected $table = 'users'; // Tên bảng: users (đã xác nhận từ phpMyAdmin)

    /**
     * Đăng ký người dùng mới
     *
     * @param string $email Email (sử dụng làm tên đăng nhập)
     * @param string $password Mật khẩu thô
     * @param string $name Tên đầy đủ
     * @return bool|int Số dòng thêm được (1 nếu thành công), hoặc false
     */
    public function register($email, $password, $name) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Các cột: email, password, name, address, phone (dựa trên ảnh phpMyAdmin)
        $sql = "INSERT INTO {$this->table} (email, password, name) VALUES (?, ?, ?)";
        $data = [$email, $hashed_password, $name];
        
        // Sử dụng phương thức insert() từ class Db
        return $this->insert($sql, $data); 
    }

    /**
     * Đăng nhập người dùng
     *
     * @param string $email Tên đăng nhập (email)
     * @param string $password Mật khẩu thô
     * @return array|null Thông tin người dùng (không có mật khẩu) nếu thành công
     */
    public function login($email, $password) {
        $user = $this->getByEmail($email); // Lấy user theo email

        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công, xóa mật khẩu vì lý do bảo mật
            unset($user['password']); 
            return $user;
        }

        return null;
    }

    /**
     * Lấy thông tin người dùng theo email (tên đăng nhập)
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $result = $this->select($sql, [$email]);
        
        return $result ? $result[0] : null;
    }
    
    /**
     * Sửa đổi thông tin người dùng
     */
    public function updateProfile($userId, $name, $address, $phone) {
        $sql = "UPDATE {$this->table} SET name = ?, address = ?, phone = ? WHERE id = ?";
        $data = [$name, $address, $phone, $userId];
        
        return $this->update($sql, $data); // Trả về số dòng cập nhật
    }
}