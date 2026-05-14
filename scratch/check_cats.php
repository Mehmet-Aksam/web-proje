<?php
require_once 'config.php';
$res = $conn->query("SELECT DISTINCT category FROM questions");
while($row = $res->fetch_assoc()) {
    echo $row['category'] . "\n";
}
?>
