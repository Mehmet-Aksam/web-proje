<?php
header('Content-Type: application/json');
require_once 'config.php';

$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, test_key, title, category FROM tests 
        WHERE title LIKE '%$q%' OR category LIKE '%$q%' 
        LIMIT 5";
$result = $conn->query($sql);

$results = [];
while($row = $result->fetch_assoc()) {
    $results[] = $row;
}

echo json_encode($results);
$conn->close();
?>
