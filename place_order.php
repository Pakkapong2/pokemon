<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['address']) || trim($_POST['address']) === '') {
    echo "<script>alert('กรุณากรอกที่อยู่จัดส่ง'); window.location='checkout.php';</script>";
    exit;
}
$address = trim($_POST['address']);

// ดึง cart id
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
$cart = $cart_result->fetch_assoc();

if (!$cart) {
    echo "<script>alert('ไม่พบตะกร้าสินค้า'); window.location='product.php';</script>";
    exit;
}
$cart_id = $cart['id'];

// ดึงสินค้าในตะกร้า
$stmt = $conn->prepare("SELECT product_id, quantity FROM cart_items WHERE cart_id = ?");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$items_result = $stmt->get_result();

if ($items_result->num_rows === 0) {
    echo "<script>alert('ไม่มีสินค้าในตะกร้า'); window.location='product.php';</script>";
    exit;
}

$total_price = 0;
$items = [];

while ($item = $items_result->fetch_assoc()) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    // เช็คราคาและ stock
    $stmt_product = $conn->prepare("SELECT price, stock FROM products WHERE id = ?");
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $product_result = $stmt_product->get_result();
    $product = $product_result->fetch_assoc();

    if (!$product) {
        echo "<script>alert('ไม่พบสินค้าบางรายการ'); window.location='cart.php';</script>";
        exit;
    }

    if ($product['stock'] < $quantity) {
        echo "<script>alert('สินค้า {$product_id} มีไม่พอในสต๊อก'); window.location='cart.php';</script>";
        exit;
    }

    $total_price += $product['price'] * $quantity;
    $items[] = ['product_id' => $product_id, 'quantity' => $quantity];
}

// เริ่ม transaction
$conn->begin_transaction();

try {
    // เพิ่ม order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, address, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ids", $user_id, $total_price, $address);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // เตรียม statement ล่วงหน้า
    $stmt_insert = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($items as $item) {
        // เพิ่มรายการสินค้าลง order_items
        $stmt_insert->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
        $stmt_insert->execute();

        // หัก stock สินค้า
        $stmt_stock->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt_stock->execute();
    }

    // ล้างตะกร้า
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    $conn->commit();

    header("Location: order_success.php");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('เกิดข้อผิดพลาดในการสั่งซื้อ: " . $e->getMessage() . "'); window.location='checkout.php';</script>";
    exit;
}
?>
