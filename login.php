<?php
session_start();
require_once "koneksi.php";

// Jika sudah login, langsung masuk dashboard
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

$error = "";

// Jika tombol login ditekan
if (isset($_POST["username"])) {

    $username = trim($_POST["username"]);
    $passwordInput = trim($_POST["password"]);

    // Prepared statement (AMAN)
    $sql = "SELECT id_user, nama, username, password, role FROM user WHERE username = ? LIMIT 1";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    // Cek username
    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        // Cek password hash
        if (password_verify($passwordInput, $data["password"])) {

            // Simpan session hanya data penting
            $_SESSION["user"] = [
                "id_user" => $data["id_user"],
                "nama"    => $data["nama"],
                "username"=> $data["username"],
                "role"    => $data["role"]
            ];

            echo "<script>
                alert('Selamat datang, {$data['nama']}');
                location.href='index.php';
            </script>";
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ke Udaraku</title>

    <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #001f3f, #0074D9);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      width: 350px;
      text-align: center;
      color: white;
    }

    .login-container img {
      width: 100px;
      margin-bottom: 15px;
    }

    .login-container h2 {
      margin-bottom: 25px;
      color: #fff;
      letter-spacing: 1px;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      font-size: 14px;
      display: block;
      margin-bottom: 5px;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      outline: none;
      font-size: 14px;
    }

    .btn-login {
      width: 100%;
      background: #00bfff;
      border: none;
      padding: 10px;
      color: white;
      border-radius: 8px;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-login:hover {
      background: #0099cc;
    }

    .link {
      margin-top: 15px;
      display: block;
      font-size: 14px;
      color: #ccc;
    }

    .link a {
      color: #00bfff;
      text-decoration: none;
      font-weight: 500;
    }

    .link a:hover {
      text-decoration: underline;
    }
  </style>

</head>
<body>

<div class="login-container">
    <img src="udaraku.jpg" alt="Logo Udaraku">
    <h2>Login ke Udaraku</h2>

    <!-- Tampilkan error jika ada -->
    <?php if (!empty($error)): ?>
        <div style="background:#ff4444; padding:10px; border-radius:8px; margin-bottom:10px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn-login" type="submit">Login</button>

        <span class="link">
            Belum punya akun? <a href="daftar.php">Daftar</a>
        </span>

    </form>
</div>

</body>
</html>