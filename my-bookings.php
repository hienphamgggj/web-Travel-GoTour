<?php
$page_title = 'Tra cứu đơn hàng';
require_once 'includes/config.php';
require_once 'includes/header.php';

$bookings = null;
$search_result = false;

// Xử lý tìm kiếm đơn hàng theo mã đơn hoặc email
if(isset($_POST['search_booking'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_POST['search_keyword']);
    
    $sql = "SELECT b.*, t.name as tour_name, t.duration, t.departure_location, t.image as tour_image
            FROM bookings b 
            JOIN tours t ON b.tour_id = t.id 
            WHERE b.booking_code = '$search_keyword' OR b.email = '$search_keyword' OR b.phone = '$search_keyword'
            ORDER BY b.created_at DESC";
    $bookings = mysqli_query($conn, $sql);
    $search_result = true;
}
?>

<style>
    .bookings-page { padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh; }
    .page-header { text-align: center; margin-bottom: 40px; }
    .page-header h1 { font-size: 36px; color: #2c3e50; margin-bottom: 10px; }
    .page-header p { color: #666; font-size: 18px; }
    
    .search-box {
        background: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .search-box input {
        width: 70%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
    }
    .search-box button {
        padding: 12px 25px;
        background: #ff6b6b;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }
    
    .booking-card { background: white; border-radius: 15px; overflow: hidden; margin-bottom: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: transform 0.3s; }
    .booking-card:hover { transform: translateY(-3px); }
    .booking-header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .booking-code { font-size: 18px; font-weight: bold; }
    .booking-code i { margin-right: 8px; }
    .booking-date { font-size: 14px; opacity: 0.8; }
    .booking-body { display: flex; padding: 20px; gap: 20px; flex-wrap: wrap; }
    .booking-image { width: 180px; height: 130px; border-radius: 10px; overflow: hidden; }
    .booking-image img { width: 100%; height: 100%; object-fit: cover; }
    .booking-info { flex: 1; }
    .booking-info h3 { font-size: 20px; color: #2c3e50; margin-bottom: 10px; }
    .booking-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin: 15px 0; }
    .detail-item { display: flex; align-items: center; gap: 8px; color: #555; font-size: 14px; }
    .detail-item i { width: 20px; color: #ff6b6b; }
    .booking-footer { background: #f8f9fa; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .total-price { font-size: 22px; font-weight: bold; color: #ff6b6b; }
    .status { display: inline-block; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: bold; }
    .status-pending { background: #ffc107; color: #333; }
    .status-confirmed { background: #28a745; color: white; }
    .status-cancelled { background: #dc3545; color: white; }
    .status-completed { background: #17a2b8; color: white; }
    .btn-view { background: #ff6b6b; color: white; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-size: 14px; transition: all 0.3s; }
    .btn-view:hover { background: #ff5252; }
    .no-result { text-align: center; padding: 60px; background: white; border-radius: 15px; }
    .no-result i { font-size: 64px; color: #ccc; margin-bottom: 20px; }
    .info-text { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    @media (max-width: 768px) {
        .booking-body { flex-direction: column; }
        .booking-image { width: 100%; height: 180px; }
        .booking-footer { flex-direction: column; align-items: stretch; }
        .search-box input { width: 100%; margin-bottom: 10px; }
    }
</style>

<div class="bookings-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-search"></i> Tra cứu đơn hàng</h1>
            <p>Nhập mã đơn, email hoặc số điện thoại để tra cứu đơn hàng của bạn</p>
        </div>
        
        <div class="search-box">
            <form method="POST" action="">
                <input type="text" name="search_keyword" placeholder="Nhập mã đơn, email hoặc số điện thoại..." required>
                <button type="submit" name="search_booking"><i class="fas fa-search"></i> Tra cứu</button>
            </form>
        </div>
        
        <?php if($search_result): ?>
            <?php if(mysqli_num_rows($bookings) > 0): ?>
                <div class="info-text">
                    <i class="fas fa-info-circle"></i> Tìm thấy <?php echo mysqli_num_rows($bookings); ?> đơn hàng
                </div>
                <?php while($row = mysqli_fetch_assoc($bookings)): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="booking-code">
                            <i class="fas fa-receipt"></i> <?php echo $row['booking_code']; ?>
                        </div>
                        <div class="booking-date">
                            <i class="far fa-calendar-alt"></i> Đặt ngày: <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?>
                        </div>
                    </div>
                    
                    <div class="booking-body">
                        <div class="booking-image">
                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['tour_image']; ?>" 
                                 alt="<?php echo $row['tour_name']; ?>"
                                 onerror="this.src='https://images.unsplash.com/photo-1555992336-03a23c7b20ee?w=400'">
                        </div>
                        <div class="booking-info">
                            <h3><?php echo $row['tour_name']; ?></h3>
                            <div class="booking-details">
                                <div class="detail-item">
                                    <i class="far fa-clock"></i> Thời gian: <?php echo $row['duration']; ?>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i> Khởi hành: <?php echo $row['departure_location']; ?>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-day"></i> Ngày đi: <?php echo date('d/m/Y', strtotime($row['departure_date'])); ?>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i> Số khách: <?php echo $row['people_count']; ?> người
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-user"></i> Khách hàng: <?php echo $row['fullname']; ?> - <?php echo $row['phone']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="booking-footer">
                        <div>
                            <span class="total-price"><?php echo number_format($row['total_price']); ?>đ</span>
                        </div>
                        <div>
                            <?php
                            $status_text = '';
                            $status_class = '';
                            switch($row['status']) {
                                case 'pending': $status_text = '⏳ Chờ xác nhận'; $status_class = 'status-pending'; break;
                                case 'confirmed': $status_text = '✅ Đã xác nhận'; $status_class = 'status-confirmed'; break;
                                case 'cancelled': $status_text = '❌ Đã hủy'; $status_class = 'status-cancelled'; break;
                                case 'completed': $status_text = '🎉 Hoàn thành'; $status_class = 'status-completed'; break;
                            }
                            ?>
                            <span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                        </div>
                        <div>
                            <a href="booking-detail.php?id=<?php echo $row['id']; ?>" class="btn-view">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-result">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy đơn hàng</h3>
                    <p>Vui lòng kiểm tra lại mã đơn, email hoặc số điện thoại.</p>
                    <a href="booking.php" class="btn-detail" style="display: inline-block; margin-top: 20px;">
                        <i class="fas fa-ticket-alt"></i> Đặt tour ngay
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-result">
                <i class="fas fa-ticket-alt"></i>
                <h3>Tra cứu đơn hàng</h3>
                <p>Nhập mã đơn, email hoặc số điện thoại ở trên để tra cứu đơn hàng của bạn.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>