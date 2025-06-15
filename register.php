<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formdan gelen veriler
    $fullname = trim($_POST['fullname']);
    $birthdate = $_POST['birthdate'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Şifreyi güvenli şekilde hashle
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Hazırlanmış sorgu (SQL ile uyumlu)
   $stmt = $conn->prepare("INSERT INTO users (fullname, birth_date, username, email, password_hash) VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sssss", $fullname, $birthdate, $username, $email, $password_hash);

    if ($stmt->execute()) {
        header("Location: login.php"); // Başarılıysa giriş sayfasına yönlendir
        exit();
    } else {
        echo "Kayıt başarısız: " . $stmt->error;
    }
}
?>



<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Oyuncu Kayıt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at top left, #111, #000);
      height: 100vh;
      margin: 0;
      font-family: 'Orbitron', sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    .border-effect {
      position: absolute;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }

    .border-effect::before,
    .border-effect::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 4px;
      background: linear-gradient(to right, orange, lime);
      animation: moveX 4s linear infinite;
    }

    .border-effect::after {
      top: auto;
      bottom: 0;
      animation-direction: reverse;
    }

    @keyframes moveX {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .border-effect-side::before,
    .border-effect-side::after {
      content: '';
      position: absolute;
      height: 100%;
      width: 4px;
      background: linear-gradient(to bottom, lime, orange);
      animation: moveY 5s linear infinite;
    }

    .border-effect-side::after {
      left: auto;
      right: 0;
      animation-direction: reverse;
    }

    @keyframes moveY {
      0% { transform: translateY(-100%); }
      100% { transform: translateY(100%); }
    }

    .register-card {
      background: rgba(0, 0, 0, 0.85);
      border: 2px solid #1aff00;
      border-radius: 20px;
      padding: 30px 40px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 0 25px 5px #00ff99;
      z-index: 2;
      position: relative;
    }

    .register-card h2 {
      color: #00ffcc;
      text-align: center;
      margin-bottom: 25px;
      text-shadow: 0 0 10px #00ffcc;
    }

    .form-control {
      background-color: #111;
      border: 1px solid #444;
      color: #fff;
    }

    .form-control:focus {
      border-color: orange;
      box-shadow: 0 0 10px orange;
    }

    .btn-glow {
      background: linear-gradient(to right, lime, orange);
      border: none;
      color: #000;
      font-weight: bold;
      text-shadow: none;
      box-shadow: 0 0 15px #00ff66;
      transition: 0.3s ease;
    }

    .btn-glow:hover {
      box-shadow: 0 0 25px #ffaa00, 0 0 45px #ffaa00;
      transform: scale(1.05);
    }

    a {
      color: #ffaa00;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="border-effect"></div>
  <div class="border-effect-side"></div>

  <div class="register-card">
    <h2>Oyuncu Kayıt</h2>
  <form method="POST" action="register.php">
    <label for="fullname">Ad Soyad:</label>
    <input type="text" id="fullname" name="fullname" required><br>

    <label for="birthdate">Doğum Tarihi:</label>
    <input type="date" id="birthdate" name="birthdate" required><br>

    <label for="username">Kullanıcı Adı:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Şifre:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">Kayıt Ol</button>
</form>

    <p class="text-center mt-3">Zaten hesabın var mı? <a href="login.php">Giriş Yap</a></p>
  </div>
</body>
</html>
