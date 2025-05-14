<?php
require_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header("Content-Type: application/json");

$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=istanbul_proje", "postgres", "elma1145");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Token kontrolü 
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? null;

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    echo json_encode(["status" => "error", "message" => "Geçersiz token. Giriş yapmalısınız."]);
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

// Favoriler
$stmt = $pdo->prepare("SELECT yer_id FROM favoriler WHERE user_id = :user_id ORDER BY tarih DESC");
$stmt->execute(['user_id' => $userId]);
$favoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "favoriler" => $favoriler]);
