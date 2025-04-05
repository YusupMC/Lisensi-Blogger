<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Ambil domain dari parameter GET
$domain = $_GET['domain'] ?? '';

// Load file JSON
$data = json_decode(file_get_contents("../data/license.json"), true);

// Cari domain di dalam array
$lisensi = array_filter($data, function($item) use ($domain) {
  return $item['domain'] === $domain;
});

// Ambil lisensi pertama yang cocok
$lisensi = array_values($lisensi);

// Kalau ketemu, kirim status
if (count($lisensi) > 0) {
  echo json_encode([
    "status" => $lisensi[0]['status'],
    "expired_at" => $lisensi[0]['expired_at']
  ]);
} else {
  echo json_encode([
    "status" => "inactive",
    "message" => "Lisensi tidak ditemukan"
  ]);
}
