<?php
header("Content-Type: application/json");

echo json_encode([
    "status" => "success",
    "message" => "Çıkış başarılı. Token'ınızı tarayıcınızdan siliniz."
]);
