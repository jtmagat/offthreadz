<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "offthreadz_db");

// PRODUCT COUNT
$productCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM products");
if ($res) {
    $row = $res->fetch_assoc();
    $productCount = $row['total'] ?? 0;
}

// STOCK COUNT (Assuming 'quantity' column exists in products)
$stockCount = 0;
$res = $conn->query("SELECT SUM(quantity) AS total FROM products");
if ($res) {
    $row = $res->fetch_assoc();
    $stockCount = $row['total'] ?? 0;
}

// SALES (Assuming 'sales' table exists with 'amount' column)
$sales = 0;
$res = $conn->query("SELECT SUM(amount) AS total FROM sales");
if ($res) {
    $row = $res->fetch_assoc();
    $sales = $row['total'] ?? 0;
}

// ORDERS (Assuming 'orders' table exists)
$orders = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM orders");
if ($res) {
    $row = $res->fetch_assoc();
    $orders = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard – OFFTHREADZ</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #0a0a0a;
      color: white;
      padding: 40px;
    }

    h1 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: #1a1a1a;
      border-radius: 18px;
      padding: 30px;
      box-shadow: 0 0 12px rgba(255, 255, 255, 0.03);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 20px rgba(255, 0, 0, 0.2);
    }

    .card h2 {
      font-size: 18px;
      color: #aaa;
      margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .card .value {
      font-size: 36px;
      font-weight: bold;
      color: #fff;
    }

    .logout-btn {
      background: #e50914;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      position: absolute;
      top: 20px;
      right: 30px;
      font-weight: bold;
    }

    .logout-btn:hover {
      background: #b00610;
    }
  </style>
</head>
<body>

  <a href="logout.php" class="logout-btn">Logout</a>
  <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>

  <div class="dashboard">
    <div class="card">
      <h2>Total Products</h2>
      <div class="value"><?= $productCount ?></div>
    </div>
    <div class="card">
      <h2>Total Stocks</h2>
      <div class="value"><?= $stockCount ?></div>
    </div>
    <div class="card">
      <h2>Total Sales</h2>
      <div class="value">₱<?= number_format($sales, 2) ?></div>
    </div>
    <div class="card">
      <h2>Orders</h2>
      <div class="value"><?= $orders ?></div>
    </div>
  </div>

</body>
</html>
