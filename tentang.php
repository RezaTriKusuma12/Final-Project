<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Polusi Udara & Peran Website Udaraku — Blog</title>
  <meta name="description" content="Artikel: Polusi udara dan peran Website Udaraku untuk monitoring kualitas udara di Kampus UBP Karawang." />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#f4f8fb;
      --card:#ffffff;
      --primary:#0074D9;
      --accent:#00bfff;
      --muted:#6b7280;
      --text:#0f172a;
      --radius:12px;
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial; background:var(--bg); color:var(--text); -webkit-font-smoothing:antialiased}
    a{color:var(--primary); text-decoration:none}
    .navbar{
      background:var(--primary);
      color:#fff;
      padding:14px 20px;
      display:flex;
      align-items:center;
      gap:14px;
    }
    .brand{display:flex;align-items:center;gap:12px}
    .brand img{width:38px;height:38px;border-radius:8px;object-fit:cover}
    .brand .name{font-weight:700;letter-spacing:0.2px}
    .nav-title{flex:1;text-align:center;font-weight:600}
    .nav-actions{display:flex;gap:10px;align-items:center}
    .btn{display:inline-block;padding:8px 12px;border-radius:10px;background:var(--accent);color:#fff;font-weight:600}
    .btn-outline{background:transparent;border:1px solid rgba(255,255,255,0.2);color:#fff;padding:8px 10px;border-radius:10px}

    /* container */
    .wrap{max-width:1100px;margin:28px auto;padding:0 18px;display:grid;grid-template-columns:2fr 1fr;gap:24px}
    @media (max-width:900px){ .wrap{grid-template-columns:1fr} .nav-title{display:none} }

    /* post */
    .post-card{background:var(--card);padding:22px;border-radius:var(--radius);box-shadow:0 8px 30px rgba(2,6,23,0.06)}
    .post-card h1{margin:6px 0 6px;font-size:26px}
    .meta{color:var(--muted);font-size:14px;margin-bottom:12px}
    .lead{color:#334155;margin-bottom:14px;line-height:1.7}
    .post-card img.hero{width:100%;border-radius:10px;margin:12px 0 18px;display:block}

    .post-card h3{margin-top:18px}
    .post-card ul{margin-left:18px}

    /* sidebar */
    .sidebar .card{background:var(--card);padding:16px;border-radius:12px;box-shadow:0 8px 30px rgba(2,6,23,0.06);margin-bottom:16px}
    .recent-item{display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #eef3fa}
    .recent-item:last-child{border-bottom:0}
    .subscribe input[type="email"]{width:100%;padding:10px;border-radius:8px;border:1px solid #e6eef6;margin-bottom:8px}

    /* footer within page */
    .back{display:inline-block;margin-top:14px;padding:8px 12px;background:#eef6ff;border-radius:8px;color:var(--primary)}
    .muted{color:var(--muted);font-size:14px}
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <header class="navbar" role="banner">
    <div class="brand">
      <img src="udaraku.jpeg" alt="Logo Udaraku">
      <div class="name">Udaraku</div>
    </div>

    <div class="nav-title">Real Time Weather Web — UBP Karawang</div>

    <div class="nav-actions">
      <a href="index.php" class="btn-outline" title="Kembali ke Dashboard">Dashboard</a>
      <a href="logout.php" class="btn">Logout</a>
    </div>
  </header>

  <!-- CONTENT -->
  <main class="wrap" role="main">

    <!-- POST -->
    <article class="post-card" aria-labelledby="judul-post">
      <h1 id="judul-post">Polusi Udara dan Peran Website Udaraku dalam Monitoring Kualitas Udara Kampus UBP Karawang</h1>
      <div class="meta">Dipublikasikan: <strong><time datetime="<?php echo date('Y-m-d'); ?>"><?php echo date('j F Y'); ?></time></strong> &nbsp;•&nbsp; Penulis: Tim Udaraku</div>

      <p class="lead"><strong>Apa Itu Polusi Udara?</strong><br>
      Polusi udara terjadi ketika atmosfer terkontaminasi oleh partikel atau gas berbahaya seperti <em>PM2.5, PM10, CO, NO₂</em>, dan O₃. Di wilayah industri dan padat lalu lintas seperti Karawang, sumber polusi meliputi pabrik, kendaraan bermotor, pembakaran sampah, dan konstruksi. Kondisi udara yang buruk berpengaruh langsung pada kesehatan dan kenyamanan aktivitas belajar di kampus.</p>

      <!-- hero image (optional) -->
      <img class="hero" src="https://images.unsplash.com/photo-1501706362039-c6e809fc3ca1?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3&s=88b6d7a7c3d0b754f0e8f6f7d5b8d7f4" alt="Ilustrasi polusi udara">

      <h3>Dampak Polusi Udara bagi Mahasiswa</h3>
      <p>Polusi udara mengakibatkan gangguan kesehatan seperti iritasi mata, batuk, sesak napas, serta menurunkan konsentrasi belajar. Kegiatan luar ruangan—seperti olahraga atau praktikum lapangan—bisa terganggu pada saat AQI tinggi. Oleh karena itu, informasi kualitas udara yang akurat di setiap lokasi kampus sangat penting.</p>

      <h3>Website Udaraku: Solusi Monitoring Lokal</h3>
      <p>Website <strong>Udaraku</strong> dibuat untuk memantau kualitas udara di lingkungan <em>Universitas Buana Perjuangan Karawang</em>. Layanan utama yang disediakan:</p>
      <ul>
        <li><strong>Mapping Gedung:</strong> Peta interaktif yang menunjukkan gedung-gedung kampus (Gedung A–F, Rektorat, Perpustakaan) dan kondisi AQI di setiap titik.</li>
        <li><strong>Search Area:</strong> Fitur pencarian lokasi untuk mengecek kondisi udara di titik tertentu.</li>
        <li><strong>History & Grafik:</strong> Visualisasi tren AQI per jam/hari menggunakan Chart.js.</li>
        <li><strong>Kategori & Saran:</strong> Klasifikasi nilai AQI menjadi Baik / Sedang / Tidak Sehat beserta rekomendasi tindakan.</li>
      </ul>

      <h3>Bagaimana Sistem Bekerja?</h3>
      <p>Sederhananya: sensor membaca data polutan di titik-titik strategis → data dikirim ke server → server menyimpan & menyajikan ke website → pengguna melihat peta dan grafik secara real-time. Website dapat dikembangkan lebih lanjut dengan notifikasi otomatis jika kualitas udara menurun.</p>

      <h3>Manfaat untuk Kampus</h3>
      <p>Dengan sistem monitoring, kampus bisa:</p>
      <ul>
        <li>Mengambil keputusan operasional (mis. menunda kegiatan luar ruangan).</li>
        <li>Mendukung riset dosen dan mahasiswa dengan data historis.</li>
        <li>Meningkatkan kesadaran civitas tentang pentingnya lingkungan sehat.</li>
      </ul>

      <p class="muted">Udaraku bukan hanya alat informasi — ini langkah kecil menuju kampus yang lebih sehat dan peduli lingkungan.</p>

      <a class="back" href="index.php" title="Kembali ke Dashboard">← Kembali ke Dashboard</a>
    </article>

    <!-- SIDEBAR -->
    <aside class="sidebar" aria-label="Sidebar">
      <div class="card">
        <h4>Tentang Udaraku</h4>
        <p class="muted">Platform monitoring kualitas udara untuk Kampus UBP Karawang. Menyajikan peta, grafik, dan informasi real-time agar civitas akademika selalu tahu kondisi udara di lingkungan kampus.</p>
      </div>

      <div class="card">
        <h4>Artikel Terkait</h4>

        <div class="recent-item">
          <div>
            <strong>Apa Itu AQI?</strong>
            <div class="muted" style="font-size:13px">Panduan singkat untuk mahasiswa</div>
          </div>
        </div>

        <div class="recent-item">
          <div>
            <strong>5 Cara Kurangi Polusi di Kampus</strong>
            <div class="muted" style="font-size:13px">Langkah praktis yang bisa kamu coba</div>
          </div>
        </div>

        <div class="recent-item">
          <div>
            <strong>Sensor Udara: Cara Kerjanya</strong>
            <div class="muted" style="font-size:13px">Teknologi & instalasi</div>
          </div>
        </div>
      </div>

      <div class="card subscribe">
        <h4>Subscribe</h4>
        <p class="muted" style="font-size:14px">Dapatkan update mingguan tentang kualitas udara kampus.</p>
        <form method="post" action="#">
          <input type="email" name="email" placeholder="Email kamu" required>
          <div style="height:8px"></div>
          <button type="submit" class="btn" style="width:100%">Subscribe</button>
        </form>
      </div>

      <div class="card">
        <h4>Kontak</h4>
        <p class="muted" style="font-size:14px">Butuh bantuan atau ingin pasang sensor? Hubungi tim Udaraku.</p>
        <p style="margin:8px 0"><strong>Email:</strong> <a href="mailto:udaraku@ubp.ac.id">udaraku@ubp.ac.id</a></p>
      </div>
    </aside>

  </main>

</body>
</html>