<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM destinations WHERE id = $id";
$result = mysqli_query($conn, $sql);
$dest = mysqli_fetch_assoc($result);

if(!$dest) {
    header('Location: manage-destinations.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $price = (float)$_POST['price'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $image_name = $dest['image'];
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image_name);
    }
    
    $sql = "UPDATE destinations SET name='$name', slug='$slug', location='$location', description='$description', type='$type', price=$price, image='$image_name', is_featured=$is_featured WHERE id=$id";
    
    if(mysqli_query($conn, $sql)) {
        header('Location: manage-destinations.php?success=1');
    } else {
        header('Location: manage-destinations.php?error=1');
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Địa điểm - GO Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 30px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; padding: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #ffc107; color: #333; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-cancel { background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
        .current-image img { max-width: 200px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sửa Địa điểm</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><label>Tên địa điểm</label><input type="text" name="name" value="<?php echo $dest['name']; ?>" required></div>
            <div class="form-group"><label>Vị trí</label><input type="text" name="location" value="<?php echo $dest['location']; ?>" required></div>
            <div class="form-group"><label>Mô tả</label><textarea name="description" rows="3"><?php echo $dest['description']; ?></textarea></div>
            <div class="form-group">
                <label>Loại hình</label>
                <select name="type">
                    <option value="đảo" <?php echo $dest['type'] == 'đảo' ? 'selected' : ''; ?>>Đảo</option>
                    <option value="biển" <?php echo $dest['type'] == 'biển' ? 'selected' : ''; ?>>Biển</option>
                    <option value="di tích" <?php echo $dest['type'] == 'di tích' ? 'selected' : ''; ?>>Di tích</option>
                    <option value="núi" <?php echo $dest['type'] == 'núi' ? 'selected' : ''; ?>>Núi</option>
                    <option value="chùa" <?php echo $dest['type'] == 'chùa' ? 'selected' : ''; ?>>Chùa</option>
                </select>
            </div>
            <div class="form-group"><label>Giá vé</label><input type="number" name="price" value="<?php echo $dest['price']; ?>"></div>
            <div class="form-group">
                <div class="current-image"><img src="../uploads/<?php echo $dest['image']; ?>"></div>
                <label>Thay đổi ảnh</label><input type="file" name="image" accept="image/*">
            </div>
            <div class="form-group"><label><input type="checkbox" name="is_featured" value="1" <?php echo $dest['is_featured'] ? 'checked' : ''; ?>> Địa điểm nổi bật</label></div>
            <button type="submit">Lưu thay đổi</button>
            <a href="manage-destinations.php" class="btn-cancel">Hủy</a>
        </form>
    </div>
</body>
</html>