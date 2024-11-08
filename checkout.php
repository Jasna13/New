<?php
session_start();
$con = mysqli_connect("localhost", "root", "root", "medico_shop");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$uid = $_SESSION['uid'] ?? 0;
$cart_items = [];
$grand_total = 0;

// Get the product_id from the URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    // Fetch only the selected product from the cart for this user
    $query = "SELECT cart.cid, products.name, products.price, cart.quantity, (products.price * cart.quantity) AS total
              FROM cart
              INNER JOIN products ON cart.id = products.id
              WHERE cart.uid = ? AND cart.id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $uid, $product_id);
} else {
    echo "Invalid product selection.";
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $grand_total += $row['total'];
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="header-container">
        <span class="brand-name">MediCare</span>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Add to Cart</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="profile.php">Profile</a></li>
            <?php if (isset($_SESSION['uid'])): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<style>
    /* Reusing styles from add_to_cart.php */
    body, * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #06782c;
        padding: 27px;
        color: white;
    }

    nav a {
        color: white;
        text-decoration: none;
        margin-left: 20px;
        font-size: 16px;
    }

    nav a:hover {
        text-decoration: underline;
    }

    /* Checkout container styling */
    .checkout-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    /* Order summary styling */
    .order-summary {
        margin-bottom: 30px;
    }

    .order-summary table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #43A047;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e2e2e2;
    }

    /* Checkout form styling */
    .checkout-form {
        display: flex;
        flex-direction: column;
    }

    .checkout-form label {
        font-size: 16px;
        margin-top: 10px;
    }

    .checkout-form input, .checkout-form select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-top: 5px;
    }

    .checkout-form input[type="submit"] {
        background-color: #43A047;
        color: white;
        cursor: pointer;
        font-size: 18px;
        margin-top: 20px;
    }

    .checkout-form input[type="submit"]:hover {
        background-color: #388E3C;
    }
</style>

<div class="checkout-container">
    <h2>Order Summary</h2>
    <div class="order-summary">
        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['price']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo htmlspecialchars($item['total']); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3">Grand Total</th>
                <th><?php echo htmlspecialchars($grand_total); ?></th>
            </tr>
        </table>
    </div>

    <!-- Checkout Form -->
    <h2>Shipping Details</h2>
    <form action="process_checkout.php" method="POST" class="checkout-form">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required>
        
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>
        
        <label for="contact">Contact Number</label>
        <input type="text" id="contact" name="contact" required>
        
        <label for="payment">Payment Method</label>
        <select id="payment" name="payment">
            <option value="credit_card">Credit Card</option>
            <option value="upi">UPI</option>
            <option value="cod">Cash on Delivery</option>
        </select>

        <input type="submit" value="Confirm Order">
    </form>
</div>
</body>
<footer>
    <p>&copy; 2024 MediCare. All rights reserved.</p>
</footer>
</html>
