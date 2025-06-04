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
$result = $stmt->get_result();
$cart = $result->fetch_assoc();

if (!$cart) {
    echo "<p class='text-center mt-5'>ยังไม่มีสินค้าที่เลือกไว้</p>";
    exit;
}

$cart_id = $cart['id'];


$stmt = $conn->prepare("
    SELECT ci.id, p.name, p.price, ci.quantity
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.cart_id = ?
");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ตะกร้าสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-5">
    <h3 class="mb-4">🛒 ตะกร้าสินค้าของคุณ</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>สินค้า</th>
                <th>ราคา</th>
                <th>จำนวน</th>
                <th>ราคารวม</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            while ($item = $result->fetch_assoc()):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price'], 2) ?> บาท</td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($subtotal, 2) ?> บาท</td>
                    <td>
                        <a href="remove_cart.php?item_id=<?= $item['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('คุณต้องการลบสินค้านี้หรือไม่?');">
                            ลบ
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">รวมทั้งหมด</th>
                <th><?= number_format($total, 2) ?> บาท</th>
            </tr>
        </tfoot>
    </table>
    <div class="mt-3">
        <a href="product.php" class="btn btn-secondary">
            ⬅️ กลับไปเลือกสินค้าต่อ
        </a>
        <a href="orders.php" class="btn btn-outline-dark">
            📄 ดูประวัติการสั่งซื้อ
        </a>
        <a href="checkout.php" class="btn btn-primary">
            ✅ ยืนยันการสั่งซื้อ
        </a>
    </div>

</body>

</html>