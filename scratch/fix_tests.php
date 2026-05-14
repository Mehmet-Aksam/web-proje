<?php
require_once 'config.php';

$missing_tests = [
    ['ingilizce_test_1', 'Temel Eşyalar Testi', 'Temel kelime bilgisi testi', 'ingilizce', 10],
    ['ingilizce_test_2', 'Cümle Çeviri Testi', 'Çeviri üzerine test', 'ingilizce', 10],
    ['ingilizce_test_3', 'Karışık Kelime Testi', 'Zor seviye kelimeler', 'ingilizce', 15],
    ['ingilizce_test_4', 'İngilizce Dil Testi - 4', 'Dil bilgisi ve gramer üzerine pekiştirme testi.', 'ingilizce', 20],
    ['ingilizce_test_5', 'İngilizce Dil Testi - 5', 'Genel İngilizce kelime ve yapı deneme sınavı.', 'ingilizce', 20],
    ['genelkultur_test_1', 'Antik Çağ ve Mitoloji', 'Antik uygarlıklar ve mitolojik efsaneler üzerine test.', 'genel_kultur', 15],
    ['genelkultur_test_2', 'Yakın Çağ Tarihi', 'Dünya tarihinin yakın geçmişine dair önemli olaylar.', 'genel_kultur', 20],
    ['genelkultur_test_3', 'Osmanlı İmparatorluğu', 'Osmanlı tarihi, padişahlar ve önemli savaşlar.', 'genel_kultur', 25],
    ['genelkultur_test_4', 'Başkentler ve Bayraklar', 'Ülkeler, başkentleri ve bayrakları hakkında coğrafya testi.', 'genel_kultur', 10],
    ['genelkultur_test_5', 'Dünya Klasikleri', 'Edebiyat dünyasının ölümsüz eserleri ve yazarları.', 'genel_kultur', 20]
];

foreach ($missing_tests as $t) {
    // Check if test_key already exists
    $stmt = $conn->prepare("SELECT id FROM tests WHERE test_key = ?");
    $stmt->bind_param("s", $t[0]);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows == 0) {
        $stmt2 = $conn->prepare("INSERT INTO tests (test_key, title, description, category, duration_minutes) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("ssssi", $t[0], $t[1], $t[2], $t[3], $t[4]);
        $stmt2->execute();
        echo "Eklendi: " . $t[0] . "\n";
    } else {
        echo "Zaten var: " . $t[0] . "\n";
    }
}
?>
