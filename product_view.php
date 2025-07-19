<?php
// Connect to DB
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

// Get Product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Fetch sizes
$size_stmt = $conn->prepare("SELECT size, stock FROM product_sizes WHERE product_id = ?");
$size_stmt->bind_param("i", $product_id);
$size_stmt->execute();
$size_result = $size_stmt->get_result();
$sizes = [];
while ($row = $size_result->fetch_assoc()) {
  $sizes[] = $row;
}

// Handle not found
if (!$product) {
  echo "<h2 style='text-align:center;margin-top:50px;'>Product not found.</h2>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']) ?> | OFFTHREADZ</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 40px 20px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.07);
      display: flex;
      gap: 40px;
      padding: 40px;
      flex-wrap: wrap;
    }

    .image-gallery {
      flex: 1 1 300px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .image-gallery img {
      width: 100%;
      border-radius: 12px;
      object-fit: cover;
      max-height: 400px;
    }

    .product-details {
      flex: 1 1 300px;
    }

    .product-details h1 {
      font-size: 1.8rem;
      margin-bottom: 10px;
    }

    .product-details .price {
      font-size: 1.3rem;
      font-weight: bold;
      color: #111;
      margin-bottom: 20px;
    }

    .product-details .description {
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 25px;
      color: #333;
    }

    .sizes {
      margin-bottom: 20px;
    }

    .sizes strong {
      display: block;
      margin-bottom: 8px;
    }

    .sizes ul {
      list-style: none;
      padding: 0;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .sizes li {
      padding: 8px 14px;
      border-radius: 6px;
      background: #f0f0f0;
      font-size: 0.9rem;
    }

    .out-of-stock-msg {
      color: red;
      font-weight: bold;
      margin-top: 10px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="image-gallery">
    <img src="<?= htmlspecialchars($product['front_image']) ?>" alt="Front Image">
    <img src="<?= htmlspecialchars($product['back_image']) ?>" alt="Back Image">
  </div>

  <div class="product-details">
    <h1><?= htmlspecialchars($product['name']) ?></h1>
    <div class="price">₱<?= number_format($product['price'], 2) ?></div>
    <div class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></div>

    <div class="sizes">
      <strong>Available Sizes:</strong>
      <ul>
        <?php
          $hasStock = false;
          foreach ($sizes as $size) {
            if ($size['stock'] > 0) {
              $hasStock = true;
              echo "<li>{$size['size']} ({$size['stock']} in stock)</li>";
            }
          }

          if (!$hasStock) {
            echo "<div class='out-of-stock-msg'>All sizes are currently out of stock.</div>";
          }
        ?>
      </ul>
    </div>
  </div>
</div>

</body>
</html>
          