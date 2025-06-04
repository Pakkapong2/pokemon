<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Navbar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    .navbar-pokemon {
      background: linear-gradient(90deg, #ffcb05, #3b4cca);
    }

    .navbar-pokemon .nav-link {
      color: white;
      font-weight: 500;
      transition: 0.3s;
    }

    .navbar-pokemon .nav-link:hover,
    .navbar-pokemon .nav-link:focus {
      color: #ffe600;
      text-shadow: 0 0 5px rgba(255, 255, 255, 0.6);
    }

    .dropdown-menu {
      background-color: #f0f0f0;
    }

    .dropdown-menu a:hover {
      background-color: #ffe600;
    }

    .cart-icon {
      position: relative;
    }

    .cart-badge {
      position: absolute;
      top: -5px;
      right: -10px;
      background: red;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-pokemon sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="Home.php">
        <img src="pictures/pokeball.png" alt="Logo" style="width: 40px;" class="rounded-pill">
      </a>

      <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <!-- 🔍 ช่องค้นหา -->
          <form class="d-flex" role="search" autocomplete="off">
            <div class="position-relative">
              <input class="form-control me-2" type="search" placeholder="ค้นหา..." id="searchBox">
              <div id="suggestBox" class="list-group position-absolute w-100 z-3"></div>
            </div>
          </form>

          <!-- เมนูแยกตามประเภทผู้ใช้ -->
          <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
            <!-- เมนูสำหรับแอดมิน -->
            <li class="nav-item">
              <a class="nav-link" href="AdminDashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="manage_users.php"><i class="bi bi-people-fill me-1"></i>จัดการผู้ใช้</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="manage_products.php"><i class="bi bi-box-fill me-1"></i>จัดการสินค้า</a>
            </li>
          <?php else: ?>
            <!-- เมนูสำหรับลูกค้า -->
            <li class="nav-item">
              <a class="nav-link" href="Home.php"><i class="bi bi-house-door-fill me-1"></i>Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Product.php"><i class="bi bi-bag-fill me-1"></i>Product</a>
            </li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav ms-auto">
          <!-- 🛒 ตะกร้า (เฉพาะลูกค้า) -->
          <?php if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin'): ?>
            <li class="nav-item me-2">
              <a class="nav-link position-relative" href="cart.php">
                <i class="bi bi-cart-fill fs-5"></i>
                <span class="visually-hidden">Cart</span>
                <?php if (!empty($_SESSION['cart'])): ?>
                  <span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
                <?php endif; ?>
              </a>
            </li>
          <?php endif; ?>

          <!-- 👤 Dropdown User -->
          <?php if (isset($_SESSION['username'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo htmlspecialchars($_SESSION['username']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                  <li><a class="dropdown-item" href="AdminDashboard.php"><i class="bi bi-speedometer2 me-1"></i>แดชบอร์ด</a></li>
                <?php else: ?>
                  <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-fill me-1"></i>โปรไฟล์</a></li>
                <?php endif; ?>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="Login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
            </li>
          <?php endif; ?>
        </ul>


      </div>
    </div>
  </nav>
</body>

</html>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const searchBox = document.getElementById("searchBox");
    const suggestBox = document.getElementById("suggestBox");

    searchBox.addEventListener("input", () => {
      const query = searchBox.value.trim();
      if (query.length === 0) {
        suggestBox.innerHTML = '';
        return;
      }

      fetch(`suggest.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
          suggestBox.innerHTML = '';
          if (data.length > 0) {
            data.forEach(item => {
              const option = document.createElement('a');
              option.href = `search.php?q=${encodeURIComponent(item.name)}`;
              option.className = 'list-group-item list-group-item-action d-flex align-items-center';
              option.innerHTML = `
              <img src="pictures/${item.image}" alt="${item.name}" class="me-2 rounded" style="width: 30px; height: 30px; object-fit: cover;">
              <span>${item.name}</span>
            `;
              suggestBox.appendChild(option);
            });
          }
        });
    });

    // ซ่อนผลลัพธ์เมื่อคลิกนอกช่อง
    document.addEventListener("click", (e) => {
      if (!searchBox.contains(e.target)) {
        suggestBox.innerHTML = '';
      }
    });
  });
</script>