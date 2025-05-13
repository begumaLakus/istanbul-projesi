<?php
header("Content-Type: application/json");

// JWT'de sunucu taraflı oturum yok, token istemci tarafında silinmelidir.
echo json_encode([
    "status" => "success",
    "message" => "Çıkış başarılı. Token'ınızı tarayıcınızdan siliniz."
]);
