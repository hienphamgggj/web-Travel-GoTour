<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM destinations WHERE id = $delete_id");
    header('Location: manage-destinations.php');
    exit();
}

$sql = "SELECT * FROM destinations ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Địa điểm - GO Tour</title>
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
        .btn-add {
            background: #28a745; color: white; padding: 10px 20px;
            border-radius: 5px; text-decoration: none; display: inline-block; margin-bottom: 20px;
        }
        .table-container {
            background: white; border-radius: 10px; padding: 20px; overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .btn-edit { background: #ffc107; color: #333; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .btn-delete { background: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .featured-badge { background: #28a745; color: white; padding: 2px 8px; border-radius: 20px; font-size: 12px; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-map-marked-alt"></i> GO Tour</h3></div>
        <ul class="sidebar-menu">
            <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage-tours.php"><i class="fas fa-bus"></i> Quản lý Tour</a></li>
            <li><a href="manage-destinations.php" class="active"><i class="fas fa-map-marker-alt"></i> Quản lý Địa điểm</a></li>
            <li><a href="manage-bookings.php"><i class="fas fa-ticket-alt"></i> Quản lý Đơn hàng</a></li>
            <li><a href="manage-users.php"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
            <li><a href="../index.php"><i class="fas fa-home"></i> Về trang chủ</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="top-bar">
            <h2>Quản lý Địa điểm</h2>
            <div><span><?php echo $_SESSION['user_name']; ?></span> | <a href="../logout.php" class="logout-btn">Đăng xuất</a></div>
        </div>
        
        <a href="add-destination.php" class="btn-add"><i class="fas fa-plus"></i> Thêm địa điểm mới</a>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>ID</th><th>Hình ảnh</th><th>Tên địa điểm</th><th>Vị trí</th><th>Loại</th><th>Giá vé</th><th>Nổi bật</th><th>Thao tác</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><img src="../uploads/<?php echo $row['image']; ?>" style="width: 50px; height: 40px; object-fit: cover;"></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo number_format($row['price']); ?>đ</td>
                        <td><?php echo $row['is_featured'] ? '<span class="featured-badge">Nổi bật</span>' : 'Thường'; ?></td>
                        <td>
                            <a href="edit-destination.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Xóa địa điểm này?')"><i class="fas fa-trash"></i> Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>