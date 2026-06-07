<?php
$page_title = 'Địa điểm du lịch';
require_once 'includes/config.php';
require_once 'includes/header.php';

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';

$sql = "SELECT * FROM destinations WHERE 1=1";
if($keyword) {
    $sql .= " AND (name LIKE '%$keyword%' OR location LIKE '%$keyword%')";
}
if($type) {
    $sql .= " AND type = '$type'";
}
$sql .= " ORDER BY is_featured DESC";
$result = mysqli_query($conn, $sql);
?>

<div style="padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh;">
    <div class="container">
        <div class="section-header">
            <h2>Khám phá địa điểm</h2>
            <p>Những điểm đến tuyệt vời tại Quy Nhơn</p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
            <form method="GET" action="">
                <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label>Tìm kiếm</label>
                        <input type="text" name="keyword" value="<?php echo $keyword; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div>
                        <label>Loại hình</label>
                        <select name="type" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="">Tất cả</option>
                            <option value="đảo" <?php echo $type == 'đảo' ? 'selected' : ''; ?>>Đảo</option>
                            <option value="biển" <?php echo $type == 'biển' ? 'selected' : ''; ?>>Biển</option>
                            <option value="di tích" <?php echo $type == 'di tích' ? 'selected' : ''; ?>>Di tích</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" style="padding: 10px 20px; background: #ff6b6b; color: white; border: none; border-radius: 5px;">Lọc</button>
                        <a href="destinations.php" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="destinations-grid">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="destination-card">
                <div class="card-image">
                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <span class="price-tag"><?php echo number_format($row['price']); ?>đ</span>
                </div>
                <div class="card-content">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo $row['location']; ?></p>
                    <a href="destination-detail.php?id=<?php echo $row['id']; ?>" class="btn-detail">Xem chi tiết</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>