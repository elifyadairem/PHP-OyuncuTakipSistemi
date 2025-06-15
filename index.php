<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Oyun - Oyuncu Takip Sistemi</title>

    <!-- Google Fonts - Orbitron -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet" />

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        /* Genel ayarlar */
        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            font-family: 'Orbitron', sans-serif;
            color: #00ffea;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }

        h1 {
            font-size: 3.5rem;
            margin-bottom: 10px;
            text-shadow: 0 0 10px #00fff7;
        }

        p.slogan {
            font-size: 1.2rem;
            color: #00d1c1;
            margin-bottom: 40px;
            letter-spacing: 2px;
            text-shadow: 0 0 5px #00b8a9;
        }

        /* Buton kapsayıcısı */
        .btn-container {
            display: flex;
            gap: 40px;
        }

        /* Butonlar */
        a.button {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            background: #00ffea;
            color: #072f2f;
            font-weight: 700;
            font-size: 1.3rem;
            padding: 15px 40px;
            border-radius: 50px;
            box-shadow: 0 0 15px #00ffea88;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        a.button i {
            font-size: 1.6rem;
        }

        a.button:hover {
            background: transparent;
            color: #00ffea;
            border-color: #00ffea;
            box-shadow: 0 0 30px #00ffea;
            transform: scale(1.1);
        }

        /* Responsive */
        @media(max-width: 480px) {
            h1 {
                font-size: 2.2rem;
            }
            p.slogan {
                font-size: 1rem;
            }
            .btn-container {
                flex-direction: column;
                gap: 20px;
            }
            a.button {
                font-size: 1.1rem;
                padding: 12px 30px;
            }
        }
    </style>
</head>
<body>

<h1>Oyun - Oyuncu Takip Sistemi</h1>
<p class="slogan">En iyi skorları kaydet, oyun becerilerini geliştir!</p>

<div class="btn-container">
    <a href="login.php" class="button">
        <i class="fa-solid fa-right-to-bracket"></i> Giriş Yap
    </a>
    <a href="register.php" class="button">
        <i class="fa-solid fa-user-plus"></i> Kayıt Ol
    </a>
</div>

</body>
</html>
