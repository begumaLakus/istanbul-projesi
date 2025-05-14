<?php
require_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header("Content-Type: application/json");

// Veritabanı bağlantısı
$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=istanbul_proje", "postgres", "elma1145");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Token kontrolü
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? null;

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    echo json_encode(["status" => "error", "message" => "Geçersiz token."]);
    exit;
}

$token = $matches[1];
$secretKey = "benim_sakli_anahtarim_123";

try {
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    $userId = $decoded->user_id;
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Token doğrulanamadı: " . $e->getMessage()]);
    exit;
}

// JSON verisi
$data = json_decode(file_get_contents("php://input"), true);
$yer_id = $data['yer_id'] ?? null;

if (!$yer_id) {
    echo json_encode(["status" => "error", "message" => "Yer ID zorunludur."]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM favoriler WHERE user_id = :user_id AND yer_id = :yer_id");
$stmt->execute([
    'user_id' => $userId,
    'yer_id' => $yer_id
]);

$existing = $stmt->fetch();
if ($existing) {
    echo json_encode(["status" => "error", "message" => "Bu yer zaten favorilerinizde."]);
    exit;
}

// Favoriye ekleme kısmım
$stmt = $pdo->prepare("INSERT INTO favoriler (user_id, yer_id) VALUES (:user_id, :yer_id)");
$stmt->execute([
    'user_id' => $userId,
    'yer_id' => $yer_id
]);

echo json_encode(["status" => "success", "message" => "Favorilere eklendi."]);
