<?php
// PHP Script for a Vulnerable Login Page

// 1. Database Configuration (Adjust if not using XAMPP defaults)
$db_host = "localhost";
$db_user = "root";
$db_pass = ""; // Default XAMPP password is empty
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

    // !!! VULNERABLE LINE !!!
    // User input is directly concatenated into the SQL query string
    $sql = "SELECT username, role, secret_flag FROM users WHERE username = '$username' AND password_hash = '$password'";
    
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Displaying the secret_flag confirms a successful, privileged login
        $message = "Login Successful! Welcome, " . htmlspecialchars($row['username']) . " (" . $row['role'] . ")<br>Your Secret Flag: " . htmlspecialchars($row['secret_flag']);
    } else {
        // Default error message for failed login
        $message = "Login Failed. Check username and password.";

        // !!! IMPORTANT FOR TASK 3.A !!!
        // Uncomment the line below to enable Error-Based SQLi for Task 3.A
        $message = "Login Failed. MySQL Error: " . $conn->error; 
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SQL Injection Lab</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 400px; margin: auto; }
        h1 { color: #333; text-align: center; }
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
        <h1>Vulnerable User Login</h1>
        <p>Lab Access Credentials: <code>asad/asad</code>, <code>raza/raza</code>, or <code>admin/admin</code></p>
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