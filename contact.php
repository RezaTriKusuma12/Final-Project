<?php
session_start();

// proteksi halaman
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

/* ====================== CLASS DEFINITION ====================== */

class TeamMember {
    public $nama;
    public $nim;
    public $kelas;
    public $email;
    public $hp;
    public $foto;

    public function __construct($nama, $nim, $kelas, $email, $hp, $foto) {
        $this->nama = $nama;
        $this->nim = $nim;
        $this->kelas = $kelas;
        $this->email = $email;
        $this->hp = $hp;
        $this->foto = $foto;
    }

    // fungsi untuk mencetak card HTML
    public function renderCard() {
        return "
        <div class='team-card'>
            <img src='{$this->foto}' class='profile-img' alt='Foto'>
            <h3>{$this->nama}</h3>

            <div class='team-info'>
                <p><span class='label'>NIM:</span> {$this->nim}</p>
                <p><span class='label'>Kelas:</span> {$this->kelas}</p>
                <p><span class='label'>Email:</span> {$this->email}</p>
                <p><span class='label'>No HP:</span> {$this->hp}</p>
            </div>
        </div>
        ";
    }
}

/* ====================== OBJECT PEMBUATAN ====================== */

$team = [
    new TeamMember(
        "Mohamad Nizam Triaji",
        "24416255201169",
        "IF24F",
        "if24.mohamadtriaji@mhs.ubpkarawang.ac.id",
        "0812-2540-0649",
        "nijam.jpeg"
    ),
    new TeamMember(
        "Reza Tri Kusuma",
        "24416255201026",
        "IF24F",
        "if24.rezakusuma@mhs.ubpkarawang.ac.id",
        "0821-8580-3371",
        "reza.jpeg"
    ),
    new TeamMember(
        "Ferdiansyah Putra",
        "24416255201137",
        "IF24F",
        "if24.ferdiansyahputra@mhs.ubpkarawang.ac.id",
        "0895-3378-35072",
        "ferdi.jpeg"
    ),
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Tim</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f8fb;
        }
        .navbar {
            background: #0074D9;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            color: white;
            gap: 25px;
        }
        .navbar img {
            width: 35px;
            height: 35px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        .navbar-title {
            flex: 1;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .container { padding: 30px; }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #003366;
        }
        .team-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .team-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            transition: 0.3s;
            text-align: center;
            border-top: 5px solid #0074D9;
        }
        .team-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.20);
        }
        .profile-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
            border: 3px solid #0074D9;
        }
        .team-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .label {
            font-weight: bold;
            color: #003366;
        }
    </style>
</head>

<body>

<div class="navbar">
    <img src="udarakub.jpg" alt="Logo">
    <a href="index.php">Dashboard</a>

    <div class="navbar-title">Contact</div>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="container">
    <h2>Kontak Tim Pengembang</h2>

    <div class="team-container">
        <?php
            foreach ($team as $member) {
                echo $member->renderCard();
            }
        ?>
    </div>
</div>

</body>
</html>
