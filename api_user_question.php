<?php
/**
 * Kullanıcı Soru Önerme API
 * Kullanıcılar soru gönderir, admin onaylar/reddeder
 */
session_start();
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'config.php';

// pending_questions tablosunu oluştur (yoksa)
$conn->query("CREATE TABLE IF NOT EXISTS `pending_questions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `soru` TEXT NOT NULL,
    `resim` VARCHAR(500) DEFAULT NULL,
    `a` VARCHAR(500) NOT NULL,
    `b` VARCHAR(500) NOT NULL,
    `c` VARCHAR(500) NOT NULL,
    `d` VARCHAR(500) NOT NULL,
    `dogru` CHAR(1) NOT NULL,
    `status` ENUM('pending','approved','rejected') DEFAULT 'pending',
    `admin_note` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$action = $_GET['action'] ?? '';

switch ($action) {

    // Kategorileri getir (testler listesi)
    case 'get_categories':
        $result = $conn->query("SELECT test_key, title FROM tests ORDER BY title ASC");
        $cats = [];
        while ($row = $result->fetch_assoc()) $cats[] = $row;
        echo json_encode($cats, JSON_UNESCAPED_UNICODE);
        break;

    // Kullanıcı soru gönder
    case 'submit':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Soru göndermek için giriş yapmalısınız."]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $category = trim($data['category'] ?? '');
        $soru = trim($data['soru'] ?? '');
        $resim = trim($data['resim'] ?? '');
        $a = trim($data['a'] ?? '');
        $b = trim($data['b'] ?? '');
        $c = trim($data['c'] ?? '');
        $d = trim($data['d'] ?? '');
        $dogru = strtolower(trim($data['dogru'] ?? ''));

        if (empty($soru) || empty($a) || empty($b) || empty($c) || empty($d) || !in_array($dogru, ['a','b','c','d'])) {
            echo json_encode(["status" => "error", "message" => "Tüm alanları doğru şekilde doldurun."]);
            break;
        }

        $stmt = $conn->prepare("INSERT INTO pending_questions (user_id, username, category, soru, resim, a, b, c, d, dogru) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssss", $userId, $username, $category, $soru, $resim, $a, $b, $c, $d, $dogru);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Sorunuz gönderildi! Admin onayı bekleniyor."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Veritabanı hatası: " . $stmt->error]);
        }
        $stmt->close();
        break;

    // Kullanıcının kendi sorularını getir
    case 'my_questions':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            exit;
        }
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT id, category, soru, status, created_at FROM pending_questions WHERE user_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $questions = [];
        while ($row = $result->fetch_assoc()) $questions[] = $row;
        echo json_encode($questions, JSON_UNESCAPED_UNICODE);
        $stmt->close();
        break;

    // ===== ADMIN İŞLEMLERİ =====

    // Bekleyen soruları getir (admin)
    case 'get_pending':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(["status" => "error", "message" => "Yetkisiz."]);
            exit;
        }
        $result = $conn->query("SELECT * FROM pending_questions WHERE status = 'pending' ORDER BY id DESC");
        $pending = [];
        while ($row = $result->fetch_assoc()) $pending[] = $row;
        echo json_encode($pending, JSON_UNESCAPED_UNICODE);
        break;

    // Soruyu onayla (admin) - questions tablosuna taşı
    case 'approve':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(["status" => "error", "message" => "Yetkisiz."]);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($data['id'] ?? 0);

        // Soruyu bul
        $stmt = $conn->prepare("SELECT * FROM pending_questions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Questions tablosuna ekle
            $ins = $conn->prepare("INSERT INTO questions (category, soru, resim, a, b, c, d, dogru) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $ins->bind_param("ssssssss", $row['category'], $row['soru'], $row['resim'], $row['a'], $row['b'], $row['c'], $row['d'], $row['dogru']);
            $ins->execute();
            $ins->close();

            // Durumu güncelle
            $conn->query("UPDATE pending_questions SET status = 'approved' WHERE id = $id");
            echo json_encode(["status" => "success", "message" => "Soru onaylandı ve quize eklendi!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Soru bulunamadı."]);
        }
        $stmt->close();
        break;

    // Soruyu reddet (admin)
    case 'reject':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(["status" => "error", "message" => "Yetkisiz."]);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($data['id'] ?? 0);
        $conn->query("UPDATE pending_questions SET status = 'rejected' WHERE id = $id");
        echo json_encode(["status" => "success", "message" => "Soru reddedildi."]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Geçersiz işlem."]);
}

$conn->close();
?>
