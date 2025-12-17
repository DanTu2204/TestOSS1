<?php
require_once 'config/session.php';
require_once 'config/database.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action === 'add') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($productId > 0 && $quantity > 0) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, name, price, sale_price, image, stock FROM products WHERE id = ? AND status = 'active'");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $conn->close();
        
        if ($product && $product['stock'] >= $quantity) {
            $finalPrice = $product['sale_price'] ? $product['sale_price'] : $product['price'];
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            if (isset($_SESSION['cart'][$productId])) {
                $newQuantity = $_SESSION['cart'][$productId]['quantity'] + $quantity;
                if ($newQuantity <= $product['stock']) {
                    $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
                }
            } else {
                $_SESSION['cart'][$productId] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $finalPrice,
                    'quantity' => $quantity,
                    'image' => $product['image']
                ];
            }
            $_SESSION['success'] = 'Đã thêm sản phẩm vào giỏ hàng!';
        } else {
            $_SESSION['error'] = 'Sản phẩm không khả dụng hoặc không đủ số lượng!';
        }
    }
    header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'cart.php'));
    exit;
} elseif ($action === 'update') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if ($productId > 0) {
        if ($quantity > 0 && isset($_SESSION['cart'][$productId])) {
            // Kiểm tra stock
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $conn->close();
            
            if ($product && $quantity <= $product['stock']) {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
            } else {
                $_SESSION['error'] = 'Số lượng vượt quá tồn kho!';
            }
        } elseif ($quantity == 0) {
            unset($_SESSION['cart'][$productId]);
        }
    }
    header('Location: cart.php');
    exit;
} elseif ($action === 'remove') {
    $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
    if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng!';
    }
    header('Location: cart.php');
    exit;
} elseif ($action === 'clear') {
    $_SESSION['cart'] = [];
    $_SESSION['success'] = 'Đã xóa toàn bộ giỏ hàng!';
    header('Location: cart.php');
    exit;
}

header('Location: cart.php');
exit;
?>

