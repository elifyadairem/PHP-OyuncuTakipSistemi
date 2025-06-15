<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Kayıt ID kontrolü
if (!isset($_GET['id'])) {
    echo "Geçersiz istek!";
    exit;
}

$record_id = intval($_GET['id']);

// Kaydı al
$stmt = $mysqli->prepare("SELECT * FROM game_records WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $record_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Kayıt bulunamadı!";
    exit;
}

$record = $result->fetch_assoc();

// Güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_name = $_POST['game_name'];
    $game_mode = $_POST['game_mode'];
    $score = $_POST['score'];
    $level = $_POST['level_reached'];
    $date_played = $_POST['date_played'];

    $updateStmt = $mysqli->prepare("
        UPDATE game_records 
        SET game_name = ?, game_mode = ?, score = ?, level_reached = ?, date_played = ?
        WHERE id = ? AND user_id = ?
    ");
    $updateStmt->bind_param("ssiisii", $game_name, $game_mode, $score, $level, $date_played, $record_id, $user_id);
    if ($updateStmt->execute()) {
        header("Location: list_games.php");
        exit;
    } else {
        echo "Güncelleme başarısız!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Oyun Kaydını Düzenle</title>
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            color: #e0e0e0;
            padding: 40px;
        }
        form {
            background: #272433;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 15px #00ffea44;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 10px;
            border: none;
        }
        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background-color: #00ffea;
            border: none;
            font-weight: bold;
            color: #1f1c2c;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #00ffea99;
        }
    </style>
</head>
<body>

<h2 style="text-align:center; color:#00ffea;">Oyun Kaydını Düzenle</h2>

<form method="POST">
    <label>Oyun Adı:</label>
    <input type="text" name="game_name" required value="<?= htmlspecialchars($record['game_name']) ?>">

    <label>Oyun Modu:</label>
    <input type="text" name="game_mode" required value="<?= htmlspecialchars($record['game_mode']) ?>">

    <label>Skor:</label>
    <input type="number" name="score" required value="<?= htmlspecialchars($record['score']) ?>">

    <label>Ulaşılan Seviye:</label>
    <input type="number" name="level_reached" required value="<?= htmlspecialchars($record['level_reached']) ?>">

    <label>Oynanma Tarihi:</label>
    <input type="date" name="date_played" required value="<?= htmlspecialchars($record['date_played']) ?>">

    <button type="submit">Kaydı Güncelle</button>
</form>

</body>
</html>
