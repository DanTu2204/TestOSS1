-- Database: sports_clothing
-- Tạo database
CREATE DATABASE IF NOT EXISTS if0_40705785_oss CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE  if0_40705785_oss;

-- Bảng người dùng
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng danh mục sản phẩm
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) DEFAULT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng đơn hàng
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_name VARCHAR(100) NOT NULL,
    shipping_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dữ liệu mẫu
-- Thêm danh mục
INSERT INTO categories (name, slug, description) VALUES
('Áo Thể Thao', 'ao-the-thao', 'Áo thể thao nam nữ chất lượng cao'),
('Quần Thể Thao', 'quan-the-thao', 'Quần thể thao co giãn, thoáng khí'),
('Giày Thể Thao', 'giay-the-thao', 'Giày chạy bộ, tập gym chính hãng'),
('Phụ Kiện', 'phu-kien', 'Mũ, túi, bình nước thể thao');

-- Thêm sản phẩm
INSERT INTO products (category_id, name, slug, description, price, sale_price, image, stock) VALUES
(1, 'Áo Thể Thao Nam Nike Dri-FIT', 'ao-the-thao-nam-nike-dri-fit', 'Áo thể thao nam Nike Dri-FIT công nghệ thấm hút mồ hôi, co giãn tốt', 599000, 499000, 'assets/images/products/ao-nike-1.jpg', 50),
(1, 'Áo Thể Thao Nữ Adidas Climalite', 'ao-the-thao-nu-adidas-climalite', 'Áo thể thao nữ Adidas Climalite thoáng khí, form dáng đẹp', 549000, NULL, 'assets/images/products/ao-adidas-1.jpg', 30),
(1, 'Áo Thể Thao Puma Essential', 'ao-the-thao-puma-essential', 'Áo thể thao Puma Essential chất liệu cotton co giãn', 399000, 329000, 'assets/images/products/ao-puma-1.jpg', 40),
(2, 'Quần Thể Thao Nam Nike Pro', 'quan-the-thao-nam-nike-pro', 'Quần thể thao nam Nike Pro co giãn, không bị xù lông', 699000, 599000, 'assets/images/products/quan-nike-1.jpg', 35),
(2, 'Quần Thể Thao Nữ Adidas Tiro', 'quan-the-thao-nu-adidas-tiro', 'Quần thể thao nữ Adidas Tiro form slim, thoải mái khi vận động', 649000, NULL, 'assets/images/products/quan-adidas-1.jpg', 28),
(2, 'Quần Short Thể Thao Puma', 'quan-short-the-thao-puma', 'Quần short thể thao Puma dài đến gối, thoáng mát', 349000, 299000, 'assets/images/products/quan-puma-1.jpg', 45),
(3, 'Giày Chạy Bộ Nike Air Max', 'giay-chay-bo-nike-air-max', 'Giày chạy bộ Nike Air Max đệm khí, êm ái khi chạy', 2999000, 2499000, 'assets/images/products/giay-nike-1.jpg', 20),
(3, 'Giày Thể Thao Adidas Ultraboost', 'giay-the-thao-adidas-ultraboost', 'Giày thể thao Adidas Ultraboost công nghệ Boost', 3499000, NULL, 'assets/images/products/giay-adidas-1.jpg', 15),
(3, 'Giày Tập Gym Puma Speed', 'giay-tap-gym-puma-speed', 'Giày tập gym Puma Speed đế chống trượt', 1999000, 1699000, 'assets/images/products/giay-puma-1.jpg', 25),
(4, 'Mũ Thể Thao Nike Swoosh', 'mu-the-thao-nike-swoosh', 'Mũ thể thao Nike Swoosh che nắng tốt', 399000, 349000, 'assets/images/products/mu-nike-1.jpg', 60),
(4, 'Túi Thể Thao Adidas', 'tui-the-thao-adidas', 'Túi thể thao Adidas đa ngăn, tiện lợi', 899000, 799000, 'assets/images/products/tui-adidas-1.jpg', 30),
(4, 'Bình Nước Thể Thao Puma', 'binh-nuoc-the-thao-puma', 'Bình nước thể thao Puma 750ml, chống rò rỉ', 249000, NULL, 'assets/images/products/binh-puma-1.jpg', 80);

-- Tạo admin mặc định (username: admin, password: admin123)
-- Nếu password không đúng, chạy file setup.php để tạo hash mới hoặc reset password trong phpMyAdmin
INSERT INTO users (username, email, password, fullname, role) VALUES
('admin', 'admin@sportsclothing.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Administrator', 'admin');
-- Password mặc định: admin123
-- Để tạo hash mới: chạy file setup.php hoặc dùng: echo password_hash('admin123', PASSWORD_DEFAULT);

