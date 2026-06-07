<?php
$page_title = 'Tour du lịch';
require_once 'includes/config.php';
require_once 'includes/header.php';

$sql = "SELECT * FROM tours WHERE status = 'available' ORDER BY is_hot DESC";
$result = mysqli_query($conn, $sql);
?>

<div style="padding: 100px 0 50px; background: #f8f9fa;">
    <div class="container">
        <div class="section-header">
            <h2>Tour du lịch Quy Nhơn</h2>
            <p>Chọn tour phù hợp để khám phá Bình Định</p>
        </div>
        
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div style="display: flex; background: white; border-radius: 15px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <div style="width: 300px; height: 220px;">
                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['image']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="flex: 1; padding: 20px;">
                <h3><?php echo $row['name']; ?> <?php if($row['is_hot']): ?><span style="background: #ff6b6b; color: white; padding: 2px 8px; border-radius: 5px; font-size: 12px;">Hot</span><?php endif; ?></h3>
                <div style="display: flex; gap: 20px; margin: 10px 0; color: #666;">
                    <span><i class="far fa-clock"></i> <?php echo $row['duration']; ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo $row['departure_location']; ?></span>
                </div>
                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px;">
                    <strong>Lịch trình:</strong><br><?php echo nl2br($row['schedule']); ?>
                </div>
                <div style="margin: 15px 0;">
                    <span style="font-size: 24px; font-weight: bold; color: #ff6b6b;"><?php echo number_format($row['price']); ?>đ</span> / người
                </div>
                <a href="booking.php?tour_id=<?php echo $row['id']; ?>" style="display: inline-block; padding: 10px 20px; background: #ff6b6b; color: white; text-decoration: none; border-radius: 5px;">Đặt tour ngay</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>