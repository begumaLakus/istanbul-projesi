<?php
require_once "veritabani.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM yerler WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $yer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$yer) {
            echo "<div style='color:red;'>Kayıt bulunamadı.</div>";
            exit;
        }
    } catch (PDOException $e) {
        echo "Veri çekme hatası: " . $e->getMessage();
        exit;
    }
} else {
    echo "<div style='color:orange;'>Geçersiz ID parametresi.</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = htmlspecialchars(trim($_POST["ad"]));
    $aciklama = htmlspecialchars(trim($_POST["aciklama"]));
    $foto_url = htmlspecialchars(trim($_POST["foto_url"]));

    if (!empty($ad) && !empty($aciklama) && !empty($foto_url)) {
        try {
            $stmt = $pdo->prepare("UPDATE yerler SET ad = :ad, aciklama = :aciklama, foto_url = :foto_url WHERE id = :id");
            $stmt->execute([
                ':ad' => $ad,
                ':aciklama' => $aciklama,
                ':foto_url' => $foto_url,
                ':id' => $id
            ]);
            header("Location: ../admin.php?guncelle=basarili");
            exit;
        } catch (PDOException $e) {
            echo "Güncelleme hatası: " . $e->getMessage();
        }
    } else {
        echo "<div style='color:red;'>Tüm alanlar doldurulmalı.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yer Güncelle</title>
</head>
<body>
  <h1>Yer Güncelle</h1>
  <form method="POST">
    <label for="ad">Yer Adı:</label><br>
    <input type="text" id="ad" name="ad" value="<?= htmlspecialchars($yer['ad']) ?>" required><br><br>

    <label for="aciklama">Açıklama:</label><br>
    <textarea id="aciklama" name="aciklama" required><?= htmlspecialchars($yer['aciklama']) ?></textarea><br><br>

    <label for="foto_url">Fotoğraf URL:</label><br>
    <input type="text" id="foto_url" name="foto_url" value="<?= htmlspecialchars($yer['foto_url']) ?>" required><br><br>

    <button type="submit">Güncelle</button>
  </form>
  <br>
  <a href="../admin.php">⬅ Geri dön</a>
</body>
</html>
