<?php
include 'config.php';
session_start();

// Proteksi login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 1. Ambil jumlah transaksi HARI INI
$tgl_sekarang = date('Y-m-d');
$query_trx = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi WHERE tgl_transaksi = '$tgl_sekarang'");
$data_trx = mysqli_fetch_assoc($query_trx);
$total_transaksi_hari_ini = $data_trx['total'] ?? 0;

// 2. Ambil total baju
$query_baju = mysqli_query($conn, "SELECT COUNT(*) as total FROM baju");
$total_barang = ($query_baju) ? mysqli_fetch_assoc($query_baju)['total'] : 0;

// 3. Ambil total pembeli
$query_pembeli = mysqli_query($conn, "SELECT COUNT(*) as total FROM pembeli");
$total_pembeli = ($query_pembeli) ? mysqli_fetch_assoc($query_pembeli)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System | Soft Pink Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Menggunakan font yang lebih lembut */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        :root { 
            --pink-main: #ff85a1;
            --pink-light: #fbb1bd;
            --pink-soft: #f9d1d1;
            --pink-sidebar: #ff99ac;
        }

        body { 
            background-color: #fff5f6; 
            font-family: 'Poppins', sans-serif; 
        }

        /* Sidebar Pink Soft */
        .sidebar { 
            height: 100vh; 
            width: 250px; 
            position: fixed; 
            background: linear-gradient(180deg, var(--pink-sidebar) 0%, #ffc2d1 100%); 
            color: white; 
            padding-top: 20px; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar a { 
            padding: 12px 25px; 
            text-decoration: none; 
            color: rgba(255,255,255,0.9); 
            display: block; 
            transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active { 
            background: rgba(255,255,255,0.2); 
            color: white; 
            border-left: 5px solid #fff; 
            font-weight: 600;
        }

        .content { margin-left: 250px; padding: 40px; }

        /* Card Modern dengan warna Pink Soft */
        .card-custom { 
            border: none; 
            border-radius: 20px; 
            color: #7a4a56; /* Warna teks agak gelap supaya kebaca */
            box-shadow: 0 8px 20px rgba(255, 133, 161, 0.15); 
            transition: 0.3s;
        }

        .card-custom:hover { transform: translateY(-5px); }

        /* Varian Gradasi Pink */
        .bg-pink-1 { background: linear-gradient(45deg, #ffc2d1, #ffe5ec); }
        .bg-pink-2 { background: linear-gradient(45deg, #fbb1bd, #f9d1d1); }
        .bg-pink-3 { background: linear-gradient(45deg, #ff99ac, #fbb1bd); }

        .stat-label { font-size: 0.85rem; font-weight: 600; color: #a36a78; }
        .stat-value { font-size: 2.5rem; font-weight: 700; color: #7a4a56; }
        
        .header-title { color: #7a4a56; font-weight: 700; }
        
        .badge-date {
            background-color: #fff;
            color: var(--pink-sidebar);
            border: 1px solid var(--pink-soft);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="px-4 mb-4 text-center">
        <h4 class="fw-bold">INDO RETAIL</h4>
        <small style="opacity: 0.8;">Soft Admin Panel</small>
    </div>
    <hr style="background-color: rgba(255,255,255,0.3);">
    <a href="dashboard.php" class="active"><i class="fas fa-heart me-2"></i> Dashboard</a>
    <a href="data_baju.php"><i class="fas fa-tshirt me-2"></i> Data Baju</a>
    <a href="data_pembeli.php"><i class="fas fa-user-alt me-2"></i> Data Pembeli</a>
    <a href="transaksi.php"><i class="fas fa-shopping-cart me-2"></i> Transaksi</a>
    <div style="position: absolute; bottom: 20px; width: 100%;">
        <a href="logout.php" class="text-white opacity-75"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="header-title">Ringkasan Toko</h2>
        <div class="badge badge-date p-2 px-3 shadow-sm rounded-pill">
            <i class="far fa-calendar-alt me-2"></i> <?php echo date('d M Y'); ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom bg-pink-1 p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label text-uppercase">Transaksi Hari Ini</h6>
                        <h2 class="stat-value m-0"><?php echo $total_transaksi_hari_ini; ?></h2>
                    </div>
                    <i class="fas fa-receipt fa-2x opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom bg-pink-2 p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label text-uppercase">Koleksi Baju</h6>
                        <h2 class="stat-value m-0"><?php echo $total_barang; ?></h2>
                    </div>
                    <i class="fas fa-tags fa-2x opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom bg-pink-3 p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label text-uppercase">Total Pembeli</h6>
                        <h2 class="stat-value m-0"><?php echo $total_pembeli; ?></h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <div class="card border-0 p-4" style="border-radius: 20px; background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
            <h5 class="header-title" style="font-size: 1.1rem;">Selamat Datang Kembali, <?php echo $_SESSION['username']; ?>! ✨</h5>
            <p class="text-muted small mb-0">Semoga harimu menyenangkan dan penjualanmu laris manis hari ini!</p>
        </div>
    </div>
</div>

</body>
</html>