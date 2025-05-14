<?php
require_once "php/veritabani.php";

try {
    $sorgu = $pdo->query("SELECT * FROM yerler");
    $yerler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veri çekme hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yönetim Paneli</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    td, th {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    a {
      color: #007BFF;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    img {
      max-width: 100px;
      height: auto;
    }
  </style>
</head>
<body>
  <h1>Yönetim Paneli – Yer Listesi</h1>

  <table>
    <tr>
      <th>ID</th>
      <th>Ad</th>
      <th>Açıklama</th>
      <th>Görsel</th>
      <th>İşlemler</th>
    </tr>

    <?php foreach ($yerler as $yer): ?>
    <tr>
      <td><?= htmlspecialchars($yer['id']) ?></td>
      <td><?= htmlspecialchars($yer['ad']) ?></td>
      <td><?= htmlspecialchars($yer['aciklama']) ?></td>
      <td>
        <?php if (!empty($yer['foto_url'])): ?>
          <img src="<?= htmlspecialchars($yer['foto_url']) ?>" alt="Foto">
        <?php else: ?>
          <em>Görsel yok</em>
        <?php endif; ?>
      </td>
      <td>
        <a href="php/yer-guncelle.php?id=<?= $yer['id'] ?>">Güncelle</a> |
        <a href="#" onclick="yerSil(<?= $yer['id'] ?>, this.closest('tr')); return false;">Sil</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

  <script>
    function yerSil(id, rowElement) {
      if (confirm("Silmek istediğinize emin misiniz?")) {
        fetch('php/yer-sil.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            rowElement.remove(); 
          } else {
            alert("Silinemedi: " + data.error);
          }
        })
        .catch(error => {
          alert("Bir hata oluştu: " + error);
        });
      }
    }
  </script>
</body>
</html>
