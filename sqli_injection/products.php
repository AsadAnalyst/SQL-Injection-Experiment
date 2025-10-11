<?php
// FILE: products.php
// VULNERABLE TO: Union-Based Data Exfiltration (Task 2.B)

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
$product_id = isset($_GET['id']) ? $_GET['id'] : 101;

// !!! VULNERABLE QUERY - Two Columns Selected !!!
$sql = "SELECT name, price FROM products WHERE product_id = '$product_id'";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $output_rows[] = $row;
    }
} else {
    // Show error if query fails (useful for debugging Union syntax)
    $output_rows[] = ['name' => 'Query Failed', 'price' => $conn->error];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SQL Injection Lab - Products</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h1 { color: #333; text-align: center; }
        code { background: #eee; padding: 2px 4px; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Lookup</h1>
        <p>Vulnerable URL parameter: <code>?id=[input]</code></p>
        <p>Access page: <code>http://localhost/sqli_lab/products.php?id=101</code></p>
        
        <h2>Results:</h2>
        <table>
            <tr>
                <th>Column 1 (Product Name)</th>
                <th>Column 2 (Price)</th>
            </tr>
            <?php foreach ($output_rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>