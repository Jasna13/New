<?php
session_start(); // Start the session

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$dbname = "medico_shop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID and fetch product details
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<h2>Product not found!</h2>";
    exit;
}
// Check stock status
$stockStatus = '';
if ($product['stock'] == 0) {
    $stockStatus = "Out of Stock";
} elseif ($product['stock'] <= 10) {
    $stockStatus = "Low Stock";
}
// Current and expiry dates
$currentDate = new DateTime();
$expiryDate = !empty($product['expiry_date']) ? new DateTime($product['expiry_date']) : null;

// Check if the product is expired
if ($expiryDate && $expiryDate < $currentDate) {
    echo "<h2>This product has expired and is no longer available for purchase.</h2>";
    exit;
}

// Fetch user's prescription status if logged in
$prescriptionStatus = 'not uploaded'; // Default status
$canPurchase = false; // Flag to indicate whether user can purchase

if (isset($_SESSION['uid'])) {
    $userId = $_SESSION['uid'];
    $sqlPrescription = "SELECT status FROM prescriptions WHERE user_id = ? AND product_id = ?";
    $stmtPrescription = $conn->prepare($sqlPrescription);
    $stmtPrescription->bind_param("ii", $userId, $productId);
    $stmtPrescription->execute();
    $resultPrescription = $stmtPrescription->get_result();
    
    if ($resultPrescription->num_rows > 0) {
        $prescriptionData = $resultPrescription->fetch_assoc();
        $prescriptionStatus = $prescriptionData['status'];
        // Check if the prescription is approved
        if ($prescriptionStatus == 'approved') {
            $canPurchase = true;
        }
    }
    $stmtPrescription->close();
}

// Close the connection
$stmt->close();
$finalPrice = $product['discounted_price'] > 0 ? $product['price'] - $product['discounted_price'] : $product['price'];
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?> | MediCare</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }
        button.buy-now {
            background-color: #ff9800;
            color: white;
            margin-left: 10px;
        }
        
        button.buy-now:hover {
            background-color: #e67e22;
        }

        .out-of-stock {
            color: red;
            font-weight: bold;
            margin-top: 15px;
        }

        .low-stock {
            color: orange;
            font-weight: bold;
            margin-top: 15px;
        }
        /* Header Styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #06782c;
            padding: 15px;
            color: white;
        }

        /* Logo Styling */
        .logo h1 {
            font-size: 24px;
        }

        /* Navigation Styling */
        nav {
            display: flex;
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

        /* Product Card Styling */
        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            overflow: hidden;
        }

        /* Image Styling */
        .product-image img {
            max-width: 250px;
            border-radius: 10px;
            object-fit: cover;
        }

        /* Product Info Styling */
        .product-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .product-info h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        footer {
            background-color: #06782c;
            margin-top: 200px;
            text-align: center;
            padding: 10px;
            color: white;
        }

        .product-info .price {
            font-size: 20px;
            color: red;
            margin-bottom: 15px;
        }

        /* Button Styling */
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.add-to-cart {
            background-color: #06782c;
            color: white;
        }

        button.add-to-cart:hover {
            background-color: #0056b3;
        }

        button.buy-now {
            background-color: #06782c;
            color: white;
        }

        button.buy-now:hover {
            background-color: #0056b3;
        }

        .login-required {
            color: red;
            margin-top: 15px;
        }

        /* Prescription Upload Form Styling */
        .prescription-upload {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .prescription-upload label {
            font-weight: bold;
        }

        .prescription-upload input[type="file"] {
            margin-top: 5px;
            display: block;
        }

        .prescription-upload button {
            margin-top: 10px;
            background-color: #06782c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
        }

        .prescription-upload button:hover {
            background-color: #0056b3;
        }

        /* Styling for approved prescription message */
        .approved {
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-family: 'Arial', sans-serif;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border: 2px solid #388e3c;
            background-color: #28a745;
        }
    </style>
</head>
<body onload="displayExpiryAlert()">
    <header>
        <h1 class="logo">MediCare</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="Add_to_cart.php">Add to Cart</a></li>
                <?php if (isset($_SESSION['uid'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="product-card">
            <div class="product-image">
                <img src="../<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" />
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></h2>
                <p>Expiry Date: <?php echo $expiryDate ? $expiryDate->format("d M Y") : "Unavailable"; ?></p>
                <p>Price: 
                    <?php if ($product['discounted_price'] > 0): ?>
                        <span class="original-price" style="text-decoration: line-through;">₹<?php echo number_format($product['price'], 2); ?></span>
                        ₹<?php echo number_format($finalPrice, 2); ?>
                    <?php else: ?>
                        ₹<?php echo number_format($product['price'], 2); ?>
                    <?php endif; ?>
                </p>
                <?php if ($stockStatus == "Out of Stock"): ?>
                    <p class="out-of-stock">Out of Stock</p>
                <?php elseif ($stockStatus == "Low Stock"): ?>
                    <p class="low-stock">Low Stock</p>
                <?php endif; ?>
                <?php if (isset($_SESSION['uid'])): ?>
                    <!-- Prescription check -->
                    <?php if ($product['requires_prescription']): ?>
                        <?php if ($prescriptionStatus === 'approved'): ?>
                            <p class="approved">You can now purchase the product, Admin approved the prescription.</p>
                            <?php if ($stockStatus != "Out of Stock"): ?>
                                <!-- Form for Add to Cart -->
<form action="add_to_cart_actions.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
    <input type="hidden" name="price" value="<?php echo htmlspecialchars($finalPrice); ?>">
    <button type="submit" class="add-to-cart">Add to Cart</button>
</form>

<!-- Form for Buy Now -->
<form action="buy_now.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
    <input type="hidden" name="price" value="<?php echo htmlspecialchars($finalPrice); ?>">
    <button type="submit" class="buy-now">Buy Now</button>
</form>

                            <?php endif; ?>
                        <?php else: ?>
                            <p class="login-required">Your prescription is currently <?php echo htmlspecialchars($prescriptionStatus); ?>.</p>
                            <div class="prescription-upload">
                                <form action="upload_prescription.php" method="post" enctype="multipart/form-data">
                                    <label for="prescription">Upload Prescription:</label>
                                    <input type="file" name="prescription" id="prescription" required>
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <button type="submit" class="upload">Upload</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($stockStatus != "Out of Stock"): ?>
                            <!-- Form for Add to Cart -->
<form action="add_to_cart_actions.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
    <input type="hidden" name="price" value="<?php echo htmlspecialchars($finalPrice); ?>">
    <button type="submit" class="add-to-cart">Add to Cart</button>
</form>

<!-- Form for Buy Now -->
<form action="buy_now.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
    <input type="hidden" name="price" value="<?php echo htmlspecialchars($finalPrice); ?>">
    <button type="submit" class="buy-now">Buy Now</button>
</form>

                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="login-required">Please <a href="login.php">login</a> to add this product to your cart.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 MediCare. All Rights Reserved.</p>
    </footer>

    <script>
        function displayExpiryAlert() {
            var expiryDate = "<?php echo $expiryDate ? $expiryDate->format("d M Y") : 'Unavailable'; ?>";
            alert("Expiry Date: " + expiryDate);
        }
    </script>
</body>
</html>
