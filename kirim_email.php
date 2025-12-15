<?php
require 'config.php';

// Ambil semua email user
$q = mysqli_query($koneksi, "SELECT email FROM user");
$recipients = [];

while ($r = mysqli_fetch_assoc($q)) {
    if (!empty($r['email'])) {
        $recipients[] = ["email" => $r['email']];
    }
}

if (empty($recipients)) {
    echo "Tidak ada email penerima.";
    die();
}

// Isi email
$emailContent = [
    "sender" => ["email" => "udarakubersih@gmail.com", "name" => "Sistem Monitoring Udara"],
    "to" => $recipients,
    "subject" => "⚠️ Peringatan Kualitas Udara Tinggi!",
    "htmlContent" => "
        <h2 style='color:red;'>Peringatan PM2.5 Melebihi Batas!</h2>
        <p>Nilai PM2.5 terdeteksi lebih tinggi dari ambang aman.</p>
        <p>Segera cek dashboard untuk detail lebih lengkap.</p>
    "
];

// Kirim lewat Brevo API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "accept: application/json",
    "api-key: $BREVO_API_KEY",
    "content-type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailContent));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
