<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

$category = $_GET['category'] ?? '';

if (empty($category)) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM questions WHERE category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
$stmt->close();
$conn->close();
?>