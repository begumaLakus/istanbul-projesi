<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT * FROM yerler");
    echo "<h3>Veritabanı bağlantısı başarılı! 🎉</h3><ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($row['ad']) . "</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "Sorgu hatası: " . $e->getMessage();
}
?>
