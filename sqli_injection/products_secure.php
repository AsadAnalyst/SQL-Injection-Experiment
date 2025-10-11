<?php
// SECURE VERSION - products_secure.php
// This version uses prepared statements to prevent SQL injection

// 1. Database Configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "assignment_db";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$output_rows = [];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 101; // Input validation

// SECURE QUERY - Using prepared statements
$stmt = $conn->prepare("SELECT name, price FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $output_rows[] = $row;
    }
} else {
    $output_rows[] = ['name' => 'Query Failed', 'price' => 'Error occurred'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SQL Injection Lab - Secure Products</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h1 { color: #333; text-align: center; }
        .secure-badge { background: #5cb85c; color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px; }
        code { background: #eee; padding: 2px 4px; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secure Product Lookup <span class="secure-badge">PROTECTED</span></h1>
        <p>This version uses prepared statements and input validation to prevent SQL injection.</p>
        <p>Secure URL parameter: <code>?id=[integer_only]</code></p>
        <p>Current product ID: <strong><?php echo htmlspecialchars($product_id); ?></strong></p>
        
        <h2>Results:</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
            </tr>
            <?php foreach ($output_rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <div style="margin-top: 20px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px;">
            <strong>Security Features Implemented:</strong>
            <ul>
                <li>Prepared statements prevent SQL injection</li>
                <li>Input validation ensures only integers are accepted</li>
                <li>Output is properly escaped with htmlspecialchars()</li>
                <li>Error messages don't reveal database structure</li>
            </ul>
        </div>
    </div>
</body>
</html>