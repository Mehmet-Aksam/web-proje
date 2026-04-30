<?php
/**
 * Liderlik Tablosu API
 * Kullanıcıların toplam puanlarına göre sıralanmış listeyi döndürür.
 * İstek: GET /api_leaderboard.php
 * Yanıt: JSON dizisi [{ username, score, quizzes_solved }, ...]
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Hata raporlama (JSON çıktısını bozmasın)
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'config.php';

// users tablosunda score ve quizzes_solved sütunlarının varlığını kontrol et
$checkCol = $conn->query("SHOW COLUMNS FROM `users` LIKE 'score'");
if (!$checkCol || $checkCol->num_rows == 0) {
    // Sütun yoksa, boş dizi döndür (setup_db.php henüz çalıştırılmamış olabilir)
    echo json_encode([]);
    $conn->close();
    exit;
}

// En yüksek puanlı 10 kullanıcıyı getir (admin hariç)
$sql = "SELECT username, score, quizzes_solved 
        FROM users 
        WHERE role != 'admin' AND score > 0
        ORDER BY score DESC 
        LIMIT 10";

$result = $conn->query($sql);

if (!$result) {
    // SQL hatası varsa boş dizi döndür
    echo json_encode([]);
    $conn->close();
    exit;
}

$leaderboard = [];
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = [
        'username'       => $row['username'],
        'score'          => (int)$row['score'],
        'quizzes_solved' => (int)$row['quizzes_solved']
    ];
}

echo json_encode($leaderboard, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
