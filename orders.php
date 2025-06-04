<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT id, total_price, address, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประวัติการสั่งซื้อ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4 text-center">📦 ประวัติการสั่งซื้อ</h2>

        <?php if ($result->num_rows === 0): ?>
            <div class="alert alert-info text-center">
                ไม่มีคำสั่งซื้อในระบบ
            </div>
        <?php else: ?>
            <?php while ($order = $result->fetch_assoc()): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">คำสั่งซื้อ #<?= $order['id'] ?></h5>
                        <p class="card-text">
                            <strong>วันที่สั่งซื้อ:</strong> <?= $order['created_at'] ?><br>
                            <strong>ที่อยู่จัดส่ง:</strong> <?= htmlspecialchars($order['address']) ?>
                        </p>

                        <!-- รายการสินค้า -->
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>สินค้า</th>
                                    <th>ราคา</th>
                                    <th>จำนวน</th>
                                    <th>รวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt_items = $conn->prepare("
                                    SELECT oi.quantity, p.name, p.price
                                    FROM order_items oi
                                    JOIN products p ON oi.product_id = p.id
                                    WHERE oi.order_id = ?
                                ");
                                $stmt_items->bind_param("i", $order['id']);
                                $stmt_items->execute();
                                $items_result = $stmt_items->get_result();
                                $subtotal = 0;

                                while ($item = $items_result->fetch_assoc()):
                                    $item_total = $item['price'] * $item['quantity'];
                                    $subtotal += $item_total;
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= number_format($item['price'], 2) ?> บาท</td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><?= number_format($item_total, 2) ?> บาท</td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>รวมทั้งสิ้น:</strong></td>
                                    <td><strong><?= number_format($subtotal, 2) ?> บาท</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="product.php" class="btn btn-primary">🔙 กลับไปเลือกสินค้า</a>
        </div>
    </div>
</body>
</html>
