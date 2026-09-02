-- =========================================================
-- PLACEMENT DRIVE SYSTEM - DATABASE
-- Import this file in phpMyAdmin (XAMPP)
-- =========================================================

CREATE DATABASE IF NOT EXISTS placement_cell;
USE placement_cell;

-- =========================================================
-- TABLE: table_drives
-- =========================================================
DROP TABLE IF EXISTS application;
DROP TABLE IF EXISTS table_drives;

CREATE TABLE table_drives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    job_role VARCHAR(150) NOT NULL,
    package DECIMAL(10,2) NOT NULL,
    eligible_branches VARCHAR(255) NOT NULL,
    min_cgpa DECIMAL(4,2) NOT NULL,
    max_backlogs INT NOT NULL,
    drive_date DATE NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(30) DEFAULT 'Open',
    CHECK (package > 0),
    CHECK (min_cgpa >= 0 AND min_cgpa <= 10),
    CHECK (max_backlogs >= 0)
);

-- =========================================================
-- TABLE: application
-- =========================================================
CREATE TABLE application (
    id INT AUTO_INCREMENT PRIMARY KEY,
    drive_id INT NOT NULL,
    student_name VARCHAR(150) NOT NULL,
    roll_number VARCHAR(50) NOT NULL,
    batch VARCHAR(20) NOT NULL,
    category VARCHAR(50) NOT NULL,
    cgpa DECIMAL(4,2) NOT NULL,
    percentage DECIMAL(5,2) NOT NULL,
    backlogs INT NOT NULL,
    resume VARCHAR(255) NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(30) DEFAULT 'Applied',
    FOREIGN KEY (drive_id) REFERENCES table_drives(id) ON DELETE CASCADE,
    CHECK (cgpa >= 0 AND cgpa <= 10),
    CHECK (percentage >= 0 AND percentage <= 100),
    CHECK (backlogs >= 0)
);

-- =========================================================
-- SAMPLE DATA (optional - safe to remove)
-- =========================================================
INSERT INTO table_drives
(company_name, job_role, package, eligible_branches, min_cgpa, max_backlogs, drive_date, status)
VALUES
('TCS', 'Software Developer', 7.00, 'Computer, IT', 7.00, 2, '2026-09-20', 'Open'),
('Infosys', 'System Engineer', 6.50, 'Computer, IT, ENTC', 6.50, 3, '2026-09-25', 'Open'),
('Accenture', 'Associate Analyst', 8.00, 'Computer, IT', 8.00, 1, '2026-10-01', 'Open');
