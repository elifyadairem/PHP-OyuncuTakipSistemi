<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $game_name = trim($_POST['game_name']);
    $game_mode = trim($_POST['game_mode']);
    $score = (int)$_POST['score'];
    $level = (int)$_POST['level_reached'];
    $date = $_POST['date_played'];
    $user_id = $_SESSION['user_id'];

    if ($game_name && $game_mode && $score >= 0 && $level >= 0 && $date) {
        $stmt = $mysqli->prepare("INSERT INTO game_records (user_id, game_name, game_mode, score, level_reached, date_played) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issiis", $user_id, $game_name, $game_mode, $score, $level, $date);

        if ($stmt->execute()) {
            $success = "Oyun kaydı başarıyla eklendi!";
        } else {
            $error = "Bir hata oluştu: " . $stmt->error;
        }
    } else {
        $error = "Lütfen tüm alanları eksiksiz ve doğru şekilde doldurun.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Oyun Ekle</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            color: #e0e0e0;
            margin: 0;
            padding: 0 20px;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #00ffea;
        }
        form {
            max-width: 500px;
            margin: 40px auto;
            background: #272433;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 20px #00ffea55;
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
            border: none;
            border-radius: 8px;
            font-size: 1rem;
        }
        input[type="submit"] {
            margin-top: 25px;
            background: #00ffea;
            color: #1f1c2c;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        input[type="submit"]:hover {
            background: transparent;
            color: #00ffea;
            border: 2px solid #00ffea;
        }
        .msg {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
        .success { color: #4fff8f; }
        .error { color: #ff4d4d; }
    </style>
</head>
<body>

<h1>Yeni Oyun Kaydı Ekle</h1>

<form method="post" action="">
    <label>Oyun Adı:</label>
    <input type="text" name="game_name" required>

    <label>Oyun Modu:</label>
    <input type="text" name="game_mode" required>

    <label>Skor:</label>
    <input type="number" name="score" min="0" required>

    <label>Ulaşılan Seviye:</label>
    <input type="number" name="level_reached" min="0" required>

    <label>Oynanma Tarihi:</label>
    <input type="date" name="date_played" required>

    <input type="submit" value="Kaydı Ekle">
</form>

<?php if ($success): ?>
    <div class="msg success"><?= htmlspecialchars($success) ?></div>
<?php elseif ($error): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

</body>
</html>
