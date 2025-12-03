<?php
$code = trim($_GET['c'] ?? $_SERVER['REQUEST_URI'], "/");

// Periksa cache dulu!
$cacheFile = __DIR__ . "/cache/$code.json";

if (file_exists($cacheFile)) {
    $data = json_decode(file_get_contents($cacheFile), true);
    header("Location: " . $data['url']);
    exit;
}

// Jika cache tidak ada → baru buka database
require_once "config.php";

$stmt = $pdo->prepare("SELECT url FROM links WHERE code = ?");
$stmt->execute([$code]);

if ($stmt->rowCount() == 0) {
    die("Shortlink tidak ditemukan");
}

$data = $stmt->fetch();

// Buat lagi cache (permanen)
file_put_contents("cache/$code.json", json_encode(["url" => $data['url']]));

header("Location: " . $data['url']);
exit;
