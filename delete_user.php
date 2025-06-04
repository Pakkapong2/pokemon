<?php
session_start();
require 'dbconnect.php';


if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}


if (!isset($_GET['id'])) {
    echo "<script>alert('ไม่พบผู้ใช้ที่ต้องการลบ'); window.location='manage_users.php';</script>";
    exit();
}

$user_id = intval($_GET['id']);


if ($_SESSION['user_id'] == $user_id) {
    echo "<script>alert('คุณไม่สามารถลบบัญชีของตัวเองได้'); window.location='manage_users.php';</script>";
    exit();
}


$query = "DELETE FROM users WHERE id = $user_id";
if (mysqli_query($conn, $query)) {
    echo "<script>alert('ลบผู้ใช้เรียบร้อยแล้ว'); window.location='manage_users.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการลบผู้ใช้'); window.location='manage_users.php';</script>";
}
?>
