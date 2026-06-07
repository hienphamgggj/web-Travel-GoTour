<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if($delete_id != $_SESSION['user_id']) {
        mysqli_query($conn, "DELETE FROM users WHERE id = $delete_id");
    }
    header('Location: manage-users.php');
    exit();
}

if(isset($_GET['toggle_role'])) {
    $id = (int)$_GET['toggle_role'];
    mysqli_query($conn, "UPDATE users SET role = IF(role = 'user', 'admin', 'user') WHERE id = $id");
    header('Location: manage-users.php');
    exit();
}

$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Người dùng - GO Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .sidebar {
            position: fixed; left: 0; top: 0; width: 260px; height: 100%; background: #2c3e50; color: white;
        }
        .sidebar-header { padding: 20px; text-align: center; }
        .sidebar-menu { list-style: none; padding: 20px 0; }
        .sidebar-menu a {
            display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #34495e; border-left: 3px solid #ff6b6b; }
        .sidebar-menu i { width: 25px; margin-right: 10px; }
        .main-content { margin-left: 260px; padding: 20px; }
        .top-bar {
            background: white; padding: 15px 20px; border-radius: 10px;
            display: flex; justify-content: space-between; margin-bottom: 20px;
        }
        .table-container {
            background: white; border-radius: 10px; padding: 20px; overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .role-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .role-admin { background: #ff6b6b; color: white; }
        .role-user { background: #28a745; color: white; }
        .btn-edit { background: #ffc107; color: #333; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .btn-delete { background: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-map-marked-alt"></i> GO Tour</h3></div>
        <ul class="sidebar-menu">
            <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage-tours.php"><i class="fas fa-bus"></i> Quản lý Tour</a></li>
            <li><a href="manage-destinations.php"><i class="fas fa-map-marker-alt"></i> Quản lý Địa điểm</a></li>
            <li><a href="manage-bookings.php"><i class="fas fa-ticket-alt"></i> Quản lý Đơn hàng</a></li>
            <li><a href="manage-users.php" class="active"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
            <li><a href="../index.php"><i class="fas fa-home"></i> Về trang chủ</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="top-bar">
            <h2>Quản lý Người dùng</h2>
            <div><span><?php echo $_SESSION['user_name']; ?></span> | <a href="../logout.php" class="logout-btn">Đăng xuất</a></div>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>ID</th><th>Họ tên</th><th>Email</th><th>SĐT</th><th>Vai trò</th><th>Ngày đăng ký</th><th>Thao tác</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fullname']; ?></td>
                        <td><?php echo $row['email']; ?>
                                                <td><?php echo $row['phone'] ?: '---'; ?></td>
                        <td>
                            <span class="role-badge <?php echo $row['role'] == 'admin' ? 'role-admin' : 'role-user'; ?>">
                                <?php echo $row['role'] == 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                        <td>
                            <?php if($row['id'] != $_SESSION['user_id']): ?>
                                <a href="?toggle_role=<?php echo $row['id']; ?>" class="btn-edit"><i class="fas fa-exchange-alt"></i> Đổi role</a>
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Xóa người dùng này?')"><i class="fas fa-trash"></i> Xóa</a>
                            <?php else: ?>
                                <span style="color: #999;">Bạn</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>