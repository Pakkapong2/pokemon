<?php
session_start();
require 'dbconnect.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location='Login.php';</script>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $allowed = ['pending', 'confirmed', 'shipped', 'delivered', 'canceled'];
    if (in_array($status, $allowed)) {
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
    }
}


$order_items_result = $conn->query("
    SELECT oi.order_id, p.name AS product_name, oi.quantity 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id
");

$order_items_by_order = [];
if ($order_items_result) {
    while ($item = $order_items_result->fetch_assoc()) {
        $order_id = $item['order_id'];
        $order_items_by_order[$order_id][] = $item;
    }
}


$sql = "
    SELECT orders.id, orders.status, orders.created_at, users.username 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    ORDER BY orders.id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการคำสั่งซื้อ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4 text-center">📦 จัดการคำสั่งซื้อ</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-secondary">
                    <tr>
                        <th>หมายเลข</th>
                        <th>ชื่อลูกค้า</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>สถานะ</th>
                        <th>เปลี่ยนสถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <?php
                                $status_colors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'primary',
                                    'shipped' => 'info',
                                    'delivered' => 'success',
                                    'canceled' => 'danger'
                                ];
                                $badge = $status_colors[$row['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $row['status'] ?></span>
                            </td>
                            <td>
                                <form method="POST" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <?php foreach (['pending', 'confirmed', 'shipped', 'delivered', 'canceled'] as $status): ?>
                                            <option value="<?= $status ?>" <?= $row['status'] === $status ? 'selected' : '' ?>>
                                                <?= ucfirst($status) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-success">บันทึก</button>
                                </form>
                            </td>
                        </tr>

                       
                        <?php if (!empty($order_items_by_order[$row['id']])): ?>
                            <tr>
                                <td colspan="5" class="bg-light">
                                    <strong>📋 รายการสินค้า:</strong>
                                    <ul class="mb-0">
                                        <?php foreach ($order_items_by_order[$row['id']] as $item): ?>
                                            <li><?= htmlspecialchars($item['product_name']) ?> × <?= $item['quantity'] ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </tbody>

            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">ยังไม่มีคำสั่งซื้อ</div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="AdminDashboard.php" class="btn btn-secondary">⬅️ กลับแดชบอร์ด</a>
        </div>
    </div>
</body>

</html>