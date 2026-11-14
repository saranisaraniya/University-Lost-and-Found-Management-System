-- UniFind Campus Lost & Found System - Clean Database Initialization
DROP DATABASE IF EXISTS unifind_db;
CREATE DATABASE unifind_db;
USE unifind_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS claims;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) DEFAULT NULL,
    user_type ENUM('student', 'staff', 'admin') DEFAULT 'student',
    reg_no_or_staff_id VARCHAR(50) DEFAULT NULL,
    department VARCHAR(100) DEFAULT 'Physical Science',
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('registered', 'admin') DEFAULT 'registered',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Items Table
CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    report_type ENUM('lost', 'found') NOT NULL,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(150) NOT NULL,
    item_date DATE NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    status ENUM('open', 'under_review', 'claimed', 'returned') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Claims Table
CREATE TABLE claims (
    claim_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    claimant_id INT NOT NULL,
    verification_proof TEXT NOT NULL,
    claim_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (claimant_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Default Admin Account (Password: admin123)
INSERT INTO users (full_name, email, phone, user_type, reg_no_or_staff_id, department, password_hash, role) 
VALUES ('System Admin', 'admin@unifind.edu', '+94 77 123 4567', 'admin', 'ADMIN/001', 'Physical Science Department', '$2y$10$wN3eO1G/e1k1e4L1K1E4l.1K1E4l1k1e4L1K1E4l1k1e4L1K1E4', 'admin');

-- Sample Verified Listings
INSERT INTO items (user_id, report_type, title, category, description, location, item_date, image_path, approval_status, status) 
VALUES 
(1, 'lost', 'Dell Inspiron 15 Laptop', 'Electronics', 'Dark silver laptop inside a Targus black bag.', 'Science Faculty Lab A', '2026-11-01', 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=600&q=80', 'approved', 'open'),
(1, 'found', 'University Identity Card', 'ID Cards', 'Identity card belonging to Physical Science student.', 'Main Library 2nd Floor', '2026-11-02', 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=600&q=80', 'approved', 'open');
