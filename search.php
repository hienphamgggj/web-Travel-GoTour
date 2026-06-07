<?php
$page_title = 'Tìm kiếm';
require_once 'includes/config.php';
require_once 'includes/header.php';

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';

if(empty($keyword)) {
    header('Location: index.php');
    exit();
}

$sql_dest = "SELECT * FROM destinations WHERE name LIKE '%$keyword%' OR location LIKE '%$keyword%'";
$result_dest = mysqli_query($conn, $sql_dest);

$sql_tours = "SELECT * FROM tours WHERE name LIKE '%$keyword%' AND status = 'available'";
$result_tours = mysqli_query($conn, $sql_tours);
?>

<style>
    .search-page { padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh; }
    .search-header { background: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; text-align: center; }
    .keyword { color: #ff6b6b; font-weight: bold; }
    .result-section { margin-bottom: 40px; }
    .result-section h3 { margin-bottom: 20px; border-left: 4px solid #ff6b6b; padding-left: 15px; }
    .no-result { text-align: center; padding: 50px; background: white; border-radius: 10px; }
</style>

<div class="search-page">
    <div class="container">
        <div class="search-header">
            <h2>Kết quả tìm kiếm</h2>
            <p>Từ khóa: <span class="keyword">"<?php echo htmlspecialchars($keyword); ?>"</span></p>
        </div>
        
        <div class="result-section">
            <h3>Địa điểm</h3>
            <?php if(mysqli_num_rows($result_dest) > 0): ?>
            <div class="destinations-grid">
                <?php while($row = mysqli_fetch_assoc($result_dest)): ?>
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
            <?php else: ?>
            <div class="no-result">Không tìm thấy địa điểm nào!</div>
            <?php endif; ?>
        </div>
        
        <div class="result-section">
            <h3>Tour du lịch</h3>
            <?php if(mysqli_num_rows($result_tours) > 0): ?>
            <div class="tours-grid">
                <?php while($row = mysqli_fetch_assoc($result_tours)): ?>
                <div class="tour-card">
                    <div class="card-image">
                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
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
                        <a href="booking.php?tour_id=<?php echo $row['id']; ?>" class="btn-book">Đặt tour ngay</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="no-result">Không tìm thấy tour nào!</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>