<?php
require_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Content-Type: application/json");

$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=istanbul_proje", "postgres", "elma1145");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = json_decode(file_get_contents("php://input"), true);

$mail = $data['mail'] ?? null;
$sifre = $data['sifre'] ?? null;

if (!$mail || !$sifre) {
    echo json_encode(["status" => "error", "message" => "Mail ve şifre zorunludur."]);
    exit;
}

// Kullanıcıyı kontrol et
$stmt = $pdo->prepare("SELECT * FROM users WHERE mail = :mail");
$stmt->execute(['mail' => $mail]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "Kullanıcı bulunamadı."]);
    exit;
}

// Şifre doğrulama
if (!password_verify($sifre, $user['sifre'])) {
    echo json_encode(["status" => "error", "message" => "Şifre hatalı."]);
    exit;
}

// JWT token üret
$secretKey = "benim_sakli_anahtarim_123";
$payload = [
    "iss" => "http://localhost",
    "iat" => time(),
    "exp" => time() + (60*60*24), // 1 gün geçerli
    "user_id" => $user['id'],
    "mail" => $user['mail']
];

$jwt = JWT::encode($payload, $secretKey, 'HS256');

echo json_encode([
    "status" => "success",
    "message" => "Giriş başarılı.",
    "token" => $jwt
]);
