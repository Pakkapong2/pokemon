<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการแก้ไข'); window.location='manage_products.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $id");

if ($result->num_rows === 0) {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการแก้ไข'); window.location='manage_products.php';</script>";
    exit();
}

$product = $result->fetch_assoc();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $newPicture = $_FILES['picture'];
    $stock = intval($_POST['stock']);

    if ($name === '' || $price <= 0 || $stock < 0) {
        $errors[] = "กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง";
    }


    if ($name === '' || $price <= 0) {
        $errors[] = "กรุณากรอกข้อมูลให้ครบถ้วน";
    } else {

        if ($newPicture['error'] === 0) {
            $newPictureName = uniqid() . '_' . basename($newPicture['name']);
            $uploadPath = 'pictures/' . $newPictureName;

            if (move_uploaded_file($newPicture['tmp_name'], $uploadPath)) {

                if (!empty($product['picture']) && file_exists('pictures/' . $product['picture'])) {
                    unlink('pictures/' . $product['picture']);
                }

                $pictureToUpdate = $newPictureName;
            } else {
                $errors[] = "ไม่สามารถอัปโหลดรูปภาพได้";
            }
        } else {
            $pictureToUpdate = $product['picture'];
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, picture = ?, stock = ? WHERE id = ?");
            $stmt->bind_param("ssdsii", $name, $description, $price, $pictureToUpdate, $stock, $id);


            if ($stmt->execute()) {
                header("Location: manage_products.php");
                exit();
            } else {
                $errors[] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">✏️ แก้ไขสินค้า</h2>

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
                <label class="form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รายละเอียด</label>
                <textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">ราคา (บาท)</label>
                <input type="number" step="0.01" class="form-control" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">จำนวนสินค้า (Stock)</label>
                <input type="number" class="form-control" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" min="0" required>
            </div>

            <div class="mb-3">
                <label class="form-label">รูปปัจจุบัน</label><br>
                <?php if (!empty($product['picture']) && file_exists('pictures/' . $product['picture'])): ?>
                    <img src="pictures/<?= htmlspecialchars($product['picture']) ?>" alt="Current Image" style="width: 150px;" class="rounded mb-2">
                <?php else: ?>
                    <p>ไม่มีรูป</p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">เลือกรูปใหม่ (หากต้องการเปลี่ยน)</label>
                <input type="file" class="form-control" name="picture" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">💾 บันทึกการแก้ไข</button>
            <a href="manage_products.php" class="btn btn-secondary">ย้อนกลับ</a>
        </form>
    </div>
</body>

</html>