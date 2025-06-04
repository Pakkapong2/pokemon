<?php
session_start();
require 'dbconnect.php';


if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}


if (!isset($_GET['id'])) {
    echo "<script>alert('ไม่พบผู้ใช้งานที่ต้องการแก้ไข'); window.location='manage_users.php';</script>";
    exit();
}

$user_id = intval($_GET['id']);
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้'); window.location='manage_users.php';</script>";
    exit();
}

$user = mysqli_fetch_assoc($result);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);

    $updateQuery = "UPDATE users SET username='$username', email='$email', user_type='$user_type' WHERE id=$user_id";
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ'); window.location='manage_users.php';</script>";
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">✏️ แก้ไขข้อมูลผู้ใช้</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ชื่อผู้ใช้</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($user['username']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">ประเภทผู้ใช้</label>
            <select name="user_type" class="form-select" required>
                <option value="customer" <?= $user['user_type'] === 'customer' ? 'selected' : '' ?>>ลูกค้า</option>
                <option value="admin" <?= $user['user_type'] === 'admin' ? 'selected' : '' ?>>แอดมิน</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">💾 บันทึกการเปลี่ยนแปลง</button>
        <a href="manage_users.php" class="btn btn-secondary">↩️ ย้อนกลับ</a>
    </form>
</div>
</body>
</html>
