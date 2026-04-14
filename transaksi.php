<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// --- FUNGSI SIMPAN (CREATE) ---
if (isset($_POST['tambah_transaksi'])) {
    $id_pembeli = $_POST['id_pembeli'];
    $id_baju = $_POST['id_baju'];
    $tgl = $_POST['tgl_transaksi'];
    $jumlah = $_POST['jumlah'];

    // Ambil harga baju dari tabel baju
    $result_harga = mysqli_query($conn, "SELECT harga_baju FROM baju WHERE id = $id_baju");
    $data_baju = mysqli_fetch_assoc($result_harga);
    $total_harga = $data_baju['harga_baju'] * $jumlah;

    mysqli_query($conn, "INSERT INTO transaksi (id_pembeli, id_baju, tgl_transaksi, jumlah, total_harga) 
                        VALUES ('$id_pembeli', '$id_baju', '$tgl', '$jumlah', '$total_harga')");
    header("Location: transaksi.php");
}

// --- FUNGSI HAPUS (DELETE) ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi=$id");
    header("Location: transaksi.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi | Soft Pink Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        :root { 
            --pink-main: #ff85a1;
            --pink-light: #fbb1bd;
            --pink-soft: #f9d1d1;
            --pink-sidebar: #ff99ac;
            --text-dark: #7a4a56;
        }

        body { 
            background-color: #fff5f6; 
            font-family: 'Poppins', sans-serif; 
            color: var(--text-dark);
        }

        /* Sidebar Pink Soft */
        .sidebar { 
            height: 100vh; width: 250px; position: fixed; 
            background: linear-gradient(180deg, var(--pink-sidebar) 0%, #ffc2d1 100%); 
            color: white; padding-top: 20px; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar a { 
            padding: 12px 25px; text-decoration: none; 
            color: rgba(255,255,255,0.9); display: block; transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active { 
            background: rgba(255,255,255,0.2); color: white; 
            border-left: 5px solid #fff; font-weight: 600;
        }

        .content { margin-left: 250px; padding: 40px; }

        /* Card & Table Styling */
        .card-table { 
            border: none; border-radius: 20px; 
            box-shadow: 0 8px 20px rgba(255, 133, 161, 0.1); 
            background: white; overflow: hidden;
        }

        .table thead {
            background-color: #ffe5ec;
            color: var(--text-dark);
        }

        .btn-pink {
            background-color: var(--pink-sidebar);
            color: white; border: none; border-radius: 10px;
            padding: 8px 20px; transition: 0.3s;
        }

        .btn-pink:hover {
            background-color: var(--pink-main); color: white;
            box-shadow: 0 4px 10px rgba(255, 133, 161, 0.3);
        }

        .price-text { color: #d63384; font-weight: 700; }

        /* Modal Styling */
        .modal-content { border: none; border-radius: 20px; }
        .modal-header {
            background-color: var(--pink-sidebar);
            color: white; border-top-left-radius: 20px; border-top-right-radius: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="px-4 mb-4 text-center">
        <h4 class="fw-bold text-white">INDO RETAIL</h4>
        <small style="opacity: 0.8;">Soft Admin Panel</small>
    </div>
    <hr style="background-color: rgba(255,255,255,0.3);">
    <a href="dashboard.php"><i class="fas fa-heart me-2"></i> Dashboard</a>
    <a href="data_baju.php"><i class="fas fa-tshirt me-2"></i> Koleksi Baju</a>
    <a href="data_pembeli.php"><i class="fas fa-user-tag me-2"></i> Data Pembeli</a>
    <a href="transaksi.php" class="active"><i class="fas fa-shopping-cart me-2"></i> Transaksi</a>
    <div style="position: absolute; bottom: 20px; width: 100%;">
        <a href="logout.php" class="text-white opacity-75"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--text-dark);">Riwayat Transaksi</h2>
        <button class="btn btn-pink shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-cart-plus me-2"></i> Transaksi Baru
        </button>
    </div>

    <div class="card card-table">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Pembeli</th>
                        <th class="py-3">Baju</th>
                        <th class="py-3">Qty</th>
                        <th class="py-3">Total Harga</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT transaksi.*, pembeli.nama_pembeli, baju.nama_baju 
                            FROM transaksi 
                            JOIN pembeli ON transaksi.id_pembeli = pembeli.id_pembeli 
                            JOIN baju ON transaksi.id_baju = baju.id 
                            ORDER BY id_transaksi DESC";
                    $query = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td class="ps-4 align-middle"><?php echo $no++; ?></td>
                        <td class="align-middle small"><?php echo date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                        <td class="fw-bold align-middle"><?php echo $row['nama_pembeli']; ?></td>
                        <td class="align-middle"><?php echo $row['nama_baju']; ?></td>
                        <td class="align-middle"><?php echo $row['jumlah']; ?> pcs</td>
                        <td class="align-middle price-text">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                        <td class="text-center align-middle">
                            <a href="transaksi.php?hapus=<?php echo $row['id_transaksi']; ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Hapus riwayat transaksi ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header"><h5>Input Transaksi Baru</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Pembeli</label>
                        <select name="id_pembeli" class="form-select" required>
                            <option value="">-- Pilih Pembeli --</option>
                            <?php
                            $pembeli = mysqli_query($conn, "SELECT * FROM pembeli");
                            while($p = mysqli_fetch_assoc($pembeli)) echo "<option value='".$p['id_pembeli']."'>".$p['nama_pembeli']."</option>";
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Baju</label>
                        <select name="id_baju" class="form-select" required>
                            <option value="">-- Pilih Koleksi Baju --</option>
                            <?php
                            $baju = mysqli_query($conn, "SELECT * FROM baju");
                            while($b = mysqli_fetch_assoc($baju)) echo "<option value='".$b['id']."'>".$b['nama_baju']." (Rp ".number_format($b['harga_baju'],0,',','.').")</option>";
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tgl_transaksi" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Beli (Qty)</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_transaksi" class="btn btn-pink">Selesaikan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>