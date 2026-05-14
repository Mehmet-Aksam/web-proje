<?php
require_once 'config.php';
$res = $conn->query("SELECT test_key, category FROM tests");
while($row = $res->fetch_assoc()) {
    echo "Key: " . $row['test_key'] . " | Cat: " . $row['category'] . "\n";
}
?>
