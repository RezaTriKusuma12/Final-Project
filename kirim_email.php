<?php
// File ini HANYA berisi fungsi
// Tidak boleh ada eksekusi langsung

require "config.php";

/**
 * Ambil semua email user
 */
function getRecipients()
{
    global $koneksi;

    $q = mysqli_query($koneksi, "SELECT email FROM user");
    $recipients = [];

    while ($r = mysqli_fetch_assoc($q)) {
        if (!empty($r['email'])) {
            $recipients[] = ["email" => $r['email']];
        }
    }

    return $recipients;
}

/**
 * Kirim email kondisi BAHAYA
 */
function kirimEmailBahaya($pm25)
{
    global $BREVO_API_KEY;

    $recipients = getRecipients();
    if (empty($recipients)) return;

    $emailContent = [
        "sender" => [
            "email" => "udarakubersih@gmail.com",
            "name"  => "UdaraKu"
        ],
        "to" => $recipients,
        "subject" => "⚠️ PM2.5 Tinggi - Udara Tidak Sehat",
        "htmlContent" => "
            <h2 style='color:red;'>⚠️ Peringatan Kualitas Udara</h2>
            <p>PM2.5 saat ini:</p>
            <h3>{$pm25} µg/m³</h3>
            <p>Status: <b>TIDAK SEHAT</b></p>
        "
    ];

    kirimKeBrevo($emailContent);
}

/**
 * Kirim email kondisi AMAN
 */
function kirimEmailAman($pm25)
{
    global $BREVO_API_KEY;

    $recipients = getRecipients();
    if (empty($recipients)) return;

    $emailContent = [
        "sender" => [
            "email" => "udarakubersih@gmail.com",
            "name"  => "UdaraKu"
        ],
        "to" => $recipients,
        "subject" => "✅ Kualitas Udara Kembali Aman",
        "htmlContent" => "
            <h2 style='color:green;'>✅ Udara Kembali Aman</h2>
            <p>PM2.5 saat ini:</p>
            <h3>{$pm25} µg/m³</h3>
            <p>Status: <b>AMAN</b></p>
        "
    ];

    kirimKeBrevo($emailContent);
}

/**
 * Helper kirim ke Brevo API
 */
function kirimKeBrevo($emailContent)
{
    global $BREVO_API_KEY;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "accept: application/json",
        "api-key: $BREVO_API_KEY",
        "content-type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailContent));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);
    curl_close($ch);
}
