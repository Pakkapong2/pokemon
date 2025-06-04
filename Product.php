<?php require('dbconnect.php');
$sql = 'SELECT * FROM products ORDER BY stock = 0 ASC, id DESC';  // ดึงสินค้าตาม stock
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <title>สินค้า</title>
    <style>
        .out-of-stock {
            opacity: 0.6;
        }
    </style>
</head>

<body>
    <?php include 'Navbar.php'; ?>

    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col">
                    <div class="card h-100 d-flex flex-column <?php echo ($row['stock'] == 0) ? 'out-of-stock' : ''; ?>">
                        <img src="pictures/<?php echo htmlspecialchars($row['picture']); ?>" class="card-img-top" alt="No Picture" style="height: 300px; object-fit: contain;">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h4>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="mt-auto">
                                <p class="text-muted mb-2">
                                    คงเหลือ: <?php echo $row['stock']; ?> ชิ้น |
                                    ฿<?php echo number_format($row['price'], 2); ?>
                                </p>


                                <?php if ($row['stock'] == 0) { ?>
                                    <button class="btn btn-secondary btn-sm w-100" disabled>
                                        <i class="bi bi-x-circle"></i> สินค้าหมด
                                    </button>
                                <?php } else { ?>
                                    <a href="add_to_cart.php?product_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm w-100">
                                        <i class="bi bi-cart-plus"></i> เพิ่มสินค้า
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>