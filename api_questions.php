<?php
header('Content-Type: application/json');
require_once 'config.php';

// Hangi kategorinin istendiğini alıyoruz
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

if (empty($category)) {
    echo json_encode(["status" => "error", "message" => "Kategori belirtilmedi."]);
    exit;
}

$sql = "SELECT soru, resim, a, b, c, d, dogru FROM questions WHERE category='$category'";
$result = $conn->query($sql);

$questions = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Eğer boş alanlar varsa JSON'a dahil etmemek için filtreleyebiliriz
        // Ya da doğrudan gönderebiliriz, JavaScript tarafında ele alınır.
        $questions[] = $row;
    }
    echo json_encode($questions);
} else {
    // Eğer kategoriye ait soru yoksa boş dizi döndür
    echo json_encode([]);
}

$conn->close();
?>
