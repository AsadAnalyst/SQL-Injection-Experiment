# XAMPP Installation and Setup Guide

## Step 1: Download XAMPP

1. **Open your web browser** and go to: https://www.apachefriends.org/
2. **Click "Download"** button
3. **Select "XAMPP for Windows"** - it should automatically detect your OS
4. **Download the latest version** (usually around 150-200 MB)
5. **Save the installer** to your Downloads folder

## Step 2: Install XAMPP

1. **Locate the downloaded file** (usually named something like `xampp-windows-x64-8.2.12-0-VS16-installer.exe`)
2. **Right-click the installer** and select **"Run as administrator"**
3. **Follow the installation wizard:**
   - Click "Next" on the welcome screen
   - **Component Selection**: Make sure these are checked:
     - ✅ Apache
     - ✅ MySQL
     - ✅ PHP
     - ✅ phpMyAdmin
   - **Installation Directory**: Use default `C:\xampp` (recommended)
   - Click "Next" through remaining screens
   - Click "Finish" when installation completes

## Step 3: Start XAMPP Services

1. **Open XAMPP Control Panel**:
   - Go to Start Menu → Search for "XAMPP"
   - **Run as Administrator** (important!)

2. **Start Required Services**:
   - Click **"Start"** button next to **Apache** 
     - Status should turn GREEN and show "Running"
   - Click **"Start"** button next to **MySQL**
     - Status should turn GREEN and show "Running"

3. **Verify Services are Running**:
   - Both Apache and MySQL should show green "Running" status
   - Apache typically runs on Port 80
   - MySQL typically runs on Port 3306

## Step 4: Test XAMPP Installation

### Test Apache Web Server:
1. **Open your web browser**
2. **Navigate to**: `http://localhost`
3. **Expected Result**: You should see the XAMPP welcome page

### Test phpMyAdmin:
1. **Navigate to**: `http://localhost/phpmyadmin`
2. **Expected Result**: phpMyAdmin login page appears
3. **Login with**:
   - Username: `root`
   - Password: (leave blank)

## Step 5: Deploy Your SQL Injection Lab

### Create Lab Directory:
1. **Navigate to**: `C:\xampp\htdocs\`
2. **Create new folder**: `sqli_lab`
3. **Full path should be**: `C:\xampp\htdocs\sqli_lab\`

### Copy Lab Files:
Copy these files from your current directory to `C:\xampp\htdocs\sqli_lab\`:
- `login.php`
- `products.php`
- `login_secure.php` 
- `products_secure.php`

## Step 6: Initialize Database

1. **Open phpMyAdmin**: `http://localhost/phpmyadmin`
2. **Click "SQL" tab**
3. **Copy and paste** the entire content from `setup.sql`
4. **Click "Go"** to execute
5. **Verify**: You should see `assignment_db` database created in the left sidebar

## Step 7: Test Your Lab Applications

### Test Login Application:
1. **Navigate to**: `http://localhost/sqli_lab/login.php`
2. **Test login with**:
   - Username: `alice`
   - Password: `password`
3. **Expected Result**: "Login Successful! Welcome, alice (user)" with secret flag

### Test Products Application:
1. **Navigate to**: `http://localhost/sqli_lab/products.php?id=101`
2. **Expected Result**: Page showing "Secure Firewall" product with price $999.00

## Troubleshooting Common Issues

### Port 80 Already in Use:
- **Symptom**: Apache won't start, shows red status
- **Solution**: 
  - Close Skype, IIS, or other web servers
  - Or change Apache port in XAMPP config

### MySQL Won't Start:
- **Symptom**: MySQL shows red status
- **Solution**:
  - Check if another MySQL service is running
  - Stop other MySQL services in Windows Services

### Permission Issues:
- **Always run XAMPP Control Panel as Administrator**
- **Check Windows Firewall** - allow Apache and MySQL

### Cannot Access localhost:
- **Check Windows Firewall**
- **Verify Apache is running** (green status)
- **Try**: `http://127.0.0.1` instead of `http://localhost`

## Verification Checklist

- [ ] XAMPP Control Panel opens without errors
- [ ] Apache service starts and shows GREEN status
- [ ] MySQL service starts and shows GREEN status  
- [ ] `http://localhost` shows XAMPP welcome page
- [ ] `http://localhost/phpmyadmin` opens successfully
- [ ] `assignment_db` database created successfully
- [ ] `http://localhost/sqli_lab/login.php` loads and works with alice/password
- [ ] `http://localhost/sqli_lab/products.php?id=101` shows Secure Firewall product

## Next Steps After Successful Setup

Once XAMPP is running and your lab is accessible:

1. **Take screenshots** of successful login and product pages (required for assignment)
2. **Begin SQL injection testing** using the attack documentation
3. **Document all findings** for your lab report

---

**Need Help?** If you encounter any issues during installation, let me know the specific error messages and I'll help troubleshoot!