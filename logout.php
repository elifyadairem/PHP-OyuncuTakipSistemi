

<?php
session_start(); // Oturumu başlatır
session_unset();  // Tüm oturum verilerini temizler
session_destroy(); // Oturumu tamamen kapatır

header("Location: login.php");
exit();