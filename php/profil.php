<?php
require_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header("Content-Type: application/json");

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

    require_once "veritabani.php";

    $stmt = $pdo->prepare("SELECT id, isim, mail FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(["status" => "success", "user" => $user]);
    } else {
        echo json_encode(["status" => "error", "message" => "Kullanıcı bulunamadı."]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Token doğrulanamadı: " . $e->getMessage()]);
}
