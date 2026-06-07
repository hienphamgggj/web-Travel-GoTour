<?php
$page_title = 'Hồ sơ cá nhân';
require_once 'includes/config.php';
require_once 'includes/header.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$message = '';
$message_type = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update_profile'])) {
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $update = "UPDATE users SET fullname = '$fullname', phone = '$phone' WHERE id = $user_id";
        if(mysqli_query($conn, $update)) {
            $_SESSION['user_name'] = $fullname;
            $message = 'Cập nhật thành công!';
            $message_type = 'success';
            $user['fullname'] = $fullname;
            $user['phone'] = $phone;
        } else {
            $message = 'Cập nhật thất bại!';
            $message_type = 'error';
        }
    }
    
    if(isset($_POST['change_password'])) {
        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        
        if(!password_verify($old, $user['password'])) {
            $message = 'Mật khẩu cũ không đúng!';
            $message_type = 'error';
        } elseif($new != $confirm) {
            $message = 'Mật khẩu mới không khớp!';
            $message_type = 'error';
        } elseif(strlen($new) < 6) {
            $message = 'Mật khẩu phải có ít nhất 6 ký tự!';
            $message_type = 'error';
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = "UPDATE users SET password = '$hashed' WHERE id = $user_id";
            if(mysqli_query($conn, $update)) {
                $message = 'Đổi mật khẩu thành công!';
                $message_type = 'success';
            } else {
                $message = 'Đổi mật khẩu thất bại!';
                $message_type = 'error';
            }
        }
    }
}
?>

<style>
    .profile-page { padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh; }
    .profile-container { max-width: 800px; margin: 0 auto; background: white; border-radius: 15px; padding: 30px; }
    .profile-header { text-align: center; margin-bottom: 30px; }
    .profile-header h2 { color: #2c3e50; }
    .nav-tabs { display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 1px solid #ddd; }
    .nav-tabs a { padding: 10px 20px; text-decoration: none; color: #666; }
    .nav-tabs a.active { color: #ff6b6b; border-bottom: 2px solid #ff6b6b; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
    .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    .btn-save { background: #ff6b6b; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    .alert { padding: 12px; border-radius: 5px; margin-bottom: 20px; }
    .alert-success { background: #d4edda; color: #155724; }
    .alert-error { background: #f8d7da; color: #721c24; }
</style>

<div class="profile-page">
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <h2><i class="fas fa-user-circle"></i> Hồ sơ cá nhân</h2>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="nav-tabs">
                <a href="?tab=info" class="<?php echo !isset($_GET['tab']) || $_GET['tab'] == 'info' ? 'active' : ''; ?>">Thông tin</a>
                <a href="?tab=password" class="<?php echo isset($_GET['tab']) && $_GET['tab'] == 'password' ? 'active' : ''; ?>">Đổi mật khẩu</a>
                <a href="my-bookings.php">Lịch sử đặt tour</a>
                <a href="my-favorites.php">Yêu thích</a>
            </div>
            
            <?php if(!isset($_GET['tab']) || $_GET['tab'] == 'info'): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo $user['email']; ?>" disabled style="background: #f8f9fa;">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" value="<?php echo $user['phone']; ?>">
                </div>
                <button type="submit" name="update_profile" class="btn-save">Cập nhật</button>
            </form>
            <?php endif; ?>
            
            <?php if(isset($_GET['tab']) && $_GET['tab'] == 'password'): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Mật khẩu cũ</label>
                    <input type="password" name="old_password" required>
                </div>
                <div class="form-group">
                    <label>Mật khẩu mới</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Xác nhận mật khẩu mới</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn-save">Đổi mật khẩu</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>