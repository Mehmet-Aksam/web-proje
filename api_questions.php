<?php
$conn = new mysqli("localhost", "root", "", "web_proje_db");
if ($conn->connect_error) {
    die("Bağlantı hatası");
}

$category = $_GET['category'];

$sql = "SELECT * FROM questions WHERE category='$category'";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>