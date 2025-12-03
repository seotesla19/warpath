<?php
session_start();

// Password hash (aman)
$stored_hash = '$2y$10$QVbrPEMFjIKIccRpwlYrEuz6tlYbpZkvS1GIUhcN/1YS58To.oFLa';

// Lama sesi (10 menit)
$session_lifetime = 600;

// Jika sudah login
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_lifetime) {
        session_unset();
        session_destroy();
        header("Location: login.php?expired=1");
        exit;
    }

    $_SESSION['last_activity'] = time();
    header("Location: index.php");
    exit;
}

// Jika form dipost
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['password'];

    if (password_verify($input, $stored_hash)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();
        header("Location: index.php");
        exit;
    } else {
        $error = "Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: url('https://i.redd.it/bpxxqqvps4h91.gif') no-repeat center center fixed;
        background-size: cover;
        font-family: Arial, sans-serif;
    }

    .login-box {
        background: rgba(0, 0, 0, 0.75);
        padding: 25px 35px;
        border-radius: 12px;
        border: 2px solid #00ff3c;
        box-shadow: 0 0 25px #00ff3c80;
        text-align: center;
        min-width: 300px;
    }

    .login-box label {
        color: #00ff3c;
        font-size: 15px;
        display: block;
        text-align: left;
        margin-bottom: 6px;
    }

    .login-box input[type="password"] {
        width: 100%;
        padding: 10px;
        font-size: 15px;
        border: 2px solid #00ff3c;
        background: #111;
        color: #00ff3c;
        border-radius: 6px;
        margin-bottom: 15px;
        outline: none;
    }

    .login-box button {
        width: 100%;
        padding: 12px;
        background: #00ff3c;
        color: black;
        font-weight: bold;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0 0 12px #00ff3c;
    }

    .login-box button:hover {
        filter: brightness(1.2);
    }

    .error {
        margin-top: 10px;
        color: #ff4444;
        font-weight: bold;
    }
</style>
</head>

<body>

<div class="login-box">
    <form method="POST">
        <label>Password :</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>

        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (isset($_GET['expired'])) echo "<div class='error'>Sesi berakhir, login kembali.</div>"; ?>
    </form>
</div>

</body>
</html>
