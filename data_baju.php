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
    <title>Koleksi Baju | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { height: 100vh; width: 250px; position: fixed; background: #2d3436; color: white; padding-top: 20px; }
        .sidebar a { padding: 12px 25px; text-decoration: none; color: #b2bec3; display: block; }
        .sidebar a:hover, .sidebar a.active { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
        .content { margin-left: 250px; padding: 30px; }
        .card-table { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="px-4 mb-4"><h4 class="fw-bold text-white">CLOTHING APP</h4></div>
    <a href="dashboard.php"><i class="fas fa-th-large me-2"></i> Dashboard</a>
    <a href="data_baju.php" class="active"><i class="fas fa-tshirt me-2"></i> Koleksi Baju</a>
    <a href="data_pembeli.php"><i class="fas fa-user-tag me-2"></i> Data Pembeli</a>
    <a href="transaksi.php"><i class="fas fa-shopping-bag me-2"></i> Transaksi</a>
    <a href="logout.php" class="text-danger mt-5"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Koleksi Baju Terbaru</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> Tambah Baju
        </button>
    </div>

    <div class="card card-table">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Baju</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Made In</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT * FROM baju ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td class="ps-4"><?php echo $no++; ?></td>
                        <td class="fw-bold"><?php echo $row['nama_baju']; ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo $row['jenis_baju']; ?></span></td>
                        <td>Rp <?php echo number_format($row['harga_baju'], 0, ',', '.'); ?></td>
                        <td><i class="fas fa-globe-asia me-1 text-muted"></i> <?php echo $row['made_in']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="data_baju.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus baju ini?')">
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
                                        <div class="mb-3"><label>Nama Baju</label><input type="text" name="nama_baju" class="form-control" value="<?php echo $row['nama_baju']; ?>" required></div>
                                        <div class="mb-3"><label>Jenis Baju</label><input type="text" name="jenis_baju" class="form-control" value="<?php echo $row['jenis_baju']; ?>" required></div>
                                        <div class="mb-3"><label>Harga</label><input type="number" name="harga_baju" class="form-control" value="<?php echo $row['harga_baju']; ?>" required></div>
                                        <div class="mb-3"><label>Made In</label><input type="text" name="made_in" class="form-control" value="<?php echo $row['made_in']; ?>" required></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
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
                <div class="modal-header bg-primary text-white"><h5>Tambah Koleksi Baru</h5></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Nama Baju</label><input type="text" name="nama_baju" class="form-control" placeholder="Contoh: Hoodie Polos" required></div>
                    <div class="mb-3"><label>Jenis Baju</label><input type="text" name="jenis_baju" class="form-control" placeholder="Kaos / Kemeja / Jaket" required></div>
                    <div class="mb-3"><label>Harga</label><input type="number" name="harga_baju" class="form-control" placeholder="100000" required></div>
                    <div class="mb-3"><label>Made In</label><input type="text" name="made_in" class="form-control" placeholder="Indonesia" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Baju</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>