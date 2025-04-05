<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = '../data/license.json';
    $data = json_decode(file_get_contents($file), true) ?? [];

    $new = [
        "id" => $_POST['id'],
        "domain" => $_POST['domain'],
        "status" => $_POST['status'],
        "created_at" => date("Y-m-d"),
        "expired_at" => $_POST['expired_at']
    ];

    // Hapus data lama dengan ID sama (replace)
    $data = array_filter($data, fn($x) => $x['id'] !== $new['id']);
    $data[] = $new;

    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));
}

header("Location: index.php");
exit;
