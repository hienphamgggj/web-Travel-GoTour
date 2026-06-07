<?php
$page_title = 'Trang chủ';
require_once 'includes/config.php';
require_once 'includes/header.php';

// Lấy địa điểm nổi bật
$sql_featured = "SELECT * FROM destinations WHERE is_featured = 1 LIMIT 6";
$result_featured = mysqli_query($conn, $sql_featured);

// Lấy tour hot
$sql_hot_tours = "SELECT * FROM tours WHERE is_hot = 1 AND status = 'available' LIMIT 4";
$result_hot_tours = mysqli_query($conn, $sql_hot_tours);
?>

<style>
/* Banner Slideshow */
.slideshow-container {
    position: relative;
    width: 100%;
    height: 550px;
    margin-top: 70px;
    overflow: hidden;
}
.slide {
    position: absolute;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
.slide.active {
    opacity: 1;
}
.slide-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    width: 100%;
    padding: 20px;
    z-index: 10;
}
.slide-content h1 {
    font-size: 48px;
    margin-bottom: 20px;
}
.slide-content p {
    font-size: 20px;
    margin-bottom: 30px;
}
.hero-tags {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}
.hero-tags span {
    background: rgba(255,255,255,0.2);
    padding: 8px 20px;
    border-radius: 30px;
}
.search-box {
    background: white;
    padding: 10px;
    border-radius: 50px;
    max-width: 600px;
    margin: 0 auto;
}
.search-group {
    display: flex;
    align-items: center;
}
.search-group i {
    margin-left: 15px;
    color: #999;
}
.search-group input {
    flex: 1;
    padding: 15px;
    border: none;
    outline: none;
    font-size: 16px;
}
.search-group button {
    padding: 12px 30px;
    background: #ff6b6b;
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
}
.prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.5);
    color: white;
    padding: 15px;
    cursor: pointer;
    border-radius: 50%;
    z-index: 20;
    font-size: 18px;
}
.prev { left: 20px; }
.next { right: 20px; }
.prev:hover, .next:hover { background: rgba(0,0,0,0.8); }
.dots {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 20;
}
.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
}
.dot.active { background: #ff6b6b; }

/* Địa điểm và Tour */
.featured-destinations, .hot-tours {
    padding: 60px 0;
}
.hot-tours { background: #f8f9fa; }
.section-header {
    text-align: center;
    margin-bottom: 50px;
}
.section-header h2 {
    font-size: 36px;
    color: #2c3e50;
    margin-bottom: 15px;
}
.destinations-grid, .tours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}
.destination-card, .tour-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.destination-card:hover, .tour-card:hover {
    transform: translateY(-5px);
}
.card-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}
.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.price-tag {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: #ff6b6b;
    color: white;
    padding: 5px 12px;
    border-radius: 5px;
    font-weight: bold;
}
.card-content {
    padding: 20px;
}
.card-content h3 {
    margin-bottom: 10px;
    color: #2c3e50;
}
.location {
    color: #666;
    font-size: 14px;
    margin-bottom: 10px;
}
.btn-detail, .btn-book {
    display: inline-block;
    padding: 8px 20px;
    background: #ff6b6b;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
}
.btn-book {
    width: 100%;
    text-align: center;
}
.tour-info {
    display: flex;
    gap: 20px;
    margin: 10px 0;
    color: #666;
    font-size: 14px;
}
.price-box {
    margin: 15px 0;
}
.new-price {
    font-size: 24px;
    font-weight: bold;
    color: #ff6b6b;
}

/* Tour card slider */
.card-image-slider {
    position: relative;
    height: 220px;
    overflow: hidden;
}
.slider-container {
    position: relative;
    width: 100%;
    height: 100%;
}
.slide-img {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.5s ease;
}
.slide-img.active-slide {
    opacity: 1;
}
.slide-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.slider-prev, .slider-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 50%;
    z-index: 10;
}
.slider-prev { left: 5px; }
.slider-next { right: 5px; }
.slider-prev:hover, .slider-next:hover {
    background: rgba(0,0,0,0.8);
}
.slider-dots {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
}
.dot-img {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
}
.dot-img.active-dot {
    background: #ff6b6b;
}
.hot-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    z-index: 10;
}
</style>

