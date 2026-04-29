<?php
$host = "localhost";
$username = "root";
$password = ""; // WAMP server default
$dbname = "web_proje_db";

// Bağlantıyı oluştur
$conn = new mysqli($host, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
// Karakter setini UTF-8 yap ki Türkçe karakterler düzgün çalışsın
$conn->set_charset("utf8mb4");
?>
