<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fdf497, #fdf497, #fd5949, #d6249f, #285AEB);
            background-size: 150% 150%;
            animation: gradientBG 8s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .register-card {
            background-color: #ffffffdd;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 460px;
            margin: 0 auto;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .btn-success {
            background-color: #3b4cca;
            border: none;
        }

        .btn-success:hover {
            background-color: #2a2fa0;
        }

        h4.card-title {
            color: #3b4cca;
            font-weight: bold;
        }

        a {
            color: #fd5949;
        }

        a:hover {
            color: #d6249f;
        }
    </style>

</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4 mx-auto">
                <div class="register-card">
                    <h4 class="card-title text-center mb-4">🐱‍🏍 สมัครสมาชิก</h4>
                    <form action="save_register.php" method="POST">
                        <div class="mb-2">
                            <label class="form-label">ชื่อ</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">นามสกุล</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="tel" class="form-control" maxlength="10" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">ที่อยู่</label>
                            <textarea name="address" class="form-control" required></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">✨ สมัครสมาชิก</button>
                        </div>
                        <p class="mt-3 text-center">มีบัญชีแล้ว? <a href="Login.php">เข้าสู่ระบบ</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>