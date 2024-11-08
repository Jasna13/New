<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'medico_shop');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productToEdit = null;

// Function to convert image to Base64
function imageToBase64($imageFile) {
    $imageData = file_get_contents($imageFile);
    return 'data:' . mime_content_type($imageFile) . ';base64,' . base64_encode($imageData);
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];
    $expiry_date = $_POST['expiry_date'];  // New expiry date field

    // Handle image upload
    if (isset($_FILES['images']) && $_FILES['images']['error'] == 0) {
        $image = $_FILES['images'];
        $imagePath = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        $imagePath = '';
    }

    $requires_prescription = isset($_POST['requires_prescription']) ? 1 : 0;

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO products (name, price, stock, category, discounted_price, image, requires_prescription, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sdisssis', $name, $price, $stock, $category, $discount, $imagePath, $requires_prescription, $expiry_date);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error adding product: " . $stmt->error;
    }
}

// Handle Edit Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $productId = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $productToEdit = $result->fetch_assoc();
    }
}

// Handle Update Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];
    $expiry_date = $_POST['expiry_date'];  // New expiry date field

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imagePath = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        $imagePath = $productToEdit['image'];
    }

    $requires_prescription = isset($_POST['requires_prescription']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ?, category = ?, discounted_price = ?, image = ?, requires_prescription = ?, expiry_date = ? WHERE id = ?");
    $stmt->bind_param('sdisssisi', $name, $price, $stock, $category, $discount, $imagePath, $requires_prescription, $expiry_date, $id);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
        header('Location: products.php');
        exit;
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}

// Fetch products for display
$products = [];
$result = $conn->query("SELECT * FROM products");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <h1>Product Management</h1>
        <nav>
            <ul>
                <li><a href="index.html">Dashboard</a></li>
                <li><a href="products.php">Product Management</a></li>
                <li><a href="stock.php">Stock Management</a></li>
                <li><a href="staff.php">Staff Management</a></li>
                <li><a href="order.php">Order Management</a></li>
                <li><a href="prescription.php">Priscription Mangement</a></li>
                <li><a href="user.php">User Management</a></li>
            </ul>
        </nav>
    </header>
    <style>
    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }
    header {
        background-color: #333;
        color: white;
        padding: 20px;
        text-align: center;
        height:130px;
    }
    nav{
        background-color:#333;
    }
    nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    nav ul li {
        /* display: inline; */
        /* margin-right: 20px; */
    }
    nav ul li a {
        color: white;
        text-decoration: none;
    }
    section {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #444;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    button.delete {
        background-color: #dc3545;
    }
    button.delete:hover {
        background-color: #c82333;
    }
    img {
        max-width: 80px;
        height: auto;
        border-radius: 5px;
    }
    form {
        margin-bottom: 20px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }
    input, select {
        width: calc(100% - 16px);
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    footer {
        text-align: center;
        padding: 10px;
        background-color: #444;
        color: white;
        position: relative;
        bottom: 0;
        width: 100%;
        margin-top: 20px;
    }
    /* Style for the Prescription Requirement Column */
.prescription-status {
text-align: center;
padding: 10px;
font-weight: bold;
border-radius: 5px;
}

/* "Yes" Style (indicating prescription required) */
.prescription-status.yes {
background-color: #28a745; /* Green color */
color: black;
}

/* "No" Style (indicating no prescription required) */
.prescription-status.no {
background-color: #dc3545; /* Red color */
color: white;
}

</style>
<section id="product">
    <h2>Manage Products</h2>
    <form id="product-form" method="POST" enctype="multipart/form-data">
        <?php if ($productToEdit): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $productToEdit['id'] ?>">
            <h3>Edit Product</h3>
        <?php else: ?>
            <input type="hidden" name="action" value="add">
            <h3>Add New Product</h3>
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required value="<?= $productToEdit['name'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required value="<?= $productToEdit['price'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="stock">Stock Quantity:</label>
            <input type="number" id="stock" name="stock" required value="<?= $productToEdit['stock'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="medications" <?= isset($productToEdit) && $productToEdit['category'] == 'medications' ? 'selected' : '' ?>>Medications</option>
                <option value="medical_supplies" <?= isset($productToEdit) && $productToEdit['category'] == 'medical_supplies' ? 'selected' : '' ?>>Medical Supplies</option>
                <option value="devices" <?= isset($productToEdit) && $productToEdit['category'] == 'devices' ? 'selected' : '' ?>>Devices</option>
            </select>
        </div>
        <div class="form-group">
            <label for="discount">Discounted Price:</label>
            <input type="number" id="discount" name="discount" value="<?= $productToEdit['discounted_price'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="images">Upload Image:</label>
            <input type="file" id="images" name="images">
        </div>
        <div class="form-group">
            <label for="product-prescription">Requires Prescription:</label>
            <input type="checkbox" id="product-prescription" name="requires_prescription" 
                   <?= isset($productToEdit) && $productToEdit['requires_prescription'] ? 'checked' : '' ?>>
        </div>
        <div class="form-group">
            <label for="expiry_date">Expiry Date:</label>
            <input type="date" id="expiry_date" name="expiry_date" value="<?= $productToEdit['expiry_date'] ?? '' ?>">
        </div>
        <button type="submit">Save Product</button>
    </form>

    <h3>Existing Products</h3>
    <table>
        <thead>
            <tr>
            <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Discount</th>
                <th>Prescription Required</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><img src="<?= $product['image'] ?>" alt="Product Image"></td>
            <td><?= $product['name'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['stock'] ?></td>
            <td><?= ucfirst(str_replace('_', ' ', $product['category'])) ?></td>
            <td><?= $product['discounted_price'] ?? 'N/A' ?></td>
            <td class="prescription-status <?= $product['requires_prescription'] ? 'yes' : 'no' ?>">
                <?= $product['requires_prescription'] ? 'Yes' : 'No' ?>
            </td>
            <td><?= $product['expiry_date'] ?? 'N/A' ?></td>
            <td>
                <!-- Edit button -->
                <form action="products.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <button type="submit">Edit</button>
                </form>
                
                <!-- Delete button -->
                <form action="products.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <button type="submit" class="delete">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</section>
</body>
</html>
