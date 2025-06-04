<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// ถ้ามีการกดบันทึกการเปลี่ยนแปลง
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, tel = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $tel, $address, $user_id);
    if ($stmt->execute()) {
        $success = "บันทึกข้อมูลสำเร็จ";
    } else {
        $error = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }
}

// ดึงข้อมูลล่าสุดของผู้ใช้
$stmt = $conn->prepare("SELECT name, email, tel, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขโปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffcb05, #3b4cca);
            min-height: 100vh;
        }

        .card {
            background-color: #ffffffdd;
            border: none;
        }

        .card h3 {
            color: #3b4cca;
            font-weight: bold;
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #3b4cca;
            border-color: #3b4cca;
        }

        .btn-primary:hover {
            background-color: #2c3a9f;
            border-color: #2c3a9f;
        }

        .btn-outline-secondary:hover {
            background-color: #ffe600;
            color: #000;
        }

        .alert-success,
        .alert-danger {
            border-radius: 1rem;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow rounded-4">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-center">👤 โปรไฟล์ของคุณ</h3>

                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i><?= $success ?></div>
                        <?php elseif (isset($error)): ?>
                            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= $error ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">ชื่อผู้ใช้</label>
                                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">อีเมล</label>
                                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($user['tel']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ที่อยู่</label>
                                <textarea name="address" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>บันทึกการเปลี่ยนแปลง</button>
                                <a href="chang_password.php" class="btn btn-outline-secondary"><i class="bi bi-key-fill me-1"></i>เปลี่ยนรหัสผ่าน</a>
                                <a href="Home.php" class="btn btn-secondary"><i class="bi bi-house-door-fill me-1"></i>กลับหน้าแรก</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
