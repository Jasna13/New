<?php
session_start();

$host = "localhost";
$dbname = "medico_shop";
$username = "root";
$password = "root";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['searchQuery'])) {
    $searchQuery = $_POST['searchQuery'];
    $likeQuery = '%' . $searchQuery . '%';

    $stmt = $conn->prepare("SELECT id, name AS product_name, image AS product_image, price AS original_price, discounted_price FROM products WHERE name LIKE ?");
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($searchResults); // Check if this shows data
    $stmt->close();
} else {
    echo json_encode(["error" => "No search query received"]);
}

$conn->close();
?>
