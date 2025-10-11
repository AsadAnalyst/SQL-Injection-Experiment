# Advanced SQL Injection Lab Assignment
## Environment Setup Guide

## 0. Environment Setup (CRITICAL FIRST STEPS)

### Step 0.1: Install and Start Services

1. **Download and Install XAMPP**
   - Go to https://www.apachefriends.org/
   - Download XAMPP for Windows
   - Install with default settings

2. **Start XAMPP Services**
   - Open XAMPP Control Panel as Administrator
   - Click **Start** next to **Apache** (should turn green)
   - Click **Start** next to **MySQL** (should turn green)
   - Verify both services are running

### Step 0.2: Application Deployment

1. **Locate XAMPP web root folder**
   - Default location: `C:\xampp\htdocs\`
   
2. **Create lab folder**
   - Inside htdocs, create folder: `sqli_lab`
   - Full path: `C:\xampp\htdocs\sqli_lab\`

3. **Deploy files**
   - Copy `login.php` to `C:\xampp\htdocs\sqli_lab\login.php`
   - Copy `products.php` to `C:\xampp\htdocs\sqli_lab\products.php`

### Step 0.3: Database Initialization

1. **Access phpMyAdmin**
   - Open browser and navigate to: `http://localhost/phpmyadmin/`
   - Login with username: `root` (leave password blank)

2. **Execute setup script**
   - Click on **SQL** tab
   - Copy and paste the entire content of `setup.sql`
   - Click **Go** to execute
   - Verify `assignment_db` database is created

## 1. Lab Setup and Initial Confirmation

### Test URLs:
- Login page: `http://localhost/sqli_lab/login.php`
- Products page: `http://localhost/sqli_lab/products.php?id=101`

### Test Credentials:
- Username: `alice`
- Password: `password`

## Required Screenshots for Deliverable 1:

### A. login.php Test Access
1. Navigate to `http://localhost/sqli_lab/login.php`
2. Login with `alice` / `password`
3. Take screenshot showing "Login Successful" message with secret flag

### B. products.php Test Access
1. Navigate to `http://localhost/sqli_lab/products.php?id=101`
2. Take screenshot showing "Secure Firewall" product details

---

## Database Schema Information

### users table:
- id (INT, Primary Key)
- username (VARCHAR)
- password_hash (VARCHAR) - MD5 hashed
- role (VARCHAR)
- secret_flag (VARCHAR)

### products table:
- product_id (INT, Primary Key)
- name (VARCHAR)
- price (DECIMAL)

### Test Data:
- alice / password (USER_FLAG_ABC123)
- bob / password (USER_FLAG_DEF456)
- admin / password (FLAG_SQLI_COMPLETE_ZXY789)

---

## Vulnerability Points:

### login.php (POST-based):
```php
$sql = "SELECT username, role, secret_flag FROM users WHERE username = '$username' AND password_hash = MD5('$password')";
```

### products.php (GET-based):
```php
$sql = "SELECT name, price FROM products WHERE product_id = '$product_id'";
```

Both queries are vulnerable to SQL injection due to direct string concatenation without input sanitization.