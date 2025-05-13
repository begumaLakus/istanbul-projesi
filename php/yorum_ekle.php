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

// JSON veriyi al
$data = json_decode(file_get_contents("php://input"), true);
$yer_id = $data['yer_id'] ?? null;
$yorum = $data['yorum'] ?? null;

if (!$yer_id || !$yorum) {
    echo json_encode(["status" => "error", "message" => "Yer ID ve yorum zorunludur."]);
    exit;
}

// Veritabanına ekle
$stmt = $pdo->prepare("INSERT INTO yorumlar (user_id, yer_id, yorum) VALUES (:user_id, :yer_id, :yorum)");
$stmt->execute([
    'user_id' => $userId,
    'yer_id' => $yer_id,
    'yorum' => $yorum
]);

echo json_encode(["status" => "success", "message" => "Yorum başarıyla eklendi."]);
