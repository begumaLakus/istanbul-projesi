<?php
$host = 'localhost';
$dbname = 'istanbul_proje';
$user = 'postgres';
$pass = 'elma1145';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
 catch (PDOException $e) {

    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "Veritabanı bağlantı hatası: " . $e->getMessage()]);
    exit;
}
?>
