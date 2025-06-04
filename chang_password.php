<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($current_password, $user['password'])) {
        $error = "รหัสผ่านเดิมไม่ถูกต้อง";
    } elseif ($new_password !== $confirm_password) {
        $error = "รหัสผ่านใหม่และยืนยันไม่ตรงกัน";
    } else {
        
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_password, $user_id);
        if ($update_stmt->execute()) {
            $success = "เปลี่ยนรหัสผ่านเรียบร้อยแล้ว";
        } else {
            $error = "เกิดข้อผิดพลาดในการอัปเดต";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เปลี่ยนรหัสผ่าน</title>
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

        .card h4 {
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
        }

        .btn-secondary:hover {
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
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4">🔐 เปลี่ยนรหัสผ่าน</h4>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i><?= $success ?></div>
                    <?php elseif (isset($error)): ?>
                        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= $error ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่านเดิม</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-shield-lock-fill me-1"></i>เปลี่ยนรหัสผ่าน</button>
                            <a href="profile.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle-fill me-1"></i>ย้อนกลับ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
