<?php
require_once 'config.php';
$res = $conn->query("SELECT category, COUNT(*) as cnt FROM questions GROUP BY category");
while($row = $res->fetch_assoc()) {
    echo $row['category'] . ": " . $row['cnt'] . "\n";
}
?>
