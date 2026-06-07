<?php
$page_title = 'Đặt tour';
require_once 'includes/config.php';
require_once 'includes/header.php';

$tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;

// Nếu không có tour_id, hiển thị danh sách tour để chọn
if($tour_id == 0) {
    $sql_tours = "SELECT * FROM tours WHERE status = 'available' ORDER BY is_hot DESC";
    $result_tours = mysqli_query($conn, $sql_tours);
    ?>
    <div style="padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh;">
        <div class="container">
            <div class="section-header">
                <h2>Chọn tour để đặt</h2>
                <p>Vui lòng chọn một tour du lịch để tiếp tục đặt</p>
            </div>
            <div class="tours-grid">
                <?php if(mysqli_num_rows($result_tours) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result_tours)): ?>
                    <div class="tour-card">
                        <div class="card-image">
                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                            <?php if($row['is_hot']): ?>
                                <span class="price-tag" style="background: #dc3545;">🔥 Hot</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h3><?php echo $row['name']; ?></h3>
                            <div class="tour-info">
                                <span><i class="far fa-clock"></i> <?php echo $row['duration']; ?></span>
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo $row['departure_location']; ?></span>
                            </div>
                            <div class="price-box">
                                <span class="new-price"><?php echo number_format($row['price']); ?>đ</span>
                            </div>
                            <a href="booking.php?tour_id=<?php echo $row['id']; ?>" class="btn-book">Chọn tour này</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; grid-column: 1/-1; padding: 50px;">
                        <i class="fas fa-bus" style="font-size: 48px; color: #ccc;"></i>
                        <p style="margin-top: 20px;">Chưa có tour nào! Vui lòng quay lại sau.</p>
                        <a href="index.php" class="btn-detail">Về trang chủ</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    require_once 'includes/footer.php';
    exit();
}

// Lấy thông tin tour theo id
$sql = "SELECT * FROM tours WHERE id = $tour_id AND status = 'available'";
$result = mysqli_query($conn, $sql);
$tour = mysqli_fetch_assoc($result);

if(!$tour) {
    echo "<div style='padding: 100px; text-align: center;'><h2>Không tìm thấy tour!</h2><a href='booking.php' class='btn-detail'>Chọn tour khác</a></div>";
    require_once 'includes/footer.php';
    exit();
}

$message = '';
$message_type = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $people_count = (int)$_POST['people_count'];
    $departure_date = $_POST['departure_date'];
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    
    // ===== KIỂM TRA NGÀY THÁNG =====
    $today = date('Y-m-d');
    $min_date = date('Y-m-d', strtotime('+1 day')); // Ngày nhỏ nhất là ngày mai
    
    // Kiểm tra ngày không được để trống
    if(empty($departure_date)) {
        $message = "❌ Vui lòng chọn ngày khởi hành!";
        $message_type = 'error';
    }
    // Kiểm tra ngày không được nhỏ hơn ngày hiện tại
    elseif($departure_date < $today) {
        $message = "❌ Ngày khởi hành không được nhỏ hơn ngày hiện tại! Vui lòng chọn ngày khác.";
        $message_type = 'error';
    }
    // Kiểm tra ngày không được là ngày quá xa (tối đa 180 ngày)
    elseif(strtotime($departure_date) > strtotime('+180 days')) {
        $message = "❌ Ngày khởi hành không được quá 180 ngày kể từ hôm nay!";
        $message_type = 'error';
    }
    else {
        $price = $tour['discount_price'] ? $tour['discount_price'] : $tour['price'];
        $total_price = $price * $people_count;
        $booking_code = 'GO' . date('Ymd') . rand(100, 999);
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';
        
        $sql = "INSERT INTO bookings (booking_code, user_id, tour_id, fullname, email, phone, people_count, departure_date, total_price, note) 
                VALUES ('$booking_code', $user_id, $tour_id, '$fullname', '$email', '$phone', $people_count, '$departure_date', $total_price, '$note')";
        
        if(mysqli_query($conn, $sql)) {
            $message = "✅ Đặt tour thành công! Mã đơn: <strong>$booking_code</strong><br>Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.";
            $message_type = 'success';
        } else {
            $message = "❌ Lỗi: " . mysqli_error($conn);
            $message_type = 'error';
        }
    }
}

// Lấy ngày tối thiểu cho input date (ngày mai)
$min_date = date('Y-m-d', strtotime('+1 day'));
// Lấy ngày tối đa (6 tháng sau)
$max_date = date('Y-m-d', strtotime('+180 days'));
?>