<!-- Banner Slideshow 3 ảnh -->
<div class="slideshow-container">
    <?php
    $banners = [
        ['image' => 'banner-1.jpg', 'title' => 'GO TOUR - QUY NHƠN', 'desc' => 'Khám phá vẻ đẹp hoang sơ của biển trời Bình Định'],
        ['image' => 'banner-2.jpg', 'title' => 'KHÁM PHÁ KỲ CO', 'desc' => 'Bãi biển đẹp nhất Quy Nhơn'],
        ['image' => 'banner-3.jpg', 'title' => 'CÙ LAO XANH', 'desc' => 'Hòn đảo xanh mát với làng chài yên bình']
    ];
    $i = 0;
    foreach($banners as $banner):
    ?>
    <div class="slide <?php echo $i == 0 ? 'active' : ''; ?>" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo BASE_URL; ?>uploads/<?php echo $banner['image']; ?>'); background-size: cover; background-position: center;">
        <div class="slide-content">
            <h1><?php echo $banner['title']; ?></h1>
            <p><?php echo $banner['desc']; ?></p>
            <div class="hero-tags">
                <span>🏝️ Hòn Khô</span>
                <span>⛰️ Ghềnh Ráng</span>
                <span>🌊 Kỳ Co</span>
                <span>🏯 Tháp Đôi</span>
                <span>🏝️ Cù Lao Xanh</span>
            </div>
            <div class="search-box">
                <form action="destinations.php" method="GET">
                    <div class="search-group">
                        <i class="fas fa-search"></i>
                        <input type="text" name="keyword" placeholder="Bạn muốn đi đâu?">
                        <button type="submit">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $i++; endforeach; ?>
    
    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
    <a class="next" onclick="changeSlide(1)">&#10095;</a>
    
    <div class="dots">
        <?php for($j = 0; $j < count($banners); $j++): ?>
            <span class="dot <?php echo $j == 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $j; ?>)"></span>
        <?php endfor; ?>
    </div>
</div>

<!-- Địa điểm nổi bật -->
<section class="featured-destinations">
    <div class="container">
        <div class="section-header">
            <h2>📍 Địa điểm nổi bật</h2>
            <p>Những điểm đến được yêu thích nhất tại Quy Nhơn</p>
        </div>
        <div class="destinations-grid">
            <?php while($row = mysqli_fetch_assoc($result_featured)): ?>
            <div class="destination-card">
                <div class="card-image">
                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                    <span class="price-tag"><?php echo number_format($row['price']); ?>đ</span>
                </div>
                <div class="card-content">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo $row['location']; ?></p>
                    <a href="destination-detail.php?id=<?php echo $row['id']; ?>" class="btn-detail">Xem chi tiết →</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Tour hot nhất với 3 ảnh slider -->
