<?php require('dbconnect.php');
$sql = 'SELECT * FROM products WHERE stock > 0 ORDER BY RAND() LIMIT 3';
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pokémon Shop - หน้าแรก</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    .card-title {
      font-weight: bold;
    }

    .btn-cart {
      width: 100%;
      font-size: 0.9rem;
    }

    .carousel-inner img {
      object-fit: contain;
      height: 500px;
    }

    @media (max-width: 576px) {
      .carousel-inner img {
        height: 250px;
      }
    }
  </style>
</head>

<body>
  <?php include 'Navbar.php'; ?>

  <div id="carousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="pictures/pokemon.png" class="d-block w-100" alt="Slide 1">
      </div>
      <div class="carousel-item">
        <img src="pictures/pokeball.webp" class="d-block w-100" alt="Slide 2">
      </div>
      <div class="carousel-item">
        <img src="pictures/potion.webp" class="d-block w-100" alt="Slide 3">
      </div>
    </div>
  </div>

  <h1 class="text-center mb-4">สินค้าแนะนำ</h1>
  <div class="container">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="col">
          <div class="card h-100">
            <img src="pictures/<?php echo htmlspecialchars($row['picture']); ?>"
              onerror="this.src='pictures/no-image.png';"
              class="card-img-top" alt="No Picture" style="height: 300px; object-fit: contain;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-truncate"><?php echo htmlspecialchars($row['name']); ?></h5>
              <p class="card-text"><?php echo mb_substr(htmlspecialchars($row['description']), 0, 100) . '...'; ?></p>

              <div class="mt-auto">
                <p class="text-muted mb-2">
                  คงเหลือ: <?php echo $row['stock']; ?> ชิ้น |
                  ฿<?php echo number_format($row['price'], 2); ?>
                </p>

                <?php if (isset($row['stock']) && $row['stock'] == 0) { ?>
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

    <div class="text-center mt-4">
      <a href="product.php" class="btn btn-primary btn-lg">
        ดูสินค้าทั้งหมด
      </a>
    </div>
  </div>

  <?php include 'footer.php'; ?>
</body>

</html>