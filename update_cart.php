<?php
session_start();
$con = mysqli_connect("localhost", "root", "root", "medico_shop");

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Check if cart ID and quantity are set in the POST request
if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

    // Validate quantity (should be greater than 0)
    if ($quantity > 0) {
        // Prepare and execute the update query
        $query = "UPDATE cart SET quantity = ? WHERE cid = ? AND uid = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['uid']);

        if ($stmt->execute()) {
            // Redirect to cart page with a success message
            header("Location: Add_to_cart.php?message=Quantity updated successfully.");
        } else {
            // Redirect to cart page with an error message
            header("Location: Add_to_cart.php?message=Failed to update quantity.");
        }
    } else {
        // Redirect to cart page with an error message for invalid quantity
        header("Location: Add_to_cart.php?message=Quantity must be greater than zero.");
    }
} else {
    // Redirect to cart page if no cart ID or quantity is set
    header("Location: Add_to_cart.php?message=Invalid request.");
}

$stmt->close();
$con->close();
?>
