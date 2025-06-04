<?php
session_start();
require('dbconnect.php');

$username = $_POST['username'];
$password = $_POST['password'];


$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_type'] = $row['user_type']; 

        
        if ($row['user_type'] == 'admin') {
            header("Location: AdminDashboard.php");
        } else {
            header("Location: Home.php");
        }
        exit();
    } else {
        echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.location='Login.php';</script>";
    }
} else {
    echo "<script>alert('ไม่พบบัญชีผู้ใช้'); window.location='Login.php';</script>";
}
?>
