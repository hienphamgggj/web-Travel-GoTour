<?php
$conn = mysqli_connect('localhost', 'root', '', 'gotour');

// Mật khẩu là admin123
$password = 'admin123';
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Xóa tài khoản admin cũ
mysqli_query($conn, "DELETE FROM users WHERE email = 'admin@gotour.com'");

// Tạo tài khoản admin mới
$sql = "INSERT INTO users (fullname, email, phone, password, role) VALUES 
        ('Administrator', 'admin@gotour.com', '0900123456', '$hashed', 'admin')";

if(mysqli_query($conn, $sql)) {
    echo "<h2 style='color:green'>✅ TẠO TÀI KHOẢN THÀNH CÔNG!</h2>";
    echo "<p>📧 Email: <strong>admin@gotour.com</strong></p>";
    echo "<p>🔑 Mật khẩu: <strong style='color:red;font-size:24px'>admin123</strong></p>";
    echo "<p>Hash mới: <code>" . $hashed . "</code></p>";
    echo "<hr>";
    echo "<p><a href='admin/login.php' style='background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>ĐĂNG NHẬP NGAY</a></p>";
} else {
    echo "Lỗi: " . mysqli_error($conn);
}
?>