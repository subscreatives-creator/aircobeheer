-- Airco Beheer Database Setup
-- Run this in phpMyAdmin to create the required tables

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS airco_beheer;
USE airco_beheer;

-- Table: rapporten (maintenance reports)
CREATE TABLE IF NOT EXISTS rapporten (
    id VARCHAR(36) PRIMARY KEY,
    klant VARCHAR(255) NOT NULL,
    locatie VARCHAR(500) NOT NULL,
    datum DATE NOT NULL,
    status ENUM('A', 'B', 'C') NOT NULL DEFAULT 'B',
    samenvatting JSON NOT NULL,
    monteur_opmerking TEXT,
    fotos JSON NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_klant (klant),
    INDEX idx_datum (datum)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: contract_aanvragen (upgrade requests)
CREATE TABLE IF NOT EXISTS contract_aanvragen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rapport_id VARCHAR(36) NOT NULL,
    aangevraagd_op DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rapport (rapport_id),
    FOREIGN KEY (rapport_id) REFERENCES rapporten(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
