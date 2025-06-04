<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffcb05, #3b4cca);
        }

        .login-card {
            background-color: #ffffffcc;
            border: none;
            border-radius: 16px;
        }

        .pokemon-logo {
            width: 60px;
            display: block;
            margin: 0 auto 10px auto;
        }

        .btn-pokemon {
            background-color: #3b4cca;
            color: #fff;
            font-weight: bold;
        }

        .btn-pokemon:hover {
            background-color: #2a32a0;
        }
    </style>
</head>

<body class="d-flex align-items-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow login-card p-4">
                    <div class="text-center">
                        <img src="pictures/pokeball.png" alt="Pokeball" class="pokemon-logo">
                        <h4 class="mb-4 fw-bold">เข้าสู่ระบบ</h4>
                    </div>
                    <form action="check_login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-pokemon">เข้าสู่ระบบ</button>
                        </div>
                        <div class="text-center mt-3">
                            <a href="register.php" class="text-decoration-none text-primary">ยังไม่มีบัญชี? สมัครสมาชิก</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>