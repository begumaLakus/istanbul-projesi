<?php
$host = 'localhost';
$dbname = 'istanbul_proje';
$user = 'postgres';
$pass = 'elma1145'; // Şükran’ın kurduğu şifre neyse onu gir

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Bağlantı başarılı!";
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    exit;
}
?>
