CREATE DATABASE IF NOT EXISTS unifind_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE unifind_db;
CREATE TABLE IF NOT EXISTS users (
 user_id INT AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 phone VARCHAR(25) NULL,
 user_type ENUM('student','staff','admin') NOT NULL DEFAULT 'student',
 reg_no_or_staff_id VARCHAR(60) NULL,
 department VARCHAR(120) NULL,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('registered','admin') NOT NULL DEFAULT 'registered',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS items (
 item_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 report_type ENUM('lost','found') NOT NULL,
 title VARCHAR(150) NOT NULL,
 category VARCHAR(60) NOT NULL,
 description TEXT NOT NULL,
 location VARCHAR(180) NOT NULL,
 item_date DATE NOT NULL,
 image_path VARCHAR(500) NULL,
 approval_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
 status ENUM('open','under_review','claimed','returned') NOT NULL DEFAULT 'open',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS claims (
 claim_id INT AUTO_INCREMENT PRIMARY KEY,
 item_id INT NOT NULL,
 claimant_id INT NOT NULL,
 verification_proof TEXT NOT NULL,
 claim_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(item_id) REFERENCES items(item_id) ON DELETE CASCADE,
 FOREIGN KEY(claimant_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
