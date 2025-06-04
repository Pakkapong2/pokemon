<?php
session_start();
require 'dbconnect.php';


if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ไม่พบรหัสสินค้าที่ต้องการลบ'); window.location='manage_products.php';</script>";
    exit();
}

$id = intval($_GET['id']);


$sql = "SELECT picture FROM products WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการลบ'); window.location='manage_products.php';</script>";
    exit();
}

$product = $result->fetch_assoc();
$picturePath = 'pictures/' . $product['picture'];

$deleteSQL = "DELETE FROM products WHERE id = $id";
if ($conn->query($deleteSQL)) {
   
    if (!empty($product['picture']) && file_exists($picturePath)) {
        unlink($picturePath);
    }

    echo "<script>alert('ลบสินค้าสำเร็จ'); window.location='manage_products.php';</script>";
    exit();
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการลบสินค้า'); window.location='manage_products.php';</script>";
    exit();
}
?>
