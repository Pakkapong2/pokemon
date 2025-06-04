<?php
session_start();
require('dbconnect.php');

// ตรวจสอบว่าเข้าสู่ระบบแล้ว
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}

// รับค่า cart_item_id จาก URL
if (!isset($_GET['item_id'])) {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการลบ'); window.history.back();</script>";
    exit;
}

$cart_item_id = intval($_GET['item_id']);

// ลบรายการออกจากตะกร้า
$stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ?");
$stmt->bind_param("i", $cart_item_id);
if ($stmt->execute()) {
    echo "<script>alert('ลบสินค้าสำเร็จ'); window.location='cart.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการลบสินค้า'); window.history.back();</script>";
}
?>
