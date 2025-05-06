<?php
require_once "php/veritabani.php"; // Veritabanı bağlantısı

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = htmlspecialchars(trim($_POST["ad"]));
    $aciklama = htmlspecialchars(trim($_POST["aciklama"]));
    $foto_url = htmlspecialchars(trim($_POST["foto_url"]));

    if (!empty($ad) && !empty($aciklama) && !empty($foto_url)) {
        try {
            $sql = "INSERT INTO yerler (ad, aciklama, foto_url) VALUES (:ad, :aciklama, :foto_url)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ad' => $ad,
                ':aciklama' => $aciklama,
                ':foto_url' => $foto_url
            ]);
            echo "<div style='color: green;'>Yeni yer başarıyla eklendi.</div>";
        } catch (PDOException $e) {
            echo "<div style='color: red;'>Veritabanı hatası: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div style='color: red;'>Lütfen tüm alanları doldurun.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yeni Yer Ekle</title>
</head>
<body>
  <h1>Yeni Yer Ekle</h1>
  <form action="" method="POST">
    <label for="ad">Yer Adı:</label><br>
    <input type="text" id="ad" name="ad" required><br><br>

    <label for="aciklama">Açıklama:</label><br>
    <textarea id="aciklama" name="aciklama" required></textarea><br><br>

    <label for="foto_url">Fotoğraf URL:</label><br>
    <input type="text" id="foto_url" name="foto_url" required><br><br>

    <button type="submit">Kaydet</button>
  </form>
  <br>
  <a href="admin.php">⬅ Geri dön</a>
</body>
</html>
