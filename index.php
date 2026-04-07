<?php
include 'config.php';
session_start();

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Menggunakan MD5 sesuai permintaan

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Full Color</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            background: #fff;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        .btn-custom {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            border: none;
            color: white;
        }
        .btn-custom:hover {
            opacity: 0.9;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center mb-4 fw-bold" style="color: #4e54c8;">Welcome Back</h3>
    
    <?php if($error): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="********" required>
        </div>
        <button type="submit" name="login" class="btn btn-custom w-100 py-2 mt-3">Login Sekarang</button>
    </form>
    
    <div class="text-center mt-3">
        <small class="text-muted">Lupa password? Hubungi Admin</small>
    </div>
</div>

</body>
</html>