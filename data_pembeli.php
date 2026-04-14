<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// --- FUNGSI SIMPAN (CREATE) ---
if (isset($_POST['tambah_pembeli'])) {
    $nama = $_POST['nama_pembeli'];
    $email = $_POST['email'];
    $telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    
    mysqli_query($conn, "INSERT INTO pembeli (nama_pembeli, email, no_telp, alamat) VALUES ('$nama', '$email', '$telp', '$alamat')");
    header("Location: data_pembeli.php");
}

// --- FUNGSI HAPUS (DELETE) ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pembeli WHERE id_pembeli=$id");
    header("Location: data_pembeli.php");
}

// --- FUNGSI EDIT (UPDATE) ---
if (isset($_POST['update_pembeli'])) {
    $id = $_POST['id_pembeli'];
    $nama = $_POST['nama_pembeli'];
    $email = $_POST['email'];
    $telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    
    mysqli_query($conn, "UPDATE pembeli SET nama_pembeli='$nama', email='$email', no_telp='$telp', alamat='$alamat' WHERE id_pembeli=$id");
    header("Location: data_pembeli.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pembeli | Soft Pink Admin</title>
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
    <a href="data_pembeli.php" class="active"><i class="fas fa-user-tag me-2"></i> Data Pembeli</a>
    <a href="transaksi.php"><i class="fas fa-shopping-cart me-2"></i> Transaksi</a>
    <div style="position: absolute; bottom: 20px; width: 100%;">
        <a href="logout.php" class="text-white opacity-75"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--text-dark);">Manajemen Pembeli</h2>
        <button class="btn btn-pink shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-user-plus me-2"></i> Tambah Pembeli
        </button>
    </div>

    <div class="card card-table">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Nama Pembeli</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">No. Telp</th>
                        <th class="py-3">Alamat</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT * FROM pembeli ORDER BY id_pembeli DESC");
                    while($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td class="ps-4 align-middle"><?php echo $no++; ?></td>
                        <td class="fw-bold align-middle"><?php echo $row['nama_pembeli']; ?></td>
                        <td class="align-middle"><?php echo $row['email']; ?></td>
                        <td class="align-middle"><?php echo $row['no_telp']; ?></td>
                        <td class="align-middle"><small class="text-muted"><?php echo $row['alamat']; ?></small></td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-outline-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row['id_pembeli']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="data_pembeli.php?hapus=<?php echo $row['id_pembeli']; ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Hapus data pembeli ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit<?php echo $row['id_pembeli']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header"><h5>Edit Data Pembeli</h5></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_pembeli" value="<?php echo $row['id_pembeli']; ?>">
                                        <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="nama_pembeli" class="form-control" value="<?php echo $row['nama_pembeli']; ?>" required></div>
                                        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required></div>
                                        <div class="mb-3"><label class="form-label">No. Telp</label><input type="text" name="no_telp" class="form-control" value="<?php echo $row['no_telp']; ?>" required></div>
                                        <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="3" required><?php echo $row['alamat']; ?></textarea></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="update_pembeli" class="btn btn-pink">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
                <div class="modal-header"><h5>Tambah Pembeli Baru</h5></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="nama_pembeli" class="form-control" placeholder="Nama Pelanggan" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" placeholder="email@contoh.com" required></div>
                    <div class="mb-3"><label class="form-label">No. Telp</label><input type="text" name="no_telp" class="form-control" placeholder="08123xxx" required></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" placeholder="Alamat lengkap pengiriman" rows="3" required></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="tambah_pembeli" class="btn btn-pink">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>