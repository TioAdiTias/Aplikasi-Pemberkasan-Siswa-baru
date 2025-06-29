CREATE DATABASE sekolah CHARSET=utf8mb4;
USE sekolah;

-- tabel admin
CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- buat akun admin bawaan (password “admin123”)
INSERT INTO admin(username,password_hash)
VALUES('admin', 
  -- gunakan PHP: password_hash('admin123',PASSWORD_DEFAULT)
  '$2y$10$e0NR9c6zQKZnGp.EW1W3N.wiWrOQhHnwNzVzJgbEUc6ZvygI0BS1W'
);

-- tabel siswa
CREATE TABLE siswa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  no_pendaftar CHAR(3) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  jurusan ENUM('TKR','TSM','TKJ') NOT NULL,
  no_hp VARCHAR(20) NOT NULL,
  alamat TEXT NOT NULL,
  asal_sekolah VARCHAR(100) NOT NULL,
  foto VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
