<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สั่งซื้อสำเร็จ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container text-center py-5">
    <h1 class="text-success mb-4">🎉 สั่งซื้อสำเร็จ!</h1>
    <p class="lead">ขอบคุณสำหรับการสั่งซื้อของคุณ</p>
    <p>ระบบได้บันทึกคำสั่งซื้อเรียบร้อยแล้ว</p>
    <div class="mt-4">
        <a href="product.php" class="btn btn-primary">🔙 กลับไปหน้าเลือกสินค้า</a>
        <a href="orders.php" class="btn btn-outline-secondary">📦 ดูรายการสั่งซื้อของฉัน</a>
    </div>
</body>

</html>
