<?php
// dashboard/perpanjang.php

if (!isset($_POST['id'])) {
    die("ID lisensi tidak ditemukan.");
}

$id = $_POST['id'];
$file = '../data/license.json';

$licenses = json_decode(file_get_contents($file), true);

foreach ($licenses as &$license) {
    if ($license['id'] === $id) {
        $new_expired = date('Y-m-d', strtotime('+1 year'));
        $license['expired_at'] = $new_expired;
        $license['status'] = 'active';
        break;
    }
}

file_put_contents($file, json_encode($licenses, JSON_PRETTY_PRINT));
header("Location: ../index.php");
exit;
