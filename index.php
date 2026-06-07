<?php
session_start();
require_once '../includes/config.php';

// Kiểm tra đăng nhập admin
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Thống kê
$total_tours = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tours"))['total'];
$total_destinations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM destinations"))['total'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'user'"))['total'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as revenue FROM bookings WHERE status IN ('confirmed', 'completed')"))['revenue'] ?? 0;
$pending_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'"))['total'];

$recent_bookings = mysqli_query($conn, "SELECT b.*, u.fullname, t.name as tour_name FROM bookings b JOIN users u ON b.user_id = u.id JOIN tours t ON b.tour_id = t.id ORDER BY b.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - GO Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .sidebar {
            position: fixed; left: 0; top: 0; width: 260px; height: 100%; background: #2c3e50; color: white;
        }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid #34495e; }
        .sidebar-menu { list-style: none; padding: 20px 0; }
        .sidebar-menu a {
            display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #34495e; border-left: 3px solid #ff6b6b; }
        .sidebar-menu i { width: 25px; margin-right: 10px; }
        .main-content { margin-left: 260px; padding: 20px; }
        .top-bar {
            background: white; padding: 15px 20px; border-radius: 10px;
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-bottom: 30px;
        }
        .stat-card {
            background: white; padding: 20px; border-radius: 10px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .stat-info h3 { font-size: 14px; color: #666; margin-bottom: 10px; }
        .stat-info .number { font-size: 28px; font-weight: bold; }
        .stat-icon i { font-size: 40px; color: #ff6b6b; opacity: 0.7; }
        .recent-table {
            background: white; border-radius: 10px; padding: 20px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .status { padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .status-pending { background: #ffc107; }
        .status-confirmed { background: #28a745; color: white; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-map-marked-alt"></i> GO Tour</h3></div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage-tours.php"><i class="fas fa-bus"></i> Quản lý Tour</a></li>
            <li><a href="manage-destinations.php"><i class="fas fa-map-marker-alt"></i> Quản lý Địa điểm</a></li>
            <li><a href="manage-bookings.php"><i class="fas fa-ticket-alt"></i> Quản lý Đơn hàng</a></li>
            <li><a href="manage-users.php"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
            <li><a href="../index.php"><i class="fas fa-home"></i> Về trang chủ</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="top-bar">
            <h2>Dashboard</h2>
            <div>
                <span>Xin chào, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="../logout.php" class="logout-btn" style="margin-left: 15px;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-info"><h3>Tour</h3><div class="number"><?php echo $total_tours; ?></div></div><div class="stat-icon"><i class="fas fa-bus"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Địa điểm</h3><div class="number"><?php echo $total_destinations; ?></div></div><div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Người dùng</h3><div class="number"><?php echo $total_users; ?></div></div><div class="stat-icon"><i class="fas fa-users"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Đơn hàng</h3><div class="number"><?php echo $total_bookings; ?></div></div><div class="stat-icon"><i class="fas fa-ticket-alt"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Doanh thu</h3><div class="number"><?php echo number_format($total_revenue); ?>đ</div></div><div class="stat-icon"><i class="fas fa-dollar-sign"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Chờ xác nhận</h3><div class="number"><?php echo $pending_bookings; ?></div></div><div class="stat-icon"><i class="fas fa-clock"></i></div></div>
        </div>
        
        <div class="recent-table">
            <h3>Đơn hàng mới nhất</h3>
            <table>
                <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Tour</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Trạng thái</th></tr></thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($recent_bookings)): ?>
                    <tr>
                        <td><?php echo $row['booking_code']; ?></td>
                        <td><?php echo $row['fullname']; ?></td>
                        <td><?php echo $row['tour_name']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                        <td><?php echo number_format($row['total_price']); ?>đ</td>
                        <td><span class="status status-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>