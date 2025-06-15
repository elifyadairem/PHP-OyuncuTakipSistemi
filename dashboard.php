<?php
require_once 'config.php';
session_start();

// Giriş yapılmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Kullanıcı bilgilerini al
$stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

// Son 5 oyun kaydını al
$stmtGames = $conn->prepare("
    SELECT game_name, game_mode, score, level_reached, date_played 
    FROM game_records 
    WHERE user_id = ? 
    ORDER BY date_played DESC 
    LIMIT 5
");
$stmtGames->bind_param("i", $user_id);
$stmtGames->execute();
$gamesResult = $stmtGames->get_result();
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Oyun Takip Sistemi</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            color: #e0e0e0;
            margin: 0;
            padding: 0 20px 40px;
        }
        header {
            background: #0f2027;
            color: #00ffea;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px #00ffea55;
        }
        header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        header a.logout-btn {
            color: #00ffea;
            font-weight: 700;
            text-decoration: none;
            border: 2px solid #00ffea;
            padding: 8px 18px;
            border-radius: 30px;
            transition: 0.3s;
        }
        header a.logout-btn:hover {
            background: #00ffea;
            color: #1f1c2c;
            box-shadow: 0 0 10px #00ffea;
        }

        main {
            max-width: 1000px;
            margin: 30px auto;
        }

        .welcome {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #00ffea;
            text-shadow: 0 0 10px #00fff7;
        }

        .user-info {
            background: #272433;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 0 15px #00ffea44;
        }
        .user-info p {
            margin: 8px 0;
            font-size: 1.1rem;
        }

        .games-section {
            background: #272433;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 15px #00ffea44;
        }
        .games-section h2 {
            margin-top: 0;
            color: #00ffea;
            text-shadow: 0 0 10px #00fff7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            color: #d0f0f0;
        }
        table th, table td {
            padding: 12px 10px;
            border-bottom: 1px solid #00ffea22;
            text-align: center;
        }
        table th {
            background: #0f2027;
            font-weight: 700;
        }
        table tr:hover {
            background: #00ffea11;
        }

        .btn-add-game {
            display: inline-block;
            margin-top: 15px;
            background: #00ffea;
            color: #1f1c2c;
            font-weight: 700;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            box-shadow: 0 0 15px #00ffea88;
            transition: 0.3s;
        }
        .btn-add-game:hover {
            background: transparent;
            color: #00ffea;
            border: 2px solid #00ffea;
            box-shadow: 0 0 20px #00ffea;
        }

        /* Responsive */
        @media (max-width: 720px) {
            main {
                margin: 20px 10px;
            }
            table, table thead, table tbody, table th, table td, table tr {
                display: block;
            }
            table tr {
                margin-bottom: 15px;
            }
            table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-left: 10px;
                font-weight: 700;
                text-align: left;
                color: #00ffea;
            }
            table th {
                display: none;
            }
        }
    </style>
</head>
<body>



<header>
    <h1>Oyun Takip Sistemi</h1>
    <a href="logout.php" class="logout-btn" title="Çıkış yap">
        <i class="fa-solid fa-right-from-bracket"></i> Çıkış Yap
    </a>
</header>

<nav style="background: #1f1c2c; padding: 12px 20px; box-shadow: 0 2px 10px #00ffea33; display: flex; gap: 20px; justify-content: center;">
    <a href="dashboard.php" style="color: #00ffea; text-decoration: none; font-weight: bold;">🏠 Dashboard</a>
    <a href="add_game.php" style="color: #00ffea; text-decoration: none; font-weight: bold;">🎮 Oyun Ekle</a>
    <a href="list_games.php" style="color: #00ffea; text-decoration: none; font-weight: bold;">📋 Oyunlarım</a>
    <a href="logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: bold;">🚪 Çıkış Yap</a>
</nav>

<main>
    <div class="welcome">Hoşgeldin, <?= htmlspecialchars($user['fullname']) ?>!</div>

    <section class="user-info">
        <h2>Profil Bilgilerin</h2>
        <p><strong>Kullanıcı Adı:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>E-posta:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Doğum Tarihi:</strong> <?= htmlspecialchars($user['birth_date']) ?></p>
    </section>

    <section class="games-section">
        <h2>Son Oynadığın Oyunlar</h2>

        <?php if ($gamesResult->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Oyun Adı</th>
                    <th>Mod</th>
                    <th>Skor</th>
                    <th>Seviye</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
            <?php while($game = $gamesResult->fetch_assoc()): ?>
                <tr>
                    <td data-label="Oyun Adı"><?= htmlspecialchars($game['game_name']) ?></td>
                    <td data-label="Mod"><?= htmlspecialchars($game['game_mode']) ?></td>
                    <td data-label="Skor"><?= htmlspecialchars($game['score']) ?></td>
                    <td data-label="Seviye"><?= htmlspecialchars($game['level_reached']) ?></td>
                    <td data-label="Tarih"><?= htmlspecialchars($game['date_played']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Henüz oyun kaydın bulunmamaktadır.</p>
        <?php endif; ?>

        <a href="add_game.php" class="btn-add-game">
            <i class="fa-solid fa-plus"></i> Yeni Oyun Kaydı Ekle
        </a>
    </section>

    <form method="POST" action="delete_acc.php" onsubmit="return confirm('Hesabınızı kalıcı olarak silmek istediğinize emin misiniz? Bu işlem geri alınamaz!');">
    <button type="submit" style="
        margin-top: 30px;
        background: #ff4d4d;
        color: white;
        font-weight: bold;
        padding: 12px 25px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        box-shadow: 0 0 10px #ff4d4dcc;
        transition: background-color 0.3s;
    ">Hesabımı Sil</button>
</form>

</main>

</body>

</html>
