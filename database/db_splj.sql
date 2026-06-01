-- Database creation script for db_splj

CREATE DATABASE IF NOT EXISTS db_splj;
USE db_splj;

-- Table users
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nomor_whatsapp VARCHAR(20) NOT NULL,
    role_type ENUM('User', 'Admin', 'Koordinator') NOT NULL DEFAULT 'User',
    membership_status VARCHAR(50) DEFAULT 'Regular',
    total_point INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table lapangan
CREATE TABLE IF NOT EXISTS lapangan (
    lapangan_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lapangan VARCHAR(255) NOT NULL,
    jenis_lapangan VARCHAR(100) NOT NULL,
    harga_per_jam DECIMAL(10,2) NOT NULL,
    status_lapangan ENUM('Tersedia', 'Maintenance') NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table booking
CREATE TABLE IF NOT EXISTS booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lapangan_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    durasi INT NOT NULL,
    status_booking ENUM('Pending DP', 'Menunggu Verifikasi', 'Confirmed', 'Rejected', 'Canceled') NOT NULL DEFAULT 'Pending DP',
    total_biaya DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (lapangan_id) REFERENCES lapangan(lapangan_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pembayaran
CREATE TABLE IF NOT EXISTS pembayaran (
    pembayaran_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_type ENUM('DP', 'Pelunasan', 'Refund') NOT NULL DEFAULT 'DP',
    jumlah_bayar DECIMAL(10,2) NOT NULL,
    bukti_transfer VARCHAR(255) NOT NULL,
    status_pembayaran ENUM('Pending', 'Verified', 'Rejected') NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial admin user
-- Password: admin123 -> hashed with password_hash() in PHP
-- Hashed: $2y$10$wKOCrVdpxg.QxPZbe57xquj3gE7wO2U57D93P.R/jK38T0R68mRk6
INSERT INTO users (nama, email, password, nomor_whatsapp, role_type, membership_status, total_point)
VALUES ('Super Admin', 'admin@splj.com', '$2y$10$wKOCrVdpxg.QxPZbe57xquj3gE7wO2U57D93P.R/jK38T0R68mRk6', '081234567890', 'Admin', 'Premium', 0)
ON DUPLICATE KEY UPDATE user_id=user_id;

-- Insert initial lapangan data
INSERT INTO lapangan (nama_lapangan, jenis_lapangan, harga_per_jam, status_lapangan) VALUES
('Futsal Arena A', 'Futsal', 150000.00, 'Tersedia'),
('Futsal Arena B', 'Futsal', 130000.00, 'Tersedia'),
('Tenis Court Main', 'Tenis', 100000.00, 'Tersedia'),
('Badminton Hall 1', 'Badminton', 50000.00, 'Tersedia'),
('Badminton Hall 2', 'Badminton', 50000.00, 'Tersedia')
ON DUPLICATE KEY UPDATE lapangan_id=lapangan_id;
