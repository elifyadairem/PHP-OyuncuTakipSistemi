<?php
// Veritabanı bağlantı bilgileri local için
#$host = 'localhost';
#$user = 'root';
#$password = '';
#$dbname = 'game_tracker_db';



$host = "localhost";
$user = "your_db_user";
$password = "your_db_password";
$dbname = "your_db_name";


// Bağlantıyı $conn adıyla yap
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>

