<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "veritabani.php";

    // JSON'dan veri oku (fetch kullanıldığı için JSON gelir)
    $data = json_decode(file_get_contents("php://input"), true);

    $ad = htmlspecialchars(trim($data["ad"] ?? ""));
    $email = filter_var(trim($data["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $mesaj = htmlspecialchars(trim($data["mesaj"] ?? ""));

    if (!empty($ad) && !empty($email) && !empty($mesaj)) {
        try {
            $sql = "INSERT INTO mesajlar (ad, email, mesaj) VALUES (:ad, :email, :mesaj)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ad' => $ad,
                ':email' => $email,
                ':mesaj' => $mesaj
            ]);
            echo json_encode(["status" => "success", "message" => "Mesaj başarıyla gönderildi."]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Veritabanı hatası: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Lütfen tüm alanları doldurunuz."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz istek."]);
}
