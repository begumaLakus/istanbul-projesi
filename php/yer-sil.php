<?php
require_once "veritabani.php";

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = (int) $_POST['id'];

    try {
        $sql = "DELETE FROM yerler WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        echo json_encode(['success' => true, 'message' => 'Yer başarıyla silindi.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Geçersiz ID']);
}
?>
