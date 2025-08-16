<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get query from URL
$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : "";

// Base SQL
$sql = "SELECT id, name, price, front_image 
        FROM products 
        WHERE name LIKE '%$query%' OR description LIKE '%$query%' 
        ORDER BY created_at DESC 
        LIMIT 20"; // limit para hindi sobrang bigat

$result = $conn->query($sql);

// Prepare array
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "price" => $row["price"],
            "front_image" => !empty($row["front_image"]) ? $row["front_image"] : "assets/default-front.png"
        ];
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($products);

// Close connection
$conn->close();
?>
