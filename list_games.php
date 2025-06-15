<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, game_name, game_mode, score, level_reached, date_played FROM game_records WHERE user_id = ? ORDER BY date_played DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıtlı Oyunlarım</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            color: #e0e0e0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #00ffea;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: #272433;
            box-shadow: 0 0 15px #00ffea44;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #00ffea22;
        }
        th {
            background-color: #0f2027;
            color: #00ffea;
        }
        tr:hover {
            background: #00ffea11;
        }
        a.btn {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .edit-btn {
            background-color: #ffa500;
            color: #1f1c2c;
        }
        .delete-btn {
            background-color: #ff4d4d;
            color: #fff;
        }
        .edit-btn:hover {
            background-color: #ffcc66;
        }
        .delete-btn:hover {
            background-color: #ff1a1a;
        }
    </style>
</head>
<body>

<h1>Kayıtlı Oyunlarım</h1>

<table>
    <thead>
        <tr>
            <th>Oyun Adı</th>
            <th>Mod</th>
            <th>Skor</th>
            <th>Seviye</th>
            <th>Tarih</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['game_name']) ?></td>
            <td><?= htmlspecialchars($row['game_mode']) ?></td>
            <td><?= htmlspecialchars($row['score']) ?></td>
            <td><?= htmlspecialchars($row['level_reached']) ?></td>
            <td><?= htmlspecialchars($row['date_played']) ?></td>
            <td>
                <a href="edit_game.php?id=<?= $row['id'] ?>" class="btn edit-btn">Düzenle</a>
                <a href="delete_game.php?id=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Bu kaydı silmek istediğinizden emin misiniz?')">Sil</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
