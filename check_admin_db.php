<?php
$conn = mysqli_connect('localhost', 'root', '', 'gotour');

echo "<h2>Kiểm tra tài khoản Admin</h2>";

$sql = "SELECT * FROM users WHERE role = 'admin'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    echo "<p style='color:green'>✅ Đã tìm thấy tài khoản admin:</p>";
    echo "<ul>";
    echo "<li>ID: " . $admin['id'] . "</li>";
    echo "<li>Email: " . $admin['email'] . "</li>";
    echo "<li>Role: " . $admin['role'] . "</li>";
    echo "<li>Password hash: " . $admin['password'] . "</li>";
    echo "</ul>";
    
    // Kiểm tra mật khẩu admin123
    $test_pass = 'admin123';
    if(password_verify($test_pass, $admin['password'])) {
        echo "<p style='color:green;font-weight:bold'>✅ Mật khẩu 'admin123' là ĐÚNG!</p>";
    } else {
        echo "<p style='color:red;font-weight:bold'>❌ Mật khẩu 'admin123' là SAI!</p>";
    }
    
    // Kiểm tra mật khẩu 123456
    $test_pass2 = '123456';
    if(password_verify($test_pass2, $admin['password'])) {
        echo "<p style='color:green;font-weight:bold'>✅ Mật khẩu '123456' là ĐÚNG!</p>";
    } else {
        echo "<p style='color:red;font-weight:bold'>❌ Mật khẩu '123456' là SAI!</p>";
    }
} else {
    echo "<p style='color:red'>❌ KHÔNG tìm thấy tài khoản admin nào!</p>";
    echo "<p>Bạn cần chạy câu lệnh SQL sau để tạo admin:</p>";
    echo "<code style='background:#f0f0f0;display:block;padding:10px;'>INSERT INTO users (fullname, email, phone, password, role) VALUES ('Administrator', 'admin@gotour.com', '0900123456', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin');</code>";
}
?>