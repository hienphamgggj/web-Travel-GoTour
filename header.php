<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GO Tour - <?php echo $page_title ?? 'Du lịch Quy Nhơn'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>">
                    <i class="fas fa-map-marked-alt"></i> GO Tour
                </a>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                    <li><a href="<?php echo BASE_URL; ?>destinations.php">Địa điểm</a></li>
                    <li><a href="<?php echo BASE_URL; ?>tours.php">Tour</a></li>
                    <li><a href="<?php echo BASE_URL; ?>booking.php">Đặt tour</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                        <span>Xin chào, <?php echo $_SESSION['user_name']; ?></span>
                        <a href="<?php echo BASE_URL; ?>profile.php">Hồ sơ</a>
                        <?php if($_SESSION['user_role'] == 'admin'): ?>
                            <a href="<?php echo BASE_URL; ?>admin/">Admin</a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>logout.php">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>login.php" class="btn-login">Đăng nhập</a>
                    <a href="<?php echo BASE_URL; ?>register.php" class="btn-register">Đăng ký</a>
                <?php endif; ?>
            </div>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>