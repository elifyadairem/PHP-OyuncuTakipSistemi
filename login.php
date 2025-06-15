<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

   $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");


    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "yanlış şifre.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Portalı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap');

        body {
            background: linear-gradient(135deg,rgb(225, 165, 25),rgb(25, 110, 35), #24243e);
            font-family: 'Orbitron', sans-serif;
            color: #fff;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            position: relative;
            background:rgb(116, 116, 130);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 20px #9c27b0, 0 0 40px rgb(243, 145, 33);
            animation: floatBox 3s ease-in-out infinite;
            z-index: 1;
        }

        .login-box::before {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg,rgb(245, 114, 0), #e91e63, #9c27b0, #00e5ff);
            z-index: -1;
            border-radius: 22px;
            filter: blur(8px);
        }

        @keyframes floatBox {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        h2 {
            text-align: center;
            color: #00e5ff;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        label {
            color:rgb(236, 236, 239);
        }

        .form-control {
            background-color:rgb(89, 38, 38);
            border: 1px solid #00e5ff;
            color: #fff;
        }

        .form-control:focus {
            background-color:rgb(244, 102, 8);
            border-color: #e91e63;
            box-shadow: 0 0 10px #e91e63;
        }

        .btn-glow {
            background: linear-gradient(to right, #2196f3, #e91e63);
            border: none;
            color: white;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            margin-top: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            background: linear-gradient(to right, #e91e63, #2196f3);
            box-shadow: 0 0 15px #e91e63;
            transform: scale(1.03);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #00e5ff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>GAMER PORTAL</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="username">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="off">
            </div>
            <button type="submit" class="btn-glow">Giriş Yap</button>
        </form>
        <div class="register-link">
            Hesabın yok mu? <a href="register.php">Kayıt Ol</a>
        </div>
    </div>
</body>
</html>
