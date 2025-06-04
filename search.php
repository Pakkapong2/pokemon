<?php
// เริ่ม session และเชื่อมต่อฐานข้อมูล
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$conn = new mysqli("localhost", "root", "", "pokemon");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่าค้นหาจาก URL
$search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ผลการค้นหา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include("navbar.php"); // รวม navbar ที่คุณใช้ ?>

<div class="container mt-4">
    <h3>ผลการค้นหา: <span class="text-primary"><?php echo htmlspecialchars($search); ?></span></h3>

    <div class="row">
        <?php
        if ($search !== '') {
            $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="pictures/<?php echo $row['picture']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text text-truncate"><?php echo $row['description']; ?></p>
                                <p class="card-text text-danger fw-bold">฿<?php echo $row['price']; ?></p>
                                <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">ดูรายละเอียด</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-muted'>ไม่พบผลลัพธ์ที่ตรงกับคำค้นหา</p>";
            }
        } else {
            echo "<p class='text-muted'>กรุณากรอกคำค้นหา</p>";
        }

        $conn->close();
        ?>
    </div>
</div>
</body>
</html>
