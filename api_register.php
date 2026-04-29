<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Content-Security-Policy: default-src \'self\'');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');
    $password_confirm = trim($data['password_confirm'] ?? '');

    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        echo json_encode(["status" => "error", "message" => "Lütfen tüm alanları doldurun."]);
        exit;
    }

    // Şifreler eşleşiyor mu kontrol et
    if ($password !== $password_confirm) {
        echo json_encode(["status" => "error", "message" => "Şifreler eşleşmiyor."]);
        exit;
    }

    // Şifre uzunluğu kontrolü
    if (strlen($password) < 6) {
        echo json_encode(["status" => "error", "message" => "Şifre en az 6 karakter olmalı."]);
        exit;
    }

    // E-posta formatı kontrolü
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Geçersiz e-posta formatı."]);
        exit;
    }

    // Şifreyi şifreleme (Hash)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // E-posta veya kullanıcı adı daha önce alınmış mı kontrol et - Prepared Statement kullan
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email=? OR username=?");
    if (!$check_stmt) {
        echo json_encode(["status" => "error", "message" => "Veritabanı hatası."]);
        exit;
    }
    
    $check_stmt->bind_param("ss", $email, $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Bu kullanıcı adı veya e-posta zaten kullanımda."]);
    } else {
        // INSERT için de Prepared Statement kullan
        $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if (!$insert_stmt) {
            echo json_encode(["status" => "error", "message" => "Veritabanı hatası."]);
            exit;
        }
        
        $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($insert_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Kayıt işlemi başarılı. Giriş yapabilirsiniz."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Bir hata oluştu: " . $insert_stmt->error]);
        }
        $insert_stmt->close();
    }
    
    $check_stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz istek türü."]);
}

$conn->close();
?>
