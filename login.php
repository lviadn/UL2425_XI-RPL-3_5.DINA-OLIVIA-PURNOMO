<?php  
session_start();
include("inc_koneksi.php");

$username = "";
$password = "";
$err = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == '' || $password == '') {
        $err .= "<li>Silakan masukkan username dan password</li>";
    }

    if (empty($err)) {
        $username = mysqli_real_escape_string($koneksi, $username);
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $query = mysqli_query($koneksi, $sql);
        $data = mysqli_fetch_array($query);

        if (!$data || $data['password'] != $password) {
            $err .= "<li>Username atau password salah</li>";
        } else {
            $role = strtolower($data['role']);
            if ($role != 'user' && $role != 'admin') {
                $err .= "<li>Akun tidak memiliki akses</li>";
            }
        }
    }

    if (empty($err)) {
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if ($role == 'user') {
            header("Location: dashboard_user.php");
        } elseif ($role == 'admin') {
            header("Location: dashboard_admin.php");
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fcefe8;
            color: #444;
            margin: 0;
            padding: 0;
        }

        .login-box {
            width: 400px;
            margin: 100px auto;
            background-color: #fff8f0;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #ff8c94;
        }

        ul {
            text-align: left;
            color: #ff4e50;
            padding-left: 20px;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #ffd3b6;
            color: #333;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #ffaaa5;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Halaman Login</h1>
        <?php if ($err) echo "<ul>$err</ul>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" name="login" value="Masuk">
        </form>
    </div>
</body>
</html>
