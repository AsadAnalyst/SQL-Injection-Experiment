<?php
// SECURE VERSION - login_secure.php
// This version uses prepared statements to prevent SQL injection

// 1. Database Configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "assignment_db";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SECURE QUERY - Using prepared statements
    $stmt = $conn->prepare("SELECT username, role, secret_flag FROM users WHERE username = ? AND password_hash = MD5(?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $message = "Login Successful! Welcome, " . htmlspecialchars($row['username']) . " (" . htmlspecialchars($row['role']) . ")<br>Your Secret Flag: " . htmlspecialchars($row['secret_flag']);
    } else {
        $message = "Login Failed. Invalid username or password.";
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SQL Injection Lab - Secure Version</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 400px; margin: auto; }
        h1 { color: #333; text-align: center; }
        .secure-badge { background: #5cb85c; color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 90%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background-color: #5cb85c; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px; width: 100%; }
        .message { padding: 10px; margin-top: 15px; border-radius: 4px; text-align: center; }
        .success { background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
        .failure { background-color: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secure User Login <span class="secure-badge">PROTECTED</span></h1>
        <p>This version uses prepared statements to prevent SQL injection.</p>
        <p>Test Credentials: <code>alice</code> / <code>password</code></p>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Successful') !== false ? 'success' : 'failure'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>