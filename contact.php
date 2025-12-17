<?php
$pageTitle = 'Liên hệ';
require_once 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Liên hệ với chúng tôi</h1>
        <div class="contact-page">
            <div class="contact-info">
                <h2>Thông tin liên hệ</h2>
                <div class="contact-item">
                    <strong>Địa chỉ:</strong>
                    <p>123 Đường ABC, Quận XYZ, TP.HCM</p>
                </div>
                <div class="contact-item">
                    <strong>Hotline:</strong>
                    <p>1900 1234</p>
                </div>
                <div class="contact-item">
                    <strong>Email:</strong>
                    <p>info@sportsstore.com</p>
                </div>
                <div class="contact-item">
                    <strong>Giờ làm việc:</strong>
                    <p>Thứ 2 - Chủ nhật: 8:00 - 22:00</p>
                </div>
            </div>
            <div class="contact-form-section">
                <h2>Gửi tin nhắn</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label>Họ và tên:</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Tin nhắn:</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

