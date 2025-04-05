<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$domain = $_GET['domain'] ?? '';

if (!$domain) {
    echo json_encode(["status" => "error", "message" => "Domain tidak diberikan."]);
    exit;
}

$api_url = "https://lisensi.yusupmadcani.my.id/api/cek_lisensi.php?domain=" . urlencode($domain);
$response = file_get_contents($api_url);

if ($response === FALSE) {
    echo json_encode(["status" => "error", "message" => "Gagal menghubungi API lisensi."]);
    exit;
}

echo $response;
?>
