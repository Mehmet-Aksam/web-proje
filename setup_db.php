<?php
/**
 * Veritabanı Kurulum Scripti (Hata Giderilmiş Versiyon)
 */

$host = "localhost";
$username = "root";
$password = "";
$dbname = "web_proje_db";

$conn = new mysqli($host, $username, $password);
if ($conn->connect_error) die("Bağlantı hatası: " . $conn->connect_error);
$conn->set_charset("utf8mb4");

// Veritabanını oluştur ve seç
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbname);

// 1. Users Tablosunu Oluştur (Veya Güncelle)
$conn->query("CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Eksik Sütunları Kontrol Et ve Ekle (Tablo önceden varsa hata almamak için)
$columns = [
    'role' => "ENUM('user','admin') DEFAULT 'user' AFTER password",
    'score' => "INT DEFAULT 0 AFTER role",
    'quizzes_solved' => "INT DEFAULT 0 AFTER score"
];

foreach ($columns as $col => $definition) {
    $check = $conn->query("SHOW COLUMNS FROM `users` LIKE '$col'");
    if ($check->num_rows == 0) {
        $conn->query("ALTER TABLE `users` ADD `$col` $definition");
    }
}

// 2. Diğer Tabloları Oluştur
$conn->query("CREATE TABLE IF NOT EXISTS `tests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `test_key` VARCHAR(100) NOT NULL UNIQUE,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT,
    `category` VARCHAR(100) NOT NULL,
    `duration_minutes` INT DEFAULT 20,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$conn->query("CREATE TABLE IF NOT EXISTS `questions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(100) NOT NULL,
    `soru` TEXT NOT NULL,
    `resim` VARCHAR(500) DEFAULT NULL,
    `a` VARCHAR(500) NOT NULL,
    `b` VARCHAR(500) NOT NULL,
    `c` VARCHAR(500) NOT NULL,
    `d` VARCHAR(500) NOT NULL,
    `dogru` CHAR(1) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// 3. Admin Kullanıcısı Oluştur
$adminCheck = $conn->query("SELECT id FROM users WHERE username='admin'");
if ($adminCheck->num_rows == 0) {
    $pass = password_hash("admin123", PASSWORD_BCRYPT);
    $conn->query("INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@sorcik.com', '$pass', 'admin')");
} else {
    // Admin varsa yetkisini güncelle
    $conn->query("UPDATE users SET role='admin' WHERE username='admin'");
}

// 4. Testleri Ekle
$tests = [
    ['levhalar_test_1', 'Trafik Levhaları Uzmanlık', 'Tüm levhaları kapsayan detaylı ehliyet sınavı.', 'ehliyet', 20],
    ['genelkultur_test', 'Genel Kültür Maratonu', 'Tarih, Sanat ve Coğrafya karma testi.', 'genel_kultur', 20],
    ['ingilizce_test', 'İngilizce Kelime Deposu', 'En çok kullanılan 1000 kelime üzerine test.', 'ingilizce', 15]
];
foreach($tests as $t) {
    $conn->query("INSERT IGNORE INTO tests (test_key, title, description, category, duration_minutes) VALUES ('$t[0]', '$t[1]', '$t[2]', '$t[3]', $t[4])");
}

// 5. Soruları Temizle ve Yeniden Ekle (20'şer Soru)
$conn->query("TRUNCATE TABLE questions");

function addBatchQuestions($conn, $cat, $topic) {
    $stmt = $conn->prepare("INSERT INTO questions (category, soru, a, b, c, d, dogru) VALUES (?, ?, ?, ?, ?, ?, ?)");
    for($i=1; $i<=20; $i++) {
        $q = "$topic - Soru $i: Bu konu hakkındaki teknik bilgi nedir?";
        $a = "Yanlış Seçenek 1"; $b = "Doğru Cevap"; $c = "Yanlış Seçenek 2"; $d = "Yanlış Seçenek 3";
        $correct = "b";
        $stmt->bind_param("sssssss", $cat, $q, $a, $b, $c, $d, $correct);
        $stmt->execute();
    }
}

addBatchQuestions($conn, 'levhalar_test_1', 'Ehliyet & Trafik');
addBatchQuestions($conn, 'genelkultur_test', 'Genel Kültür');
addBatchQuestions($conn, 'ingilizce_test', 'İngilizce Dil');

echo "<h1>✅ Kurulum Başarılı!</h1><p>Tablolar güncellendi, eksik sütunlar (role, score vb.) eklendi ve 20'şer soru hazır. <a href='index.php'>Ana Sayfaya Git</a></p>";
?>
