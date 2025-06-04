<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='Login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
$cart = $cart_result->fetch_assoc();

if (!$cart) {
    echo "<script>alert('ไม่พบตะกร้าสินค้า'); window.location='product.php';</script>";
    exit;
}

$cart_id = $cart['id'];


$stmt = $conn->prepare("
    SELECT p.name, p.price, ci.quantity
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.cart_id = ?
");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$items_result = $stmt->get_result();

$total_price = 0;
$items = [];

while ($row = $items_result->fetch_assoc()) {
    $item_total = $row['price'] * $row['quantity'];
    $total_price += $item_total;
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">🧾 ตรวจสอบคำสั่งซื้อ</h2>

        <?php if (empty($items)): ?>
            <div class="alert alert-warning text-center">ไม่มีสินค้าในตะกร้า</div>.<div class="mt-3">
        <a href="product.php" class="btn btn-secondary">
            ⬅️ กลับไปเลือกสินค้า
        </a>
    </div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>สินค้า</th>
                        <th>ราคา</th>
                        <th>จำนวน</th>
                        <th>รวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price'], 2) ?> บาท</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price'] * $item['quantity'], 2) ?> บาท</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>รวมทั้งสิ้น:</strong></td>
                        <td><strong><?= number_format($total_price, 2) ?> บาท</strong></td>
                    </tr>
                </tfoot>
            </table>

            <form action="place_order.php" method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="address" class="form-label">📮 ที่อยู่จัดส่ง</label>
                    <textarea name="address" id="address" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">✅ ยืนยันการสั่งซื้อ</button>
                <a href="cart.php" class="btn btn-secondary">🔙 กลับไปยังตะกร้า</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
