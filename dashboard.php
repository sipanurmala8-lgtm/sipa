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
$query_trx = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi WHERE tanggal = '$tgl_sekarang'");
$data_trx = mysqli_fetch_assoc($query_trx);
$total_transaksi_hari_ini = $data_trx['total'];

// 2. Ambil total barang & pembeli untuk pemanis dashboard
$total_barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM barang"))['total'];
$total_pembeli = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembeli"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-color: #ffffff;
        }
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        
        /* Sidebar */
        .sidebar {
            height: 100vh; width: 250px; position: fixed;
            background: var(--bg-gradient); color: white;
            padding-top: 20px; transition: 0.3s;
        }
        .sidebar a {
            padding: 12px 25px; text-decoration: none; font-size: 16px;
            color: rgba(255,255,255,0.8); display: block; transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.2); color: white; border-left: 4px solid #fff;
        }

        /* Main Content */
        .content { margin-left: 250px; padding: 30px; }
        
        /* Card Dashboard */
        .card-custom {
            border: none; border-radius: 15px; color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: 0.3s;
        }
        .card-custom:hover { transform: translateY(-5px); }
        .bg-trx { background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); }
        .bg-barang { background: linear-gradient(45deg, #5ee7df 0%, #b490d1 100%); }
        .bg-pembeli { background: linear-gradient(45deg, #f6d365 0%, #fda085 100%); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="px-4 mb-4">
        <h4 class="fw-bold">INDO RETAIL</h4>
        <small>Control Panel</small>
    </div>
    <hr>
    <a href="dashboard.php" class="active"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
    <a href="data_baju.php"><i class="fas fa-boxes me-2"></i> Nama Barang</a>
    <a href="data_pembeli.php"><i class="fas fa-user-friends me-2"></i> Data Pembeli</a>
    <a href="transaksi.php"><i class="fas fa-cash-register me-2"></i> Transaksi</a>
    <div style="position: absolute; bottom: 20px; width: 100%;">
        <a href="logout.php" class="text-warning"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary">Dashboard Overview</h2>
        <div class="badge bg-white text-dark p-2 shadow-sm">
            <i class="far fa-calendar-alt me-2"></i> <?php echo date('d M Y'); ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom bg-trx p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold">Transaksi Hari Ini</h6>
                        <h2 class="display-5 fw-bold"><?php echo $total_transaksi_hari_ini; ?></h2>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                </div>
                <hr>
                <small><i class="fas fa-sync-alt me-1"></i> Data diperbarui otomatis</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom bg-barang p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold">Item Barang</h6>
                        <h2 class="display-5 fw-bold"><?php echo $total_barang; ?></h2>
                    </div>
                    <i class="fas fa-box-open fa-3x opacity-50"></i>
                </div>
                <hr>
                <small>Total stok tersedia</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom bg-pembeli p-4 text-dark">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold">Total Pembeli</h6>
                        <h2 class="display-5 fw-bold"><?php echo $total_pembeli; ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
                <hr>
                <small>Pelanggan terdaftar</small>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
            <h5 class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i> Panduan Cepat</h5>
            <p class="text-muted">Halo <strong><?php echo $_SESSION['username']; ?></strong>, selamat datang kembali. Hari ini terdapat <strong><?php echo $total_transaksi_hari_ini; ?> transaksi</strong> baru. Jangan lupa untuk selalu mengecek stok barang di menu Data Barang.</p>
        </div>
    </div>
</div>

</body>
</html>