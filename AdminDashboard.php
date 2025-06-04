<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-card {
            border-radius: 1rem;
            transition: 0.3s;
        }
        .admin-card:hover {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4 text-center">📊 แผงควบคุมผู้ดูแลระบบ</h2>
        <p class="text-center">👤 ยินดีต้อนรับคุณ <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

        <div class="row mt-4">
            
            <div class="col-md-4">
                <div class="card admin-card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">👥 จัดการผู้ใช้</h5>
                        <p class="card-text">เพิ่ม ลบ แก้ไขข้อมูลผู้ใช้งาน</p>
                        <a href="manage_users.php" class="btn btn-primary">ไปยังเมนู</a>
                    </div>
                </div>
            </div>

           
            <div class="col-md-4">
                <div class="card admin-card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">🛒 จัดการสินค้า</h5>
                        <p class="card-text">เพิ่ม ลบ แก้ไขรายการสินค้า</p>
                        <a href="manage_products.php" class="btn btn-success">ไปยังเมนู</a>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card admin-card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">📦 คำสั่งซื้อ</h5>
                        <p class="card-text">ดูและจัดการคำสั่งซื้อของลูกค้า</p>
                        <a href="manage_orders.php" class="btn btn-warning">ไปยังเมนู</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="Home.php" class="btn btn-secondary">🏠 กลับหน้าเว็บไซต์</a>
        </div>
    </div>
</body>
</html>
