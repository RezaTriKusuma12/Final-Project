<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Data</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, sans-serif;
        }

        body {
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 480px;
            margin: 80px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background: #198754;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: #157347;
            transform: translateY(-2px);
        }

        /* ===== SPINNER ===== */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #eee;
            border-top: 6px solid #198754;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ===== NOTIFIKASI ===== */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #198754;
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            display: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            font-size: 14px;
            z-index: 9999;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>📁 Export Data ke CSV</h2>
    <p>Klik tombol berikut untuk export + penyimpanan otomatis ke server:</p>

    <a class="btn export-btn" href="export/export_data_udara.php">Export Data Udara</a>
    <a class="btn export-btn" href="export/export_history_polusi.php">Export History Polusi</a>
    <a class="btn export-btn" href="export/export_prediksi_polusi.php">Export Prediksi Polusi</a>
    <a class="btn export-btn" href="export/export_user.php">Export User</a>
</div>

<!-- SPINNER -->
<div class="overlay" id="loading">
    <div class="spinner"></div>
</div>

<!-- TOAST -->
<div class="toast" id="toast">✅ Export berhasil!</div>

<script>
    const buttons = document.querySelectorAll('.export-btn');
    const loading = document.getElementById('loading');
    const toast = document.getElementById('toast');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            loading.style.display = 'flex';

            // Notifikasi sukses setelah beberapa detik
            setTimeout(() => {
                loading.style.display = 'none';
                toast.style.display = 'block';

                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3000);
            }, 2000);
        });
    });
</script>

</body>
</html>
