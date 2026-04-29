<?php
session_start();
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Content-Security-Policy: default-src \'self\'');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Kullanıcı adı ve şifre al
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Lütfen kullanıcı adı ve şifrenizi girin."]);
        exit;
    }

    // Prepared Statement kullanarak SQL Injection'ı önle
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Veritabanı hatası."]);
        exit;
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Şifre doğrulama
        if (password_verify($password, $user['password'])) {
            // Oturum (Session) başlatma
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            echo json_encode(["status" => "success", "message" => "Giriş başarılı.", "role" => $user['role']]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hatalı şifre girdiniz."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Böyle bir kullanıcı bulunamadı."]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz istek türü."]);
}

$conn->close();
?>
