<?php
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

$id = $_GET['id'] ?? null;

if ($id) {
    // Delete images from folder (optional)
    $images = $conn->query("SELECT image_url FROM product_images WHERE product_id=$id");
    while ($img = $images->fetch_assoc()) {
        $path = "../" . $img['image_url'];
        if (file_exists($path)) unlink($path);
    }

    // Delete from DB
    $conn->query("DELETE FROM product_images WHERE product_id=$id");
    $conn->query("DELETE FROM products WHERE id=$id");

    header("Location: products.php");
    exit;
}
?>
