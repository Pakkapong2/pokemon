<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $picture = $_FILES['picture'];
    $stock = intval($_POST['stock']);

    if ($name === '' || $price <= 0 || $stock < 0 || $picture['error'] !== 0) {
        $errors[] = "กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง รวมถึงเลือกรูปภาพ";
    }


    if ($name === '' || $price <= 0 || $picture['error'] !== 0) {
        $errors[] = "กรุณากรอกข้อมูลให้ครบถ้วนและเลือกรูปภาพ";
    } else {
        $pictureName = uniqid() . '_' . basename($picture['name']);
        $uploadDir = 'pictures/';
        $uploadPath = $uploadDir . $pictureName;

        if (move_uploaded_file($picture['tmp_name'], $uploadPath)) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, picture, stock) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsi", $name, $description, $price, $pictureName, $stock);
            if ($stmt->execute()) {
                header("Location: manage_products.php");
                exit();
            } else {
                $errors[] = "เกิดข้อผิดพลาดในการเพิ่มสินค้า: " . $stmt->error;
            }
        } else {
            $errors[] = "ไม่สามารถอัปโหลดรูปภาพได้";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">➕ เพิ่มสินค้าใหม่</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">รายละเอียด</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">ราคา (บาท)</label>
                <input type="number" step="0.01" class="form-control" name="price" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">จำนวนสินค้าในคลัง (Stock)</label>
                <input type="number" class="form-control" name="stock" min="0" required>
            </div>

            <div class="mb-3">
                <label for="picture" class="form-label">เลือกรูปภาพ</label>
                <input type="file" class="form-control" name="picture" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">✅ เพิ่มสินค้า</button>
            <a href="manage_products.php" class="btn btn-secondary">ย้อนกลับ</a>
        </form>
    </div>
</body>

</html>