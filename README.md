# Trang Web Bán Quần Áo Thể Thao

Trang web bán quần áo thể thao được xây dựng bằng PHP, hoạt động trên WAMP Server (localhost).

## Yêu cầu hệ thống

- WAMP Server (hoặc XAMPP/MAMP)
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Trình duyệt web hiện đại

## Hướng dẫn cài đặt

### 1. Cài đặt WAMP Server

- Tải và cài đặt WAMP Server từ: https://www.wampserver.com/
- Đảm bảo WAMP Server đang chạy (icon màu xanh)

### 2. Cấu hình Database

1. Mở phpMyAdmin (http://localhost/phpmyadmin)
2. Tạo database mới với tên `sports_clothing` hoặc import file `database.sql`
3. Import file `database.sql` vào database vừa tạo:
   - Chọn database `sports_clothing`
   - Click tab "Import"
   - Chọn file `database.sql`
   - Click "Go"

### 3. Cấu hình kết nối Database

Mở file `config/database.php` và kiểm tra các thông tin:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Để trống nếu WAMP mặc định
define('DB_NAME', 'sports_clothing');
```

### 4. Đặt thư mục project

Copy toàn bộ thư mục project vào thư mục `www` của WAMP:
- Windows: `C:\wamp64\www\quan-ao-the-thao\`
- Hoặc tạo Virtual Host trong WAMP

### 5. Truy cập website

Mở trình duyệt và truy cập:
```
http://localhost/quan-ao-the-thao/
```

## Tài khoản mặc định

### Admin
- Username: `admin`
- Password: `admin123`
- Nếu không đăng nhập được, chạy file `setup.php` để tạo password hash mới hoặc reset trong phpMyAdmin

### Khách hàng
- Đăng ký tài khoản mới tại trang đăng ký

## Cấu trúc thư mục

```
quan-ao-the-thao/
├── assets/
│   ├── css/
│   │   └── style.css          # File CSS chính
│   ├── js/
│   │   └── main.js            # File JavaScript
│   └── images/                # Thư mục hình ảnh
├── config/
│   ├── database.php           # Cấu hình database
│   └── session.php            # Quản lý session
├── includes/
│   ├── header.php             # Header chung
│   └── footer.php             # Footer chung
├── index.php                  # Trang chủ
├── products.php               # Danh sách sản phẩm
├── product-detail.php         # Chi tiết sản phẩm
├── cart.php                   # Giỏ hàng
├── cart-action.php            # Xử lý giỏ hàng
├── checkout.php               # Thanh toán
├── order-success.php          # Thành công đặt hàng
├── login.php                  # Đăng nhập
├── register.php               # Đăng ký
├── logout.php                 # Đăng xuất
├── profile.php                # Tài khoản
├── order-detail.php           # Chi tiết đơn hàng
├── contact.php                # Liên hệ
├── database.sql               # File SQL database
└── README.md                  # File hướng dẫn
```

## Chức năng chính

### Khách hàng
- ✅ Xem danh sách sản phẩm
- ✅ Tìm kiếm và lọc sản phẩm theo danh mục
- ✅ Xem chi tiết sản phẩm
- ✅ Thêm sản phẩm vào giỏ hàng
- ✅ Quản lý giỏ hàng (thêm, sửa, xóa)
- ✅ Đăng ký/Đăng nhập
- ✅ Thanh toán đơn hàng
- ✅ Xem lịch sử đơn hàng
- ✅ Xem chi tiết đơn hàng

### Admin (có thể mở rộng)
- Tài khoản admin đã được tạo sẵn
- Có thể mở rộng thêm trang quản trị

## Ghi chú

1. **Hình ảnh sản phẩm**: 
   - Website sẽ hiển thị placeholder nếu chưa có hình ảnh
   - Để thêm hình ảnh: Tạo thư mục `assets/images/products/` và thêm hình ảnh
   - Cập nhật đường dẫn hình ảnh trong database nếu cần
   
2. **Setup Password**: 
   - Nếu không đăng nhập được với tài khoản admin, chạy `setup.php` để tạo hash mới
   - Sau khi dùng xong, **xóa file setup.php** để bảo mật

2. **Bảo mật**: Đây là phiên bản demo, trong môi trường production cần:
   - Validate input kỹ hơn
   - Sử dụng prepared statements (đã có)
   - Thêm CSRF protection
   - Hash password (đã có)

3. **Tùy chỉnh**: Bạn có thể tùy chỉnh:
   - Màu sắc trong file `assets/css/style.css`
   - Thông tin liên hệ trong `includes/footer.php` và `contact.php`
   - Logo và tên website trong `includes/header.php`

## Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
1. WAMP Server đã chạy chưa
2. Database đã được tạo và import chưa
3. Cấu hình database trong `config/database.php` đúng chưa
4. File permissions đã đúng chưa

## License

Dự án mã nguồn mở, tự do sử dụng và chỉnh sửa.

