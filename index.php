<?php
include 'config.php';
session_start();

$error = ""; // Inisialisasi awal agar tidak Undefined

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit(); // Mencegah kode di bawah tereksekusi
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
    <title>Login | Soft Pink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fce4ec 0%, #ffe5ec 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
        }
        .login-card {
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(255, 133, 161, 0.2);
            background: #fff;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
        }
        .btn-custom {
            background: #ff85a1;
            border: none;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background: #ff99ac;
            color: white;
        }
        .form-control:focus {
            border-color: #ff85a1;
            box-shadow: 0 0 0 0.25rem rgba(255, 133, 161, 0.25);
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center mb-4 fw-bold" style="color: #ff85a1;">Welcome Back</h3>
    
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger text-center" style="font-size: 0.9rem;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="********" required>
        </div>
        <button type="submit" name="login" class="btn btn-custom w-100 py-2 mt-3 shadow-sm">Login Sekarang</button>
    </form>
</div>

</body>
</html>