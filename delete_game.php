<?php
require_once 'config.php';
session_start();

// Giriş yapılmamışsa yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ID kontrolü
if (!isset($_GET['id'])) {
    echo "Geçersiz istek!";
    exit;
}

$record_id = intval($_GET['id']);

// Kayıt gerçekten bu kullanıcıya mı ait?
$checkStmt = $mysqli->prepare("SELECT id FROM game_records WHERE id = ? AND user_id = ?");
$checkStmt->bind_param("ii", $record_id, $user_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    echo "Bu kayda erişim izniniz yok!";
    exit;
}

// Kayıt silme
$deleteStmt = $mysqli->prepare("DELETE FROM game_records WHERE id = ? AND user_id = ?");
$deleteStmt->bind_param("ii", $record_id, $user_id);
if ($deleteStmt->execute()) {
    header("Location: list_games.php"); // Kayıt listesine dön
    exit;
} else {
    echo "Silme işlemi başarısız!";
}
?>
