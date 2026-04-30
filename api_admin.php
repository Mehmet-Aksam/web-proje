<?php
/**
 * Admin API Endpoint
 * Test, soru ve kullanıcı yönetimi için CRUD işlemleri
 */
session_start();
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 0);

// Admin yetki kontrolü
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Yetkisiz erişim."]);
    exit;
}

require_once 'config.php';

$action = $_GET['action'] ?? '';

switch ($action) {

    // ======================== TESTLER ========================
    case 'get_tests':
        $result = $conn->query("SELECT * FROM tests ORDER BY id DESC");
        $tests = [];
        while ($row = $result->fetch_assoc()) $tests[] = $row;
        echo json_encode($tests, JSON_UNESCAPED_UNICODE);
        break;

    case 'add_test':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("INSERT INTO tests (test_key, title, description, category, duration_minutes) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $data['test_key'], $data['title'], $data['description'], $data['category'], $data['duration_minutes']);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Test başarıyla eklendi."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hata: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'delete_test':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($data['id']);
        // Önce testin anahtarını bul, sonra ilgili soruları sil
        $r = $conn->query("SELECT test_key FROM tests WHERE id = $id");
        if ($r && $row = $r->fetch_assoc()) {
            $conn->query("DELETE FROM questions WHERE category = '" . $conn->real_escape_string($row['test_key']) . "'");
        }
        $conn->query("DELETE FROM tests WHERE id = $id");
        echo json_encode(["status" => "success", "message" => "Test ve soruları silindi."]);
        break;

    // ======================== SORULAR ========================
    case 'get_questions':
        $cat = $_GET['category'] ?? '';
        if ($cat) {
            $stmt = $conn->prepare("SELECT * FROM questions WHERE category = ? ORDER BY id DESC");
            $stmt->bind_param("s", $cat);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM questions ORDER BY id DESC");
        }
        $questions = [];
        while ($row = $result->fetch_assoc()) $questions[] = $row;
        echo json_encode($questions, JSON_UNESCAPED_UNICODE);
        break;

    case 'add_question':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("INSERT INTO questions (category, soru, resim, a, b, c, d, dogru) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $data['category'], $data['soru'], $data['resim'], $data['a'], $data['b'], $data['c'], $data['d'], $data['dogru']);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Soru eklendi."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hata: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'delete_question':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($data['id']);
        $conn->query("DELETE FROM questions WHERE id = $id");
        echo json_encode(["status" => "success", "message" => "Soru silindi."]);
        break;

    // ======================== TOPLU SORU EKLEME ========================
    case 'bulk_add_questions':
        $data = json_decode(file_get_contents('php://input'), true);
        $questions = $data['questions'] ?? [];
        $category = $data['category'] ?? '';

        if (empty($category) || empty($questions)) {
            echo json_encode(["status" => "error", "message" => "Kategori ve sorular zorunludur."]);
            break;
        }

        $stmt = $conn->prepare("INSERT INTO questions (category, soru, resim, a, b, c, d, dogru) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $success = 0;
        $errors = 0;

        foreach ($questions as $q) {
            $soru = trim($q['soru'] ?? '');
            $resim = trim($q['resim'] ?? '');
            $a = trim($q['a'] ?? '');
            $b = trim($q['b'] ?? '');
            $c = trim($q['c'] ?? '');
            $d = trim($q['d'] ?? '');
            $dogru = strtolower(trim($q['dogru'] ?? ''));

            // Doğrulama
            if (empty($soru) || empty($a) || empty($b) || empty($c) || empty($d) || !in_array($dogru, ['a','b','c','d'])) {
                $errors++;
                continue;
            }

            $stmt->bind_param("ssssssss", $category, $soru, $resim, $a, $b, $c, $d, $dogru);
            if ($stmt->execute()) {
                $success++;
            } else {
                $errors++;
            }
        }
        $stmt->close();

        echo json_encode([
            "status" => "success",
            "message" => "$success soru başarıyla eklendi" . ($errors > 0 ? ", $errors soru hatalı/atlandı." : "."),
            "success_count" => $success,
            "error_count" => $errors
        ], JSON_UNESCAPED_UNICODE);
        break;

    // ======================== KULLANICILAR ========================
    case 'get_users':
        $result = $conn->query("SELECT id, username, email, role, score, quizzes_solved, created_at FROM users ORDER BY id ASC");
        $users = [];
        while ($row = $result->fetch_assoc()) $users[] = $row;
        echo json_encode($users, JSON_UNESCAPED_UNICODE);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Geçersiz işlem: $action"]);
}

$conn->close();
?>
