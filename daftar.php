<?php
require_once __DIR__ . "/app/DB.php";
require_once __DIR__ . "/app/UserModel.php";

$db = new DB();
$userModel = new UserModel($db->getConnection());

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email    = trim($_POST["email"]);

    // Cek username sudah ada atau belum
    if ($userModel->isUsernameExists($username)) {
        $message = "Username sudah digunakan, silakan pilih yang lain.";
    } else {
        $created = $userModel->registerUser($nama, $username, $password, $email);

        if ($created) {
            $message = "Pendaftaran berhasil! Silakan login.";
        } else {
            $message = "Pendaftaran gagal. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Udaraku</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url('udaraku.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
        }

        .register-card {
            position: relative;
            background-color: rgba(255, 255, 255, 0.93);
            border-radius: 20px;
            width: 380px;
            padding: 40px 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            text-align: center;
            overflow: hidden;
        }

        .register-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('logo_udaraku.png') no-repeat center center;
            background-size: 130px;
            opacity: 0.008;
        }

        .register-card * {
            position: relative;
            z-index: 1;
        }

        .register-card h2 {
            color: #003366;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .login-link a {
            color: #007BFF;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        table td {
            padding: 6px;
        }

        table input {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
        }

        button {
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <div class="register-card">
        <h2>Daftar Akun Udaraku</h2>

        <?php if ($message): ?>
            <div style="background:#eee;padding:10px;border-radius:5px;margin-bottom:10px;">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <table align="center">
                <tr>
                    <td colspan="2" align="center"><h3>Pendaftaran User</h3></td>
                </tr>

                <tr>
                    <td>Nama</td>
                    <td><input type="text" name="nama" required></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" required></td>
                </tr>

                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" required></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td>
                        <button type="submit">Daftar User</button>
                        <a href="login.php">Login</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>

</body>

</html>