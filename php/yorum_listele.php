<?php
require_once '../vendor/autoload.php';

header("Content-Type: application/json");

$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=istanbul_proje", "postgres", "elma1145");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$yer_id = $_GET['yer_id'] ?? null;

if (!$yer_id) {
    echo json_encode(["status" => "error", "message" => "Yer ID zorunludur."]);
    exit;
}

$stmt = $pdo->prepare("SELECT yorum, tarih FROM yorumlar WHERE yer_id = :yer_id ORDER BY tarih DESC");
$stmt->execute(['yer_id' => $yer_id]);
$yorumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "yorumlar" => $yorumlar]);
