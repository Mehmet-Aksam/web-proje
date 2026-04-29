<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// Hata ayıklama için hata raporlamayı açalım (geliştirme aşamasında)
error_reporting(E_ALL);
ini_set('display_errors', 0); // JSON çıktısını bozmaması için 0

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Skor kaydetmek için giriş yapmalısınız."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $userId = $_SESSION['user_id'];
    $testKey = trim($data['test_key'] ?? '');
    $score = intval($data['score'] ?? 0);
    $correct = intval($data['correct'] ?? 0);
    $wrong = intval($data['wrong'] ?? 0);
    $total = intval($data['total'] ?? 0);

    // Tablo yoksa oluştur (Güvenlik önlemi)
    $conn->query("CREATE TABLE IF NOT EXISTS `score_history` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `test_key` VARCHAR(100),
        `score` INT,
        `correct_count` INT,
        `wrong_count` INT,
        `total_questions` INT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // Skoru kaydet
    $stmt = $conn->prepare("INSERT INTO score_history (user_id, test_key, score, correct_count, wrong_count, total_questions) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiii", $userId, $testKey, $score, $correct, $wrong, $total);
    
    if ($stmt->execute()) {
        // Kullanıcının toplam puanını ve çözdüğü quiz sayısını güncelle
        // Burada her quiz çözümünde puanları topluyoruz
        $conn->query("UPDATE users SET score = score + $score, quizzes_solved = quizzes_solved + 1 WHERE id = $userId");
        echo json_encode(["status" => "success", "message" => "Skor kaydedildi ve liderlik tablosu güncellendi."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Veritabanı hatası: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz istek."]);
}

$conn->close();
?>
