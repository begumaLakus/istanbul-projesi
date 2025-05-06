<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "veritabani.php";

    // Girdi temizliği ve kontrolü
    $ad = htmlspecialchars(trim($_POST["ad"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $mesaj = htmlspecialchars(trim($_POST["mesaj"]));

    // Tüm alanlar dolu mu?
    if (!empty($ad) && !empty($email) && !empty($mesaj)) {
        try {
            $sql = "INSERT INTO mesajlar (ad, email, mesaj) VALUES (:ad, :email, :mesaj)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ad' => $ad,
                ':email' => $email,
                ':mesaj' => $mesaj
            ]);
            echo "<div style='color: green;'>Mesaj başarıyla gönderildi.</div>";
        } catch (PDOException $e) {
            echo "<div style='color: red;'>Veritabanı hatası: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div style='color: red;'>Lütfen tüm alanları doldurunuz.</div>";
    }
} else {
    echo "<div style='color: orange;'>Geçersiz istek.</div>";
}
?>
