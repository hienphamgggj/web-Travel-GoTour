<?php
$page_title = 'Chi tiết đơn hàng';
require_once 'includes/config.php';
require_once 'includes/header.php';

// KHÔNG CẦN KIỂM TRA ĐĂNG NHẬP - Ai cũng có thể xem chi tiết đơn hàng
// (Chỉ cần có ID đơn hàng hợp lệ)

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id == 0) {
    echo "<div style='padding: 100px; text-align: center;'><h2>Không tìm thấy đơn hàng!</h2><a href='my-bookings.php' class='btn-detail'>Tra cứu đơn hàng</a></div>";
    require_once 'includes/footer.php';
    exit();
}

// Lấy thông tin đơn hàng - KHÔNG kiểm tra user_id
$sql = "SELECT b.*, t.name as tour_name, t.duration, t.departure_location, t.schedule, t.included, t.excluded, t.image as tour_image
        FROM bookings b 
        JOIN tours t ON b.tour_id = t.id 
        WHERE b.id = $id";
$result = mysqli_query($conn, $sql);
$booking = mysqli_fetch_assoc($result);

if(!$booking) {
    echo "<div style='padding: 100px; text-align: center;'><h2>Không tìm thấy đơn hàng!</h2><a href='my-bookings.php' class='btn-detail'>Tra cứu đơn hàng</a></div>";
    require_once 'includes/footer.php';
    exit();
}
?>

<style>
    .detail-page { padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh; }
    .detail-card { max-width: 900px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
    .detail-header { background: #2c3e50; color: white; padding: 25px; text-align: center; }
    .detail-header h2 { margin-bottom: 10px; }
    .detail-header p { font-size: 18px; }
    .detail-body { padding: 30px; }
    .info-row { display: flex; padding: 12px 0; border-bottom: 1px solid #eee; flex-wrap: wrap; }
    .info-label { width: 180px; font-weight: bold; color: #2c3e50; }
    .info-value { flex: 1; color: #555; }
    .status-badge { display: inline-block; padding: 6px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; }
    .status-pending { background: #ffc107; color: #333; }
    .status-confirmed { background: #28a745; color: white; }
    .status-cancelled { background: #dc3545; color: white; }
    .status-completed { background: #17a2b8; color: white; }
    .total-price { font-size: 28px; color: #ff6b6b; font-weight: bold; text-align: right; margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; }
    .btn-back { display: inline-block; margin-top: 30px; color: #ff6b6b; text-decoration: none; }
    .btn-print { display: inline-block; margin-top: 30px; margin-left: 15px; color: #17a2b8; text-decoration: none; }
    .tour-image-small { width: 100px; height: 70px; object-fit: cover; border-radius: 8px; }
    @media print {
        .header, .footer, .btn-back, .btn-print { display: none; }
        .detail-page { padding: 0; }
        .detail-card { box-shadow: none; }
    }
    @media (max-width: 768px) {
        .info-row { flex-direction: column; }
        .info-label { width: 100%; margin-bottom: 5px; }
        .detail-body { padding: 20px; }
    }
</style>

<div class="detail-page">
    <div class="container">
        <div class="detail-card">
            <div class="detail-header">
                <h2><i class="fas fa-receipt"></i> Chi tiết đơn hàng</h2>
                <p>Mã đơn: <strong><?php echo $booking['booking_code']; ?></strong></p>
            </div>
            <div class="detail-body">
                <!-- Thông tin tour -->
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-bus"></i> Hình ảnh tour:</div>
                    <div class="info-value">
                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $booking['tour_image']; ?>" 
                             class="tour-image-small" 
                             onerror="this.src='https://via.placeholder.com/100x70?text=No+Image'">
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-bus"></i> Tên tour:</div>
                    <div class="info-value"><?php echo $booking['tour_name']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="far fa-clock"></i> Thời gian:</div>
                    <div class="info-value"><?php echo $booking['duration']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-map-marker-alt"></i> Điểm khởi hành:</div>
                    <div class="info-value"><?php echo $booking['departure_location']; ?></div>
                </div>
                
                <!-- Thông tin khách hàng -->
                <div style="margin: 20px 0 10px; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 15px;"><i class="fas fa-user"></i> Thông tin khách hàng</h3>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-user"></i> Họ tên:</div>
                    <div class="info-value"><?php echo $booking['fullname']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-envelope"></i> Email:</div>
                    <div class="info-value"><?php echo $booking['email']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-phone"></i> Số điện thoại:</div>
                    <div class="info-value"><?php echo $booking['phone']; ?></div>
                </div>
                
                <!-- Thông tin đặt tour -->
                <div style="margin: 20px 0 10px; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 15px;"><i class="fas fa-ticket-alt"></i> Thông tin đặt tour</h3>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-calendar-alt"></i> Ngày đặt:</div>
                    <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-calendar-day"></i> Ngày khởi hành:</div>
                    <div class="info-value"><?php echo date('d/m/Y', strtotime($booking['departure_date'])); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-users"></i> Số lượng khách:</div>
                    <div class="info-value"><?php echo $booking['people_count']; ?> người</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-tag"></i> Trạng thái:</div>
                    <div class="info-value">
                        <?php
                        $status_text = '';
                        $status_class = '';
                        switch($booking['status']) {
                            case 'pending':
                                $status_text = '⏳ Chờ xác nhận';
                                $status_class = 'status-pending';
                                break;
                            case 'confirmed':
                                $status_text = '✅ Đã xác nhận';
                                $status_class = 'status-confirmed';
                                break;
                            case 'cancelled':
                                $status_text = '❌ Đã hủy';
                                $status_class = 'status-cancelled';
                                break;
                            case 'completed':
                                $status_text = '🎉 Hoàn thành';
                                $status_class = 'status-completed';
                                break;
                        }
                        ?>
                        <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                    </div>
                </div>
                <?php if($booking['note']): ?>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-sticky-note"></i> Ghi chú:</div>
                    <div class="info-value"><?php echo nl2br($booking['note']); ?></div>
                </div>
                <?php endif; ?>
                
                <!-- Thông tin tour chi tiết -->
                <div style="margin: 20px 0 10px; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 15px;"><i class="fas fa-info-circle"></i> Chi tiết tour</h3>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-list"></i> Lịch trình:</div>
                    <div class="info-value"><?php echo nl2br($booking['schedule']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-check-circle" style="color: #28a745;"></i> Bao gồm:</div>
                    <div class="info-value"><?php echo $booking['included']; ?></div>
                </div>
                <?php if($booking['excluded']): ?>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-times-circle" style="color: #dc3545;"></i> Không bao gồm:</div>
                    <div class="info-value"><?php echo $booking['excluded']; ?></div>
                </div>
                <?php endif; ?>
                
                <!-- Tổng tiền -->
                <div class="total-price">
                    Tổng tiền: <?php echo number_format($booking['total_price']); ?>đ
                </div>
                
                <!-- Nút bấm -->
                <div>
                    <a href="my-bookings.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Quay lại tra cứu
                    </a>
                    <a href="javascript:window.print()" class="btn-print">
                        <i class="fas fa-print"></i> In hóa đơn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>