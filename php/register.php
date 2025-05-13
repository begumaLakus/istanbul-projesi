<?php
require_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Content-Type: application/json");

// Veritabanı bağlantısı (Güncel bilgiler)
$host = 'localhost';
$dbname = 'istanbul_proje';
$user = 'postgres';
$pass = 'elma1145';

try {
    $pdo = new PDO("pgsql:host=$host;port=5432;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);

    $isim = $data['isim'] ?? null;
    $mail = $data['mail'] ?? null;
    $sifre = $data['sifre'] ?? null;

    if (!$isim || !$mail || !$sifre) {
        echo json_encode(["status" => "error", "message" => "Tüm alanları doldurunuz."]);
        exit;
    }

    // Mail kontrolü
    $stmt = $pdo->prepare("SELECT * FROM users WHERE mail = :mail");
    $stmt->execute(['mail' => $mail]);
    $existing = $stmt->fetch();

    if ($existing) {
        echo json_encode(["status" => "error", "message" => "Bu e-posta zaten kayıtlı."]);
        exit;
    }

    // Şifre hash
    $hashedPassword = password_hash($sifre, PASSWORD_BCRYPT);

    // Veritabanına ekledim 
    $insert = $pdo->prepare("INSERT INTO users (isim, mail, sifre) VALUES (:isim, :mail, :sifre)");
    $insert->execute([
        'isim' => $isim,
        'mail' => $mail,
        'sifre' => $hashedPassword
    ]);

    // JWT token ürettim
    $secretKey = "benim_sakli_anahtarim_123";
    $payload = [
        "iss" => "http://localhost",
        "iat" => time(),
        "exp" => time() + (60*60*24),
        "user_id" => $pdo->lastInsertId(),
        "mail" => $mail
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    echo json_encode([
        "status" => "success",
        "message" => "Kayıt başarılı.",
        "token" => $jwt
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Hata: " . $e->getMessage()
    ]);
}
