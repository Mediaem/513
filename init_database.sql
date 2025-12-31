-- Create database if not exists
CREATE DATABASE IF NOT EXISTS wordpress;
USE wordpress;

-- Create FluentCRM Subscribers Table (Simulated)
CREATE TABLE IF NOT EXISTS wp_fc_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    status VARCHAR(20) DEFAULT 'subscribed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Data
INSERT INTO wp_fc_subscribers (first_name, last_name, email, phone) VALUES 
('Mikey', 'Student', 'mikey@craftcanvas.com', '1234567890'),
('Art', 'Collector', 'collector@test.com', '0987654321');