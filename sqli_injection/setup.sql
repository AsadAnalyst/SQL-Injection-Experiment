--
-- SQL Injection Lab Setup for MySQL/MariaDB
--
-- 1. Create the database
CREATE DATABASE IF NOT EXISTS assignment_db;
USE assignment_db;

-- 2. Create the primary vulnerable table
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(60) NOT NULL,
    role VARCHAR(20) NOT NULL,
    secret_flag VARCHAR(100)
);

-- 3. Insert initial data (All passwords are 'password', MD5 hash is 5f4dcc3b5aa765d61d8327deb882cf99)
INSERT INTO users (username, password_hash, role, secret_flag) VALUES 
('asad', 'asad', 'user', 'USER_FLAG_ABC123'),
('raza', 'raza', 'user', 'USER_FLAG_DEF456'),
('admin', 'admin', 'admin', 'FLAG_SQLI_COMPLETE_ZXY789');

-- 4. Create a secondary table for Union Attack challenge
DROP TABLE IF EXISTS products;
CREATE TABLE products (
    product_id INT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10, 2)
);

INSERT INTO products (product_id, name, price) VALUES 
(101, 'Secure Firewall', 999.00),
(102, 'Encrypted Monitor', 499.00),
(103, 'Security Camera', 299.00),
(104, 'VPN Router', 199.00);

-- Verify the setup
SELECT 'Database setup complete!' AS status;
SELECT COUNT(*) as user_count FROM users;
SELECT COUNT(*) as product_count FROM products;

-- Note: Students should run this script in phpMyAdmin or MySQL client.