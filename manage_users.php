<?php
session_start();
require 'dbconnect.php';


if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}


$currentUserId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id != $currentUserId ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">👥 จัดการผู้ใช้</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>อีเมล</th>
                    <th>ประเภทผู้ใช้</th>
                    <th>วันที่สร้าง</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <span class="badge <?= $row['user_type'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                <?= $row['user_type'] ?>
                            </span>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                            <a href="delete_user.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('แน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?')"
                               class="btn btn-danger btn-sm">ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">ยังไม่มีผู้ใช้งานในระบบ</div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <a href="AdminDashboard.php" class="btn btn-secondary">⬅️ กลับแดชบอร์ด</a>
    </div>
</div>
</body>
</html>
