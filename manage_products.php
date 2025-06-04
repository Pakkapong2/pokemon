<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}


require 'dbconnect.php';

$sql = "SELECT * FROM products ";
$result = mysqli_query($conn, $sql);


if (!$result) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">📦 จัดการสินค้า</h2>
    <a href="add_product.php" class="btn btn-success mb-3">➕ เพิ่มสินค้า</a>

    <table class="table table-bordered table-hover">
        <thead class="table-primary text-center">
            <tr>
                <th>#</th>
                <th>ชื่อสินค้า</th>
                <th>ราคา</th>
                <th>คงเหลือ</th>
                <th>รูป</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td class="text-end"><?= number_format($row['price'], 2) ?> ฿</td>
                        <td class="text-center"><?= $row['stock'] ?> ชิ้น</td>
                        <td class="text-center">
                            <img src="pictures/<?= $row['picture'] ?>" width="50" height="50" class="rounded" alt="รูปสินค้า">
                        </td>
                        <td class="text-center">
                            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ แก้ไข</a>
                            <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบ?')">🗑️ ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">ไม่มีข้อมูลสินค้า</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="AdminDashboard.php" class="btn btn-secondary mt-3">⬅️ กลับแดชบอร์ด</a>
</div>
</body>
</html>
