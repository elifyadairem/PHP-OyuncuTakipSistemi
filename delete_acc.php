<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Veritabanı bağlantısını hazırla
$mysqli = new mysqli($host, $user, $password, $dbname);
if ($mysqli->connect_error) {
    die("Veritabanı bağlantı hatası: " . $mysqli->connect_error);
}

// Kullanıcının oyun kayıtlarını sil
$stmt1 = $mysqli->prepare("DELETE FROM game_records WHERE user_id = ?");
$stmt1->bind_param("i", $user_id);
$stmt1->execute();

// Kullanıcıyı sil
$stmt2 = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();

// Oturumu kapat ve anasayfaya yönlendir
session_unset();
session_destroy();

header("Location: index.php");
exit;
