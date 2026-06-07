<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM tours WHERE id = $id";
$result = mysqli_query($conn, $sql);
$tour = mysqli_fetch_assoc($result);

if(!$tour) {
    header('Location: manage-tours.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $departure_location = mysqli_real_escape_string($conn, $_POST['departure_location']);
    $price = (float)$_POST['price'];
    $discount_price = isset($_POST['discount_price']) ? (float)$_POST['discount_price'] : 0;
    $max_people = (int)$_POST['max_people'];
    $schedule = mysqli_real_escape_string($conn, $_POST['schedule']);
    $included = mysqli_real_escape_string($conn, $_POST['included']);
    $excluded = mysqli_real_escape_string($conn, $_POST['excluded']);
    
    $image_name = $tour['image'];
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image_name);
    }
    
    $sql = "UPDATE tours SET name='$name', description='$description', duration='$duration', departure_location='$departure_location', price=$price, discount_price=$discount_price, max_people=$max_people, schedule='$schedule', included='$included', excluded='$excluded', image='$image_name' WHERE id=$id";
    
    if(mysqli_query($conn, $sql)) {
        header('Location: manage-tours.php?success=1');
    } else {
        header('Location: manage-tours.php?error=1');
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Tour - GO Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 30px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 15px; padding: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #ffc107; color: #333; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-cancel { background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
        .current-image { margin: 10px 0; }
        .current-image img { max-width: 200px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sửa Tour</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><label>Tên tour</label><input type="text" name="name" value="<?php echo $tour['name']; ?>" required></div>
            <div class="form-group"><label>Mô tả</label><textarea name="description" rows="3"><?php echo $tour['description']; ?></textarea></div>
            <div class="form-group"><label>Thời gian</label><input type="text" name="duration" value="<?php echo $tour['duration']; ?>"></div>
            <div class="form-group"><label>Điểm khởi hành</label><input type="text" name="departure_location" value="<?php echo $tour['departure_location']; ?>"></div>
            <div class="form-group"><label>Giá</label><input type="number" name="price" value="<?php echo $tour['price']; ?>" required></div>
            <div class="form-group"><label>Giá khuyến mãi</label><input type="number" name="discount_price" value="<?php echo $tour['discount_price']; ?>"></div>
            <div class="form-group"><label>Số lượng tối đa</label><input type="number" name="max_people" value="<?php echo $tour['max_people']; ?>"></div>
            <div class="form-group"><label>Lịch trình</label><textarea name="schedule" rows="5"><?php echo $tour['schedule']; ?></textarea></div>
            <div class="form-group"><label>Bao gồm</label><input type="text" name="included" value="<?php echo $tour['included']; ?>"></div>
            <div class="form-group"><label>Không bao gồm</label><input type="text" name="excluded" value="<?php echo $tour['excluded']; ?>"></div>
            <div class="form-group">
                <div class="current-image"><img src="../uploads/<?php echo $tour['image']; ?>"></div>
                <label>Thay đổi ảnh</label><input type="file" name="image" accept="image/*">
            </div>
            <button type="submit">Lưu thay đổi</button>
            <a href="manage-tours.php" class="btn-cancel">Hủy</a>
        </form>
    </div>
</body>
</html>