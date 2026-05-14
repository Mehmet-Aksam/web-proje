<?php
// Mock $_GET
$_GET['category'] = 'levhalar_test_1';
ob_start();
include 'api_questions.php';
$out = ob_get_clean();
echo "Output: " . $out;
?>
