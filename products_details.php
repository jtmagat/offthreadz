<?php
$conn = new mysqli("localhost", "root", "", "offthreadz_db");
$id = $_GET['id'] ?? 0;

// Get product
$product_sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

// Get images
$images_sql = "SELECT image_url FROM product_images WHERE product_id = ?";
$img_stmt = $conn->prepare($images_sql);
$img_stmt->bind_param("i", $id);
$img_stmt->execute();
$images_result = $img_stmt->get_result();

// Get stock per size
$size_sql = "SELECT size, stock FROM product_sizes WHERE product_id = ?";
$size_stmt = $conn->prepare($size_sql);
$size_stmt->bind_param("i", $id);
$size_stmt->execute();
$size_result = $size_stmt->get_result();

$sizes = [];
while ($row = $size_result->fetch_assoc()) {
    $sizes[$row['size']] = $row['stock'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']) ?> - OFFT</title>
  <style>
    body {
      background-color: #121212;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 40px 60px;
    }

    h1 {
      font-size: 2.2rem;
      margin-bottom: 0.5rem;
    }

    .product-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      align-items: flex-start;
    }

    .image-gallery {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .image-gallery img {
      width: 300px;
      max-width: 100%;
      border-radius: 10px;
      object-fit: cover;
      border: 1px solid #444;
    }

    .product-info {
      flex: 1;
      max-width: 600px;
    }

    .price {
      font-size: 1.4rem;
      color: #00bfff;
      font-weight: bold;
      margin: 15px 0;
    }

    .desc {
      margin: 20px 0;
      color: #ccc;
      font-size: 1rem;
      line-height: 1.5;
    }

    .size-options {
      display: flex;
      gap: 10px;
      margin: 20px 0;
    }

    .size-options label {
      background: #1e1e1e;
      border: 1px solid #444;
      padding: 10px 15px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.2s ease;
    }

    .size-options input[type="radio"] {
      display: none;
    }

    .size-options input[type="radio"]:checked + span {
      background-color: #00bfff;
      color: white;
    }

    .size-options input[type="radio"]:disabled + span {
      background-color: #333;
      color: #777;
      cursor: not-allowed;
      text-decoration: line-through;
    }

    button {
      padding: 12px 25px;
      font-size: 1rem;
      font-weight: bold;
      background-color: #00bfff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 20px;
    }

    button:hover {
      background-color: #009acd;
    }

    @media (max-width: 768px) {
      body {
        padding: 20px;
      }
      .product-wrapper {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <div class="product-wrapper">
    <!-- LEFT: IMAGES -->
    <div class="image-gallery">
      <?php while ($img = $images_result->fetch_assoc()): ?>
        <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="Product Image">
      <?php endwhile; ?>
    </div>

    <!-- RIGHT: INFO -->
    <div class="product-info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <p class="price">₱<?= number_format($product['price'], 2) ?></p>
      <p class="desc"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

      <form method="POST" action="add_to_cart.php">
        <label style="font-size: 1rem; margin-bottom: 5px;">Select Size:</label>
        <div class="size-options">
          <?php foreach (['S', 'M', 'L', 'XL'] as $size): 
            $stock = $sizes[$size] ?? 0;
            $disabled = $stock <= 0 ? 'disabled' : '';
          ?>
            <label>
              <input type="radio" name="size" value="<?= $size ?>" id="size-<?= $size ?>" <?= $disabled ?>>
              <span><?= $size ?></span>
            </label>
          <?php endforeach; ?>
        </div>

        <input type="hidden" name="product_id" value="<?= $id ?>">
        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </div>

</body>
</html>
