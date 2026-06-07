-- ============================================
-- TẠO DATABASE
-- ============================================
CREATE DATABASE IF NOT EXISTS gotour;
USE gotour;

-- ============================================
-- BẢNG 1: users (Người dùng)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- BẢNG 2: destinations (Địa điểm du lịch)
-- ============================================
CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    location VARCHAR(200),
    description TEXT,
    image VARCHAR(255),
    price DECIMAL(10,2) DEFAULT 0,
    rating DECIMAL(2,1) DEFAULT 0,
    type ENUM('đảo', 'biển', 'núi', 'di tích', 'chùa') NOT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- BẢNG 3: tours (Tour du lịch)
-- ============================================
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    duration VARCHAR(50),
    departure_location VARCHAR(100),
    price DECIMAL(12,2) NOT NULL,
    discount_price DECIMAL(12,2),
    max_people INT DEFAULT 20,
    schedule TEXT,
    included TEXT,
    excluded TEXT,
    image VARCHAR(255),
    is_hot BOOLEAN DEFAULT FALSE,
    status ENUM('available', 'full', 'cancelled') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- BẢNG 4: bookings (Đơn đặt tour)
-- ============================================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) UNIQUE NOT NULL,
    user_id INT,
    tour_id INT NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    people_count INT DEFAULT 1,
    departure_date DATE NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    note TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
);

-- ============================================
-- BẢNG 5: favorites (Địa điểm yêu thích)
-- ============================================
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    destination_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, destination_id)
);

-- ============================================
-- DỮ LIỆU MẪU - TÀI KHOẢN
-- ============================================
INSERT INTO users (fullname, email, phone, password, role) VALUES
('Quản trị viên', 'admin@gotour.com', '0900123456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO users (fullname, email, phone, password, role) VALUES
('Nguyễn Văn A', 'user@gotour.com', '0987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- ============================================
-- DỮ LIỆU MẪU - ĐỊA ĐIỂM QUY NHƠN
-- ============================================
INSERT INTO destinations (name, slug, location, description, image, price, type, is_featured, rating) VALUES
('Hòn Khô', 'hon-kho', 'Xã Nhơn Lý, Quy Nhơn', 'Hòn đảo nhỏ với bãi tắm đẹp hoang sơ, nước biển trong xanh như ngọc bích.', 'hon-kho.jpg', 50000, 'đảo', 1, 4.8),
('Ghềnh Ráng', 'ghenh-rang', 'Phường Ghềnh Ráng, Quy Nhơn', 'Khu du lịch nổi tiếng với bãi đá trắng xếp tầng độc đáo.', 'ghenh-rang.jpg', 25000, 'biển', 1, 4.7),
('Kỳ Co', 'ky-co', 'Xã Nhơn Lý, Quy Nhơn', 'Bãi biển đẹp nhất Quy Nhơn với eo gió, cát trắng mịn.', 'ky-co.jpg', 80000, 'biển', 1, 4.9),
('Tháp Đôi', 'thap-doi', 'TP Quy Nhơn', 'Di tích Chăm Pa cổ đại với hai tháp đôi độc đáo.', 'thap-doi.jpg', 20000, 'di tích', 1, 4.5),
('Cù Lao Xanh', 'cu-lao-xanh', 'Xã Nhơn Châu, Quy Nhơn', 'Hòn đảo xanh mát với làng chài yên bình.', 'cu-lao-xanh.jpg', 100000, 'đảo', 1, 4.8),
('Eo Gió', 'eo-gio', 'Bán đảo Phương Mai, Quy Nhơn', 'Điểm ngắm bình minh và hoàng hôn đẹp nhất Quy Nhơn.', 'eo-gio.jpg', 40000, 'biển', 0, 4.6);

-- ============================================
-- DỮ LIỆU MẪU - TOUR
-- ============================================
INSERT INTO tours (name, duration, departure_location, price, schedule, included, excluded, image, is_hot, status) VALUES
('Tour Khám Phá Quy Nhơn 3N2Đ', '3 ngày 2 đêm', 'TP Hồ Chí Minh', 2990000, 'Ngày 1: Đến Quy Nhơn - Tháp Đôi\nNgày 2: Kỳ Co - Hòn Khô - Eo Gió\nNgày 3: Ghềnh Ráng - Về', 'Khách sạn, ăn sáng, xe đưa đón, vé thắng cảnh', 'Vé máy bay, chi tiêu cá nhân', 'tour-1.jpg', 1, 'available'),
('Tour Ghềnh Ráng - Kỳ Co 1 Ngày', '1 ngày', 'Quy Nhơn', 599000, 'Sáng: Ghềnh Ráng\nChiều: Kỳ Co - Hòn Khô', 'Xe đưa đón, ăn trưa', 'Chi tiêu cá nhân', 'tour-2.jpg', 1, 'available'),
('Tour Cù Lao Xanh 1 Ngày', '1 ngày', 'Cảng Quy Nhơn', 899000, 'Sáng: Ra đảo\nChiều: Lặn ngắm san hô', 'Tàu, ăn trưa, thiết bị lặn', 'Chi tiêu cá nhân', 'tour-3.jpg', 0, 'available');