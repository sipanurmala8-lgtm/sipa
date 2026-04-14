<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// --- FUNGSI SIMPAN (CREATE) ---
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_baju'];
    $jenis = $_POST['jenis_baju'];
    $harga = $_POST['harga_baju'];
    $made = $_POST['made_in'];
    
    mysqli_query($conn, "INSERT INTO baju (nama_baju, jenis_baju, harga_baju, made_in) VALUES ('$nama', '$jenis', '$harga', '$made')");
    header("Location: data_baju.php");
}

// --- FUNGSI HAPUS (DELETE) ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM baju WHERE id=$id");
    header("Location: data_baju.php");
}

// --- FUNGSI EDIT (UPDATE) ---
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_baju'];
    $jenis = $_POST['jenis_baju'];
    $harga = $_POST['harga_baju'];
    $made = $_POST['made_in'];
    
    mysqli_query($conn, "UPDATE baju SET nama_baju='$nama', jenis_baju='$jenis', harga_baju='$harga', made_in='$made' WHERE id=$id");
    header("Location: data_baju.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Koleksi Baju | Soft Pink Admin</title>
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

        /* Table & Card Styling */
        .card-table { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 8px 20px rgba(255, 133, 161, 0.1); 
            background: white;
            overflow: hidden;
        }

        .table thead {
            background-color: #ffe5ec;
            color: var(--text-dark);
        }

        .btn-pink {
            background-color: var(--pink-sidebar);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            transition: 0.3s;
        }

        .btn-pink:hover {
            background-color: var(--pink-main);
            color: white;
            box-shadow: 0 4px 10px rgba(255, 133, 161, 0.3);
        }

        .badge-jenis {
            background-color: #f9d1d1;
            color: #d63384;
            border-radius: 8px;
            padding: 5px 10px;
        }

        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 20px;
        }
        .modal-header {
            background-color: var(--pink-sidebar);
            color: white;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
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
    <a href="data_baju.php" class="active"><i class="fas fa-tshirt me-2"></i> Koleksi Baju</a>
    <a href="data_pembeli.php"><i class="fas fa-user-alt me-2"></i> Data Pembeli</a>
    <a href="transaksi.php"><i class="fas fa-shopping-cart me-2"></i> Transaksi</a>
    <div style="position: absolute; bottom: 20px; width: 100%;">
        <a href="logout.php" class="text-white opacity-75"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--text-dark);">Koleksi Baju Terbaru</h2>
        <button class="btn btn-pink shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> Tambah Baju
        </button>
    </div>

    <div class="card card-table">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Nama Baju</th>
                        <th class="py-3">Jenis</th>
                        <th class="py-3">Harga</th>
                        <th class="py-3">Made In</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT * FROM baju ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td class="ps-4 align-middle"><?php echo $no++; ?></td>
                        <td class="fw-bold align-middle"><?php echo $row['nama_baju']; ?></td>
                        <td class="align-middle"><span class="badge badge-jenis"><?php echo $row['jenis_baju']; ?></span></td>
                        <td class="align-middle">Rp <?php echo number_format($row['harga_baju'], 0, ',', '.'); ?></td>
                        <td class="align-middle text-muted small"><i class="fas fa-globe-asia me-1"></i> <?php echo $row['made_in']; ?></td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-outline-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="data_baju.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Hapus baju ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit<?php echo $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header"><h5>Edit Data Baju</h5></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3"><label class="form-label">Nama Baju</label><input type="text" name="nama_baju" class="form-control" value="<?php echo $row['nama_baju']; ?>" required></div>
                                        <div class="mb-3"><label class="form-label">Jenis Baju</label><input type="text" name="jenis_baju" class="form-control" value="<?php echo $row['jenis_baju']; ?>" required></div>
                                        <div class="mb-3"><label class="form-label">Harga</label><input type="number" name="harga_baju" class="form-control" value="<?php echo $row['harga_baju']; ?>" required></div>
                                        <div class="mb-3"><label class="form-label">Made In</label><input type="text" name="made_in" class="form-control" value="<?php echo $row['made_in']; ?>" required></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="update" class="btn btn-pink">Simpan Perubahan</button>
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
        <div class="modal-content shadow">
            <form method="POST">
                <div class="modal-header"><h5>Tambah Koleksi Baru</h5></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Baju</label><input type="text" name="nama_baju" class="form-control" placeholder="Contoh: Hoodie Polos" required></div>
                    <div class="mb-3"><label class="form-label">Jenis Baju</label><input type="text" name="jenis_baju" class="form-control" placeholder="Kaos / Kemeja / Jaket" required></div>
                    <div class="mb-3"><label class="form-label">Harga</label><input type="number" name="harga_baju" class="form-control" placeholder="100000" required></div>
                    <div class="mb-3"><label class="form-label">Made In</label><input type="text" name="made_in" class="form-control" placeholder="Indonesia" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="tambah" class="btn btn-pink">Simpan Baju</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>