<?php
$page_title = 'Địa điểm yêu thích';
require_once 'includes/config.php';
require_once 'includes/header.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_GET['remove_id'])) {
    $remove_id = (int)$_GET['remove_id'];
    mysqli_query($conn, "DELETE FROM favorites WHERE user_id = $user_id AND destination_id = $remove_id");
    header('Location: my-favorites.php');
    exit();
}

$sql = "SELECT d.* FROM destinations d JOIN favorites f ON d.id = f.destination_id WHERE f.user_id = $user_id ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<style>
    .favorites-page { padding: 100px 0 50px; background: #f8f9fa; min-height: 100vh; }
    .favorites-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
    .favorite-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); position: relative; }
    .card-image { height: 200px; overflow: hidden; position: relative; }
    .card-image img { width: 100%; height: 100%; object-fit: cover; }
    .btn-remove { position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 50%; cursor: pointer; }
    .card-content { padding: 20px; }
    .empty-state { text-align: center; padding: 60px; background: white; border-radius: 15px; }
</style>

<div class="favorites-page">
    <div class="container">
        <div class="section-header">
            <h2>Địa điểm yêu thích</h2>
        </div>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="favorites-grid">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="favorite-card">
                <div class="card-image">
                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <a href="?remove_id=<?php echo $row['id']; ?>" class="btn-remove" onclick="return confirm('Xóa khỏi yêu thích?')"><i class="fas fa-trash"></i></a>
                </div>
                <div class="card-content">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo $row['location']; ?></p>
                    <div class="price"><?php echo number_format($row['price']); ?>đ</div>
                    <a href="destination-detail.php?id=<?php echo $row['id']; ?>" class="btn-detail">Xem chi tiết</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-heart-broken" style="font-size: 48px; color: #ccc;"></i>
            <p>Chưa có địa điểm yêu thích</p>
            <a href="destinations.php" class="btn-detail">Khám phá ngay</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>