<?php
require "koneksi.php";
session_start();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];

    $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // simpan ID user ke session
        $_SESSION["reset_user_id"] = $user["id_user"];

        header("Location: reset_password.php");
        exit;
    } else {
        $msg = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>

    <!-- CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 360px;
            margin: 80px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-size: 14px;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            width: 100%;
            background: #4CAF50;
            padding: 10px;
            color: white;
            font-size: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .error {
            background: #ffdddd;
            color: #d8000c;
            padding: 10px;
            border-left: 4px solid #d8000c;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .text-center {
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

<div class="container">
    <h2>Cari Akun</h2>

    <?php if ($msg): ?>
        <p class="error"><?= $msg ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Masukkan Username</label>
        <input type="text" name="username" required>

        <button type="submit">Lanjutkan</button>

        <p class="text-center" style="margin-top:10px;">
            <a href="login.php">Kembali ke Login</a>
        </p>
    </form>
</div>

</body>
</html>