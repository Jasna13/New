<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    die("Error: User not logged in.");
}

// Get user ID
$user_id = $_SESSION['uid'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "medico_shop";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if file was uploaded without errors
if (isset($_FILES['prescription']) && $_FILES['prescription']['error'] == 0) {
    $targetDir = "prescriptions/";
    
    // Ensure the directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
    }

    $filename = basename($_FILES['prescription']['name']);
    $targetFilePath = $targetDir . $filename;
    
    // Move the file to the target directory
    if (move_uploaded_file($_FILES['prescription']['tmp_name'], $targetFilePath)) {
        // Insert the prescription record into the database
        $stmt = $conn->prepare("INSERT INTO prescriptions (user_id, product_id, prescription_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $_POST['product_id'], $targetFilePath);
        
        if ($stmt->execute()) {
            // If successful, display a JavaScript alert and redirect back to the products page
            echo "<script>
                alert('Prescription uploaded successfully.');
                window.location.href = 'products.php'; // Change to the page you want to redirect to
            </script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error: Failed to move the uploaded file.');</script>";
    }
} else {
    echo "<script>alert('Error: " . $_FILES['prescription']['error'] . "');</script>";
}

$conn->close();
?>



