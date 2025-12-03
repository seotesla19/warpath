<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

function generateCode($length = 6) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url']);

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $error = "URL tidak valid.";
    } else {

        // Generate kode unik
        do {
            $code = generateCode(6);
            $check = $pdo->prepare("SELECT id FROM links WHERE code = ?");
            $check->execute([$code]);
        } while ($check->rowCount() > 0);

        // Simpan ke database
        $stmt = $pdo->prepare("INSERT INTO links (code, url) VALUES (?, ?)");
        $stmt->execute([$code, $url]);

        // Buat file cache JSON
        if (!is_dir("cache")) mkdir("cache", 0777, true);
        file_put_contents("cache/$code.json", json_encode(["url" => $url]));

        $shortLink = "https://" . $_SERVER['HTTP_HOST'] . "/$code";
    }
}
?>
<!DOCTYPE html>
<html>
<body>
<form method="POST">
    <input type="text" name="url" placeholder="Masukkan URL">
    <button type="submit">Generate</button>
</form>

<?php if(isset($shortLink)): ?>
    <p><b>Shortlink:</b> <a href="<?= $shortLink ?>" target="_blank"><?= $shortLink ?></a></p>
<?php endif; ?>

<?php if(isset($error)): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>
</body>
</html>
