<?php
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $desc = $_POST["description"];
  $price = $_POST["price"];

  $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
  $stmt->bind_param("ssdi", $name, $desc, $price, $id);
  $stmt->execute();

  header("Location: products.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Product</title>
  <style>
    body { background: #121212; color: #fff; font-family: Arial; padding: 40px; }
    .container { max-width: 500px; margin: auto; background: #1e1e1e; padding: 30px; border-radius: 10px; }
    input, textarea, button {
      width: 100%; padding: 10px; margin: 10px 0;
      background: #2a2a2a; color: #fff; border: 1px solid #444; border-radius: 5px;
    }
    button {
      background: #00bfff; border: none; font-weight: bold; cursor: pointer;
    }
    button:hover {
      background: #009acd;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Product</h2>
    <form method="POST">
      <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
      <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
      <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" required>
      <button type="submit">Save Changes</button>
    </form>
  </div>
</body>
</html>
    