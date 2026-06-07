<?php
$page_title = 'Đăng ký';
require_once 'includes/config.php';

if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if(mysqli_num_rows($check) > 0) {
        $error = 'Email đã được đăng ký!';
    } elseif($password != $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } elseif(strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, email, phone, password, role) VALUES ('$fullname', '$email', '$phone', '$hashed', 'user')";
        if(mysqli_query($conn, $sql)) {
            $success = 'Đăng ký thành công! Vui lòng đăng nhập.';
            echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
        } else {
            $error = 'Có lỗi xảy ra!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - GO Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-box {
            background: white;
            border-radius: 15px;
            padding: 40px;
            width: 450px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .register-box h2 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        .register-box h2 i { color: #ff6b6b; margin-right: 10px; }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus { outline: none; border-color: #ff6b6b; }
        button {
            width: 100%;
            padding: 12px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover { background: #ff5252; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #ff6b6b; text-decoration: none; }
        .back-link { text-align: center; margin-top: 15px; }
        .back-link a { color: #999; text-decoration: none; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2><i class="fas fa-user-plus"></i> Đăng ký</h2>
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <?php if($success) echo "<div class='success'>$success</div>"; ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Họ tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Số điện thoại">
            <input type="password" name="password" placeholder="Mật khẩu (ít nhất 6 ký tự)" required>
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            <button type="submit">Đăng ký</button>
        </form>
        <div class="login-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập</a>
        </div>
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Về trang chủ</a>
        </div>
    </div>
</body>
</html>