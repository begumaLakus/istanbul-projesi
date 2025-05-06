<?php
require_once "veritabani.php"; // php klasörü içindeki veritabani.php'yi çağırıyoruz

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $sql = "DELETE FROM yerler WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Silme başarılıysa yönlendirme yap
        header("Location: ../admin.php?sil=basarili");
        exit;
    } catch (PDOException $e) {
        echo "<div style='color: red;'>Silme hatası: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div style='color: orange;'>Geçersiz ID parametresi.</div>";
}
?>
