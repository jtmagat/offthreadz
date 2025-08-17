<?php
session_start();

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  echo "<h2 style='color: red; text-align:center;'>Access denied. Admins only.</h2>";
  exit;
}

// DB connection
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

// Pagination setup
$limit = 10; // ilang products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total count
$totalResult = $conn->query("SELECT COUNT(*) as total FROM products");
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit);

// GET PRODUCTS with pagination
$products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Products – OFFTHREADZ</title>
  <style>
    body {
      background: #121212;
      color: #fff;
      font-family: Arial, sans-serif;
      padding: 30px;
    }
    h1 {
      margin: 20px 0;
      font-size: 26px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #333;
      text-align: center;
    }
    th {
      background: #222;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 13px;
    }
    tr:hover {
      background: #2a2a2a;
    }
    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #444;
    }
    .back-link {
      display: inline-block;
      margin-bottom: 15px;
      background: #fff;
      color: #000;
      padding: 6px 14px;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
      font-weight: bold;
      transition: 0.3s;
    }
    .back-link:hover {
      background: #ddd;
    }
    .pagination {
      text-align: center;
      margin-top: 20px;
    }
    .pagination a {
      display: inline-block;
      margin: 0 5px;
      padding: 6px 12px;
      background: #2b2b2b;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      transition: 0.3s;
    }
    .pagination a:hover {
      background: #00bfff;
    }
    .pagination .active {
      background: #00bfff;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <a href="dashboard.php" class="back-link">← Back</a> 

  <h1>📦 All Products</h1>

  <table>
    <tr>
      <th>ID</th>
      <th>Image</th>
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
      <th>Stock S</th>
      <th>Stock M</th>
      <th>Stock L</th>
      <th>Stock XL</th>
      <th>Total Stock</th>
    </tr>
    <?php while ($row = $products->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td>
          <?php if (!empty($row['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="product-img">
          <?php else: ?>
            <span style="color:#888;">No Image</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td>₱<?= number_format($row['price'], 2) ?></td>
        <td><?= $row['stock_s'] ?></td>
        <td><?= $row['stock_m'] ?></td>
        <td><?= $row['stock_l'] ?></td>
        <td><?= $row['stock_xl'] ?></td>
        <td><?= $row['stock_s'] + $row['stock_m'] + $row['stock_l'] + $row['stock_xl'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <!-- Pagination -->
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>">« Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
      <a href="?page=<?= $page + 1 ?>">Next »</a>
    <?php endif; ?>
  </div>

</body>
</html>
