<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['product_id'])) {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการเพิ่ม'); window.history.back();</script>";
    exit;
}

$product_id = intval($_GET['product_id']);


$stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('ไม่พบสินค้าในระบบ'); window.history.back();</script>";
    exit;
}

$stock = $product['stock'];

if ($stock <= 0) {
    echo "<script>alert('สินค้าหมด'); window.history.back();</script>";
    exit;
}


$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart = $result->fetch_assoc();

if (!$cart) {
    $stmt = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_id = $stmt->insert_id;
} else {
    $cart_id = $cart['id'];
}


$stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
$stmt->bind_param("ii", $cart_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

$current_quantity = $item ? $item['quantity'] : 0;


if ($current_quantity + 1 > $stock) {
    echo "<script>alert('ไม่สามารถเพิ่มสินค้าเกินจำนวนที่มีในสต๊อกได้'); window.location='cart.php';</script>";
    exit;
}

if ($item) {
    $new_quantity = $current_quantity + 1;
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_quantity, $item['id']);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
}

echo "<script>alert('เพิ่มสินค้าลงตะกร้าแล้ว'); window.location='cart.php';</script>";
