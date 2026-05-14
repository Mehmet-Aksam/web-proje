<?php
require_once 'config.php';
if ($conn->connect_error) {
    echo "Bağlantı hatası: " . $conn->connect_error;
} else {
    echo "Bağlantı başarılı!";
    $res = $conn->query("SHOW TABLES LIKE 'questions'");
    if ($res->num_rows > 0) {
        echo " 'questions' tablosu mevcut.";
        $res2 = $conn->query("SELECT COUNT(*) as cnt FROM questions");
        $row = $res2->fetch_assoc();
        echo " Toplam soru sayısı: " . $row['cnt'];
    } else {
        echo " 'questions' tablosu bulunamadı!";
    }
}
?>
