<?php
session_start();

// Fake login for testing (REMOVE sa production)
if (!isset($_SESSION['role'])) {
  $_SESSION['role'] = 'admin'; // palitan mo sa totoong login system
}

// Check if admin
if ($_SESSION['role'] !== 'admin') {
  echo "<h2 style='color: red; text-align:center;'>Access denied. Admins only.</h2>";
  exit;
}

// DB connection
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

// ====================== DELETE ======================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: upload.php");
    exit;
}

// ====================== FETCH FOR EDIT ======================
$editData = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $editData = $result->fetch_assoc();
}

// ====================== SAVE / UPDATE ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $desc  = $_POST['description'];
    $price = $_POST['price'];

    if (!empty($_POST['id'])) {
        // update
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
        $stmt->bind_param("ssdi", $name, $desc, $price, $id);
        $stmt->execute();
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $desc, $price);
        $stmt->execute();
    }

    header("Location: upload.php");
    exit;
}

// ====================== GET PRODUCTS ======================
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload / Manage Products</title>
  <style>
    body {
      background: #121212;
      color: #fff;
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    form {
      background: #1e1e1e;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 40px;
    }
    input, textarea, button {
      width: 100%;
      margin: 8px 0;
      padding: 10px;
      border-radius: 6px;
      border: none;
    }
    input, textarea {
      background: #2b2b2b;
      color: #fff;
    }
    button {
      background: #00bfff;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #1e1e1e;
    }
    th, td {
      padding: 12px;
      border: 1px solid #444;
      text-align: left;
    }
    th {
      background: #222;
    }
    .btn {
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: bold;
      text-decoration: none;
      margin-right: 6px;
      display: inline-block;
    }
    .edit {
      background: #00bfff;
      color: #fff;
    }
    .delete {
      background: #e63946;
      color: #fff;
    }
    .delete:hover { background: #c53030; }
  </style>
</head>
<body>

  <h2><?= $editData ? "✏️ Edit Product" : "➕ Add Product" ?></h2>

  <form method="post">
    <?php if ($editData): ?>
      <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <?php endif; ?>
    <input type="text" name="name" placeholder="Product Name" 
           value="<?= $editData['name'] ?? '' ?>" required>
    <textarea name="description" placeholder="Description"><?= $editData['description'] ?? '' ?></textarea>
    <input type="number" step="0.01" name="price" placeholder="Price" 
           value="<?= $editData['price'] ?? '' ?>" required>
    <button type="submit"><?= $editData ? "Update Product" : "Add Product" ?></button>
  </form>

  <h2>🛒 Product List</h2>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $products->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td>₱<?= number_format($row['price'], 2) ?></td>
        <td>
          <a class="btn edit" href="upload.php?id=<?= $row['id'] ?>">Edit</a>
          <a class="btn delete" href="upload.php?delete=<?= $row['id'] ?>" 
             onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

</body>
</html>