<section class="hot-tours">
    <div class="container">
        <div class="section-header">
            <h2>🔥 Tour hot nhất</h2>
            <p>Đặt ngay để nhận ưu đãi hấp dẫn</p>
        </div>
        <div class="tours-grid">
            <?php while($row = mysqli_fetch_assoc($result_hot_tours)): 
                // Lấy 3 ảnh của tour
                $sql_images = "SELECT image FROM tour_images WHERE tour_id = " . $row['id'] . " ORDER BY sort_order LIMIT 3";
                $result_images = mysqli_query($conn, $sql_images);
                $images = [];
                while($img = mysqli_fetch_assoc($result_images)) {
                    $images[] = $img['image'];
                }
                if(empty($images)) {
                    $images = [$row['image'], $row['image'], $row['image']];
                }
            ?>
            <div class="tour-card">
                <div class="card-image-slider">
                    <div class="slider-container" id="slider-<?php echo $row['id']; ?>">
                        <?php foreach($images as $idx => $img): ?>
                        <div class="slide-img <?php echo $idx == 0 ? 'active-slide' : ''; ?>">
                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $img; ?>" alt="<?php echo $row['name']; ?>" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                        </div>
                        <?php endforeach; ?>
                        <button class="slider-prev" onclick="changeImage(<?php echo $row['id']; ?>, -1)">&#10094;</button>
                        <button class="slider-next" onclick="changeImage(<?php echo $row['id']; ?>, 1)">&#10095;</button>
                    </div>
                    <div class="slider-dots" id="dots-<?php echo $row['id']; ?>">
                        <?php for($d = 0; $d < count($images); $d++): ?>
                            <span class="dot-img <?php echo $d == 0 ? 'active-dot' : ''; ?>" onclick="currentImage(<?php echo $row['id']; ?>, <?php echo $d; ?>)"></span>
                        <?php endfor; ?>
                    </div>
                    <?php if($row['is_hot']): ?>
                        <span class="hot-badge">🔥 HOT</span>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3><?php echo $row['name']; ?></h3>
                    <div class="tour-info">
                        <span><i class="far fa-clock"></i> ⏰ <?php echo $row['duration']; ?></span>
                        <span><i class="fas fa-map-marker-alt"></i> 📍 <?php echo $row['departure_location']; ?></span>
                    </div>
                    <div class="price-box">
                        <?php if($row['discount_price']): ?>
                            <span style="text-decoration: line-through; color: #999;"><?php echo number_format($row['price']); ?>đ</span>
                            <span class="new-price"> <?php echo number_format($row['discount_price']); ?>đ</span>
                        <?php else: ?>
                            <span class="new-price"><?php echo number_format($row['price']); ?>đ</span>
                        <?php endif; ?>
                    </div>
                    <a href="booking.php?tour_id=<?php echo $row['id']; ?>" class="btn-book">Đặt tour ngay</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<script>
// Banner slideshow
let slideIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');

function showSlide(n) {
    if (n >= slides.length) slideIndex = 0;
    if (n < 0) slideIndex = slides.length - 1;
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    slides[slideIndex].classList.add('active');
    dots[slideIndex].classList.add('active');
}

function changeSlide(n) {
    slideIndex += n;
    showSlide(slideIndex);
}

function currentSlide(n) {
    slideIndex = n;
    showSlide(slideIndex);
}

setInterval(() => {
    slideIndex++;
    showSlide(slideIndex);
}, 5000);

// Tour slider - chuyển ảnh trong từng tour
function changeImage(tourId, step) {
    const container = document.getElementById(`slider-${tourId}`);
    const slides = container.querySelectorAll('.slide-img');
    const dots = document.querySelectorAll(`#dots-${tourId} .dot-img`);
    let currentIndex = -1;
    for(let i = 0; i < slides.length; i++) {
        if(slides[i].classList.contains('active-slide')) {
            currentIndex = i;
            break;
        }
    }
    let newIndex = currentIndex + step;
    if(newIndex < 0) newIndex = slides.length - 1;
    if(newIndex >= slides.length) newIndex = 0;
    slides.forEach(slide => slide.classList.remove('active-slide'));
    dots.forEach(dot => dot.classList.remove('active-dot'));
    slides[newIndex].classList.add('active-slide');
    dots[newIndex].classList.add('active-dot');
}

function currentImage(tourId, index) {
    const container = document.getElementById(`slider-${tourId}`);
    const slides = container.querySelectorAll('.slide-img');
    const dots = document.querySelectorAll(`#dots-${tourId} .dot-img`);
    slides.forEach(slide => slide.classList.remove('active-slide'));
    dots.forEach(dot => dot.classList.remove('active-dot'));
    slides[index].classList.add('active-slide');
    dots[index].classList.add('active-dot');
}
</script>

<?php require_once 'includes/footer.php'; ?>