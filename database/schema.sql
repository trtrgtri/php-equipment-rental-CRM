CREATE DATABASE IF NOT EXISTS equipment_rental_crm
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE equipment_rental_crm;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE renters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30),
    status ENUM('new', 'contacted', 'approved', 'inactive') NOT NULL DEFAULT 'new',
    note TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    UNIQUE KEY unique_renter_email (email),
    INDEX idx_renters_created_at (created_at),
    INDEX idx_renters_status_created_at (status, created_at)
);

CREATE TABLE rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rental_code VARCHAR(50) NOT NULL,
    renter_name VARCHAR(100) NOT NULL,
    renter_email VARCHAR(150),
    equipment_name VARCHAR(150) NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL DEFAULT 0,
    status ENUM('pending', 'active', 'returned', 'overdue', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    UNIQUE KEY unique_rental_code (rental_code),
    INDEX idx_rentals_created_at (created_at),
    INDEX idx_rentals_status_created_at (status, created_at),
    INDEX idx_rentals_renter_email (renter_email)
);
