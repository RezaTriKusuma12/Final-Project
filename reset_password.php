<?php
require "koneksi.php";
session_start();

// harus dari step 1
if (!isset($_SESSION["reset_user_id"])) {
    header("Location: lupa_password.php");
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPass = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $id = $_SESSION["reset_user_id"];

    $stmt = $koneksi->prepare("UPDATE user SET password=? WHERE id_user=?");
    $stmt->bind_param("si", $newPass, $id);
    $stmt->execute();

    unset($_SESSION["reset_user_id"]);

    $msg = "Password berhasil diubah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

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
        input[type="password"] {
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
        .message {
            background: #ddffdd;
            color: #006400;
            padding: 10px;
            border-left: 4px solid #28a745;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
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
    <h2>Reset Password Baru</h2>

    <?php if ($msg): ?>
        <p class="message"><?= $msg ?></p>
        <p class="text-center"><a href="login.php">Kembali ke Login</a></p>
    <?php else: ?>
    <form method="POST">
        <label>Password Baru</label>
        <input type="password" name="password" required>

        <button type="submit">Simpan Password</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>