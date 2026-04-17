CREATE DATABASE IF NOT EXISTS absensi_hkbp;
USE absensi_hkbp;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('ADMIN', 'GSM')
);

CREATE TABLE IF NOT EXISTS absensi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    jenis_kegiatan ENUM('IBADAH SESI 1', 'IBADAH SESI 2', 'MINGGU CERIA', 'SERMON'),
    foto VARCHAR(255),
    waktu_absen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Akun Default (Password: 123456)
INSERT IGNORE INTO users (nama, username, password, role) VALUES 
('Admin Apage', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN'),
('Guru Satu', 'gsm1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'GSM');