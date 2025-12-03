<?php
// Koneksi database (HANYA untuk generate link, bukan setiap klik!)
try {
    $pdo = new PDO(
        "mysql:host=srv1365.hstgr.io;dbname=u145588169_tempatcuan;charset=utf8",
        "u145588169_tempatcuan",
        "Y#OsU9~v",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
