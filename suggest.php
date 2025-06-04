<?php
if (!isset($_GET['q'])) exit;
$q = $_GET['q'];

$conn = new mysqli("localhost", "root", "", "pokemon");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// แก้ชื่อคอลัมน์จาก image เป็น picture
$stmt = $conn->prepare("SELECT name, picture FROM products WHERE name LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("s", $q);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = [
        'name' => $row['name'],
        'image' => $row['picture'] // ใช้ picture แต่ออกไปในชื่อ image ให้ตรงกับ JS
    ];
}

header('Content-Type: application/json');
echo json_encode($suggestions);
