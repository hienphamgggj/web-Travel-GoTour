<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
require_once 'includes/config.php';
require_once 'includes/header.php';

$sql = "SELECT * FROM destinations WHERE id = $id";
$result = mysqli_query($conn, $sql);
$destination = mysqli_fetch_assoc($result);

if(!$destination) {
    header('Location: destinations.php');
    exit();
}

$is_favorite = false;
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check = mysqli_query($conn, "SELECT * FROM favorites WHERE user_id = $user_id AND destination_id = $id");
    $is_favorite = mysqli_num_rows($check) > 0;
    
    if(isset($_POST['toggle_favorite'])) {
        if($is_favorite) {
            mysqli_query($conn, "DELETE FROM favorites WHERE user_id = $user_id AND destination_id = $id");
        } else {
            mysqli_query($conn, "INSERT INTO favorites (user_id, destination_id) VALUES ($user_id, $id)");
        }
        header("Location: destination-detail.php?id=$id");
        exit();
    }
}
?>

<div style="padding: 100px 0 50px; background: #f8f9fa;">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="background: white; border-radius: 15px; overflow: hidden;">
                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $destination['image']; ?>" style="width: 100%; height: 400px; object-fit: cover;">
                <div style="padding: 30px;">
                    <h1 style="font-size: 32px; margin-bottom: 15px;"><?php echo $destination['name']; ?></h1>
                    <p style="color: #666; margin-bottom: 20px;"><i class="fas fa-map-marker-alt"></i> <?php echo $destination['location']; ?></p>
                    <div style="font-size: 28px; color: #ff6b6b; font-weight: bold; margin: 20px 0;"><?php echo number_format($destination['price']); ?>đ</div>
                    <div style="line-height: 1.8; color: #555;"><?php echo nl2br($destination['description']); ?></div>
                    
                    <div style="margin-top: 30px;">
                        <?php if(isset($_SESSION['user_id'])): ?>
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="toggle_favorite" style="background: none; border: 2px solid #ff6b6b; padding: 10px 20px; border-radius: 5px; cursor: pointer; <?php echo $is_favorite ? 'background: #ff6b6b; color: white;' : ''; ?>">
                                <i class="fas fa-heart"></i> <?php echo $is_favorite ? 'Đã lưu' : 'Lưu yêu thích'; ?>
                            </button>
                        </form>
                        <?php endif; ?>
                        <a href="booking.php?destination_id=<?php echo $destination['id']; ?>" style="background: #ff6b6b; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; margin-left: 10px;">Đặt vé ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>