<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
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
    
    $image_name = 'default.jpg';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image_name);
    }
    
    $sql = "INSERT INTO destinations (name, slug, location, description, type, price, image, is_featured) 
            VALUES ('$name', '$slug', '$location', '$description', '$type', $price, '$image_name', $is_featured)";
    
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
    <title>Thêm Địa điểm - GO Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 30px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; padding: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-cancel { background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm Địa điểm mới</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><label>Tên địa điểm</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Vị trí</label><input type="text" name="location" required></div>
            <div class="form-group"><label>Mô tả</label><textarea name="description" rows="3"></textarea></div>
            <div class="form-group">
                <label>Loại hình</label>
                <select name="type">
                    <option value="đảo">Đảo</option><option value="biển">Biển</option>
                    <option value="di tích">Di tích</option><option value="núi">Núi</option><option value="chùa">Chùa</option>
                </select>
            </div>
            <div class="form-group"><label>Giá vé</label><input type="number" name="price"></div>
            <div class="form-group"><label>Hình ảnh</label><input type="file" name="image" accept="image/*"></div>
            <div class="form-group"><label><input type="checkbox" name="is_featured" value="1"> Địa điểm nổi bật</label></div>
            <button type="submit">Thêm địa điểm</button>
            <a href="manage-destinations.php" class="btn-cancel">Hủy</a>
        </form>
    </div>
</body>
</html>