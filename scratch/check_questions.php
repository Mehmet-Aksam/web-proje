<?php
require_once 'config.php';
$res = $conn->query("SELECT * FROM questions LIMIT 5");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
