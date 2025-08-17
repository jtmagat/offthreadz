<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "offthreadz_db");

// PAGINATION
$limit = 5; // ilan per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total orders
$res = $conn->query("SELECT COUNT(*) AS total FROM orders");
$totalOrders = $res->fetch_assoc()['total'];
$totalPages = ceil($totalOrders / $limit);

// Get orders with items
$sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT $limit OFFSET $offset";
$orders = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orders – OFFTHREADZ</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }
    h1 { margin-bottom: 20px; }
    .order-box {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .order-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-weight: bold;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
    }
    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }
    th {
      background: #333;
      color: #fff;
    }
    img {
      width: 60px; height: 60px; object-fit: cover;
      border-radius: 6px; border:1px solid #ccc;
    }
    .pagination {
      margin-top: 20px;
      text-align: center;
    }
    .pagination a {
      margin: 0 5px;
      padding: 8px 14px;
      background: #222;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .pagination a.active {
      background: #00b894;
    }
    .back-btn {
      display:inline-block;
      margin-bottom:20px;
      text-decoration:none;
      background:#222;
      color:#fff;
      padding:10px 16px;
      border-radius:8px;
      font-weight:600;
    }
  </style>
</head>
<body>
  <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
  <h1>Orders</h1>

  <?php while($o = $orders->fetch_assoc()): ?>
    <div class="order-box">
      <div class="order-header">
        <span>Order #<?= $o['id'] ?> - <?= htmlspecialchars($o['customer_name']) ?></span>
        <span><?= $o['order_date'] ?></span>
      </div>
      <p>Email: <?= htmlspecialchars($o['email']) ?> | Phone: <?= htmlspecialchars($o['phone']) ?></p>
      <p>Address: <?= htmlspecialchars($o['address']) ?></p>
      <p><strong>Total:</strong> ₱<?= number_format($o['total_price'],2) ?></p>

      <table>
        <tr>
          <th>Image</th>
          <th>Product</th>
          <th>Size</th>
          <th>Qty</th>
          <th>Price</th>
        </tr>
        <?php
        $items = $conn->query("SELECT * FROM order_items WHERE order_id=".$o['id']);
        while($it = $items->fetch_assoc()):
        ?>
        <tr>
          <td><img src="<?= $it['image'] ? htmlspecialchars($it['image']) : 'default.jpg' ?>"></td>
          <td><?= htmlspecialchars($it['product_name']) ?></td>
          <td><?= htmlspecialchars($it['size']) ?></td>
          <td><?= $it['qty'] ?></td>
          <td>₱<?= number_format($it['price'],2) ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  <?php endwhile; ?>

  <!-- PAGINATION -->
  <div class="pagination">
    <?php for($i=1;$i<=$totalPages;$i++): ?>
      <a href="?page=<?= $i ?>" class="<?= ($i==$page)?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
</body>
</html>
