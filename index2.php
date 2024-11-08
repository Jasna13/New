<?php
// Database connection
$host = 'localhost';
$db = 'medico_shop';
$user = 'root'; // Change to your DB username
$pass = 'root'; // Change to your DB password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for total products
$product_query = "SELECT COUNT(*) AS total_products FROM products";
$product_result = $conn->query($product_query);
$total_products = $product_result->fetch_assoc()['total_products'];

// // Query for stock status (low stock example: products with less than 5 items)
// $low_stock_query = "SELECT COUNT(*) AS low_stock_products FROM products WHERE stock < 5";
// $low_stock_result = $conn->query($low_stock_query);
// $low_stock_products = $low_stock_result->fetch_assoc()['low_stock_products'];

// // Query for active staff
// $staff_query = "SELECT COUNT(*) AS active_staff FROM staff WHERE status = 'active'";
// $staff_result = $conn->query($staff_query);
// $active_staff = $staff_result->fetch_assoc()['active_staff'];

// // Query for total suppliers
// $supplier_query = "SELECT COUNT(*) AS total_suppliers FROM suppliers";
// $supplier_result = $conn->query($supplier_query);
// $total_suppliers = $supplier_result->fetch_assoc()['total_suppliers'];

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php">Product Management</a></li>
                <li><a href="stock.php">Stock Management</a></li>
                <li><a href="staff.php">Staff Management</a></li>
                <li><a href="order.php">Order Management</a></li>
            </ul>
        </nav>
    </header>

    <section id="dashboard" class="panel">
        <h2>Welcome Admin,</h2>
        <div class="grid-container">
            <div class="grid-item">
                <h3>Total Products</h3>
                <p><?php echo $total_products; ?></p>
            </div>
            <div class="grid-item">
                <h3>Stock Status</h3>
                <p>Low in <?php echo $low_stock_products; ?> products</p>
            </div>
            <div class="grid-item">
                <h3>Staff</h3>
                <p><?php echo $active_staff; ?> active staff</p>
            </div>
            <div class="grid-item">
                <h3>Suppliers</h3>
                <p><?php echo $total_suppliers; ?> suppliers</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>
</body>
</html>
