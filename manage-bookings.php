<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if(isset($_GET['update_status']) && isset($_GET['status'])) {
    $id = (int)$_GET['update_status'];
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    mysqli_query($conn, "UPDATE bookings SET status = '$status' WHERE id = $id");
    header('Location: manage-bookings.php');
    exit();
}

if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM bookings WHERE id = $delete_id");
    header('Location: manage-bookings.php');
    exit();
}

$status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
$where = '';
if($status_filter && $status_filter != 'all') {
    $where = "WHERE b.status = '$status_filter'";
}

$sql = "SELECT b.*, u.fullname as user_name, t.name as tour_name 
        FROM bookings b 
        LEFT JOIN users u ON b.user_id = u.id 
        JOIN tours t ON b.tour_id = t.id 
        $where 
        ORDER BY b.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng - GO Tour</title>
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
        .filter-bar {
            background: white; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px;
            display: flex; gap: 10px; flex-wrap: wrap;
        }
        .filter-btn {
            padding: 8px 20px; background: #f8f9fa; border: 1px solid #ddd;
            border-radius: 5px; text-decoration: none; color: #333;
        }
        .filter-btn.active { background: #ff6b6b; color: white; border-color: #ff6b6b; }
        .table-container {
            background: white; border-radius: 10px; padding: 20px; overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #ffc107; }
        .status-confirmed { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .status-completed { background: #17a2b8; color: white; }
        .btn-edit { background: #ffc107; color: #333; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .btn-delete { background: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        select { padding: 5px; border-radius: 5px; }
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
            <li><a href="manage-bookings.php" class="active"><i class="fas fa-ticket-alt"></i> Quản lý Đơn hàng</a></li>
            <li><a href="manage-users.php"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
            <li><a href="../index.php"><i class="fas fa-home"></i> Về trang chủ</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="top-bar">
            <h2>Quản lý Đơn hàng</h2>
            <div><span><?php echo $_SESSION['user_name']; ?></span> | <a href="../logout.php" class="logout-btn">Đăng xuất</a></div>
        </div>
        
        <div class="filter-bar">
            <a href="?status_filter=all" class="filter-btn <?php echo $status_filter == '' || $status_filter == 'all' ? 'active' : ''; ?>">Tất cả</a>
            <a href="?status_filter=pending" class="filter-btn <?php echo $status_filter == 'pending' ? 'active' : ''; ?>">Chờ xác nhận</a>
            <a href="?status_filter=confirmed" class="filter-btn <?php echo $status_filter == 'confirmed' ? 'active' : ''; ?>">Đã xác nhận</a>
            <a href="?status_filter=completed" class="filter-btn <?php echo $status_filter == 'completed' ? 'active' : ''; ?>">Hoàn thành</a>
            <a href="?status_filter=cancelled" class="filter-btn <?php echo $status_filter == 'cancelled' ? 'active' : ''; ?>">Đã hủy</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>Mã đơn</th><th>Khách hàng</th><th>Tour</th><th>Số người</th><th>Ngày đi</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th><th>Thao tác</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong><?php echo $row['booking_code']; ?></strong></td>
                        <td><?php echo $row['fullname']; ?><br><small><?php echo $row['email']; ?></small></td>
                        <td><?php echo $row['tour_name']; ?></td>
                        <td><?php echo $row['people_count']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['departure_date'])); ?></td>
                        <td><strong><?php echo number_format($row['total_price']); ?>đ</strong></td>
                        <td>
                            <form method="GET" style="display: inline;">
                                <input type="hidden" name="update_status" value="<?php echo $row['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                    <option value="confirmed" <?php echo $row['status'] == 'confirmed' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                    <option value="completed" <?php echo $row['status'] == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                                    <option value="cancelled" <?php echo $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                </select>
                            </form>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Xóa đơn hàng này?')"><i class="fas fa-trash"></i> Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>