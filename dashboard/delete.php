<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $file = '../data/license.json';
    $licenses = json_decode(file_get_contents($file), true);

    // Filter array, buang yang ID-nya cocok
    $licenses = array_filter($licenses, function ($item) use ($id) {
        return $item['id'] !== $id;
    });

    // Simpan ulang ke file
    file_put_contents($file, json_encode(array_values($licenses), JSON_PRETTY_PRINT));
}

header("Location: index.php");
exit;
