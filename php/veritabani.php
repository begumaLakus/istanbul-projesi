<?php
$host = 'localhost';
$dbname = 'istanbul_proje';
$user = 'postgres';
$pass = 'elma1145';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Bağlantı başarılı! 🥳";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
