<?php
session_start();

// Fake login for testing (REMOVE sa production)
if (!isset($_SESSION['role'])) {
  $_SESSION['role'] = 'user'; // Palitan to 'admin' for testing
}

// Check if admin
if ($_SESSION['role'] !== 'admin') {
  echo "<h2 style='color: red; text-align:center;'>Access denied. Admins only.</h2>";
  exit;
}

$conn = new mysqli("localhost", "root", "", "offthreadz_db");
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Products</title>
  <style>
    body {
      background: #121212;
      color: #fff;
      font-family: Arial, sans-serif;
      padding: 40px;
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
      padding: 8px 14px;
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
    .edit:hover {
      background: #009acd;
    }
    .delete:hover {
      background: #c53030;
    }
    a.btn {
      cursor: pointer;
    }
    .topbar {
      margin-bottom: 20px;
    }
    .topbar a {
      color: #00bfff;
      text-decoration: none;
      font-weight: bold;
    }
    .topbar a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="topbar">
    <h2>🛒 Product List</h2>
    <a href="add_product.php">+ Add New Product</a>
  </div>

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
          <a class="btn edit" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
          <a class="btn delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>

  </table>

</body>
</html>