<style>
    .booking-page { padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh; }
    .booking-container { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; max-width: 1000px; margin: 0 auto; }
    .booking-form, .tour-summary { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
    .booking-form h3, .tour-summary h3 { font-size: 24px; color: #2c3e50; margin-bottom: 25px; border-bottom: 2px solid #ff6b6b; display: inline-block; padding-bottom: 10px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
    .form-group label .required { color: #dc3545; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #ff6b6b; }
    .form-group input.error { border-color: #dc3545; }
    .error-message { color: #dc3545; font-size: 13px; margin-top: 5px; display: block; }
    .btn-submit { width: 100%; padding: 14px; background: #ff6b6b; color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer; transition: background 0.3s; margin-top: 10px; }
    .btn-submit:hover { background: #ff5252; }
    .btn-submit:disabled { background: #ccc; cursor: not-allowed; }
    .tour-info-item { margin: 15px 0; padding: 10px 0; border-bottom: 1px solid #eee; }
    .tour-info-item strong { color: #2c3e50; width: 120px; display: inline-block; }
    .total-price { font-size: 24px; color: #ff6b6b; font-weight: bold; text-align: center; margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; }
    .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .date-note { font-size: 12px; color: #666; margin-top: 5px; }
    @media (max-width: 768px) {
        .booking-container { grid-template-columns: 1fr; }
    }
</style>

<div class="booking-page">
    <div class="container">
        <div class="booking-container">
            <div class="booking-form">
                <h3><i class="fas fa-ticket-alt"></i> Thông tin đặt tour</h3>
                
                <?php if($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!$message || $message_type != 'success'): ?>
                <form method="POST" action="" id="bookingForm">
                    <div class="form-group">
                        <label>Họ tên <span class="required">*</span></label>
                        <input type="text" name="fullname" required placeholder="Nguyễn Văn A" value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" required placeholder="example@email.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Số điện thoại <span class="required">*</span></label>
                        <input type="tel" name="phone" required placeholder="0901234567" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Số lượng khách <span class="required">*</span></label>
                        <input type="number" name="people_count" required min="1" max="20" value="<?php echo isset($_POST['people_count']) ? $_POST['people_count'] : 1; ?>" id="people_count" onchange="updateTotal()">
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày khởi hành <span class="required">*</span></label>
                        <input type="date" name="departure_date" required 
                               min="<?php echo $min_date; ?>" 
                               max="<?php echo $max_date; ?>"
                               value="<?php echo isset($_POST['departure_date']) ? $_POST['departure_date'] : ''; ?>"
                               id="departure_date">
                        <div class="date-note">
                            <i class="fas fa-info-circle"></i> Ngày khởi hành sớm nhất: <?php echo date('d/m/Y', strtotime('+1 day')); ?> | Muộn nhất: <?php echo date('d/m/Y', strtotime('+180 days')); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea name="note" rows="3" placeholder="Yêu cầu đặc biệt (nếu có)..."><?php echo isset($_POST['note']) ? $_POST['note'] : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check-circle"></i> Xác nhận đặt tour
                    </button>
                </form>
                <?php else: ?>
                    <a href="index.php" class="btn-submit" style="display: block; text-align: center; text-decoration: none; background: #28a745;">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                    <a href="my-bookings.php" class="btn-submit" style="display: block; text-align: center; text-decoration: none; margin-top: 10px;">
                        <i class="fas fa-list"></i> Tra cứu đơn hàng
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="tour-summary">
                <h3><i class="fas fa-bus"></i> Thông tin tour</h3>
                <div class="tour-info-item">
                    <strong>Tên tour:</strong> <?php echo $tour['name']; ?>
                </div>
                <div class="tour-info-item">
                    <strong>Thời gian:</strong> <?php echo $tour['duration']; ?>
                </div>
                <div class="tour-info-item">
                    <strong>Điểm khởi hành:</strong> <?php echo $tour['departure_location']; ?>
                </div>
                <div class="tour-info-item">
                    <strong>Giá / người:</strong> 
                    <?php if($tour['discount_price']): ?>
                        <span style="text-decoration: line-through; color: #999;"><?php echo number_format($tour['price']); ?>đ</span>
                        <strong style="color: #ff6b6b;"> <?php echo number_format($tour['discount_price']); ?>đ</strong>
                    <?php else: ?>
                        <strong style="color: #ff6b6b;"><?php echo number_format($tour['price']); ?>đ</strong>
                    <?php endif; ?>
                </div>
                <div class="tour-info-item">
                    <strong>Lịch trình:</strong><br>
                    <?php echo nl2br($tour['schedule']); ?>
                </div>
                <div class="tour-info-item">
                    <strong>Bao gồm:</strong> <?php echo $tour['included']; ?>
                </div>
                <?php if($tour['excluded']): ?>
                <div class="tour-info-item">
                    <strong>Không bao gồm:</strong> <?php echo $tour['excluded']; ?>
                </div>
                <?php endif; ?>
                
                <div class="total-price" id="total_price_display">
                    Tổng tiền: <span id="total_amount"><?php echo number_format($tour['discount_price'] ? $tour['discount_price'] : $tour['price']); ?>đ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateTotal() {
    let price = <?php echo $tour['discount_price'] ? $tour['discount_price'] : $tour['price']; ?>;
    let people = document.getElementById('people_count').value;
    let total = price * people;
    document.getElementById('total_amount').innerText = total.toLocaleString('vi-VN') + 'đ';
}

// Validate ngày trước khi submit
document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
    let dateInput = document.getElementById('departure_date');
    let selectedDate = dateInput.value;
    let today = new Date();
    today.setHours(0, 0, 0, 0);
    let minDate = new Date();
    minDate.setDate(minDate.getDate() + 1);
    minDate.setHours(0, 0, 0, 0);
    
    if(!selectedDate) {
        alert('Vui lòng chọn ngày khởi hành!');
        e.preventDefault();
        return false;
    }
    
    let selected = new Date(selectedDate);
    selected.setHours(0, 0, 0, 0);
    
    if(selected < minDate) {
        alert('Ngày khởi hành phải từ ngày mai trở đi!');
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>

<?php require_once 'includes/footer.php'; ?>