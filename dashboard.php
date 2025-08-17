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

// TOTAL STOCKS (all stocks available now)
$totalStocks = 0;
$res = $conn->query("SELECT SUM(stock_s + stock_m + stock_l + stock_xl) AS total FROM products"); 
if ($res) {
    $row = $res->fetch_assoc();
    $totalStocks = $row['total'] ?? 0;
}

// SALES (from orders table)
$sales = 0;
$res = $conn->query("SELECT SUM(total_price) AS total FROM orders");
if ($res) {
    $row = $res->fetch_assoc();
    $sales = $row['total'] ?? 0;
}

// ORDERS COUNT
$orders = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM orders");
if ($res) {
    $row = $res->fetch_assoc();
    $orders = $row['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard – OFFTHREADZ</title>
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #0f0f0f, #1c1c1c);
      color: #f4f4f4;
      padding: 40px;
    }

    h1 { font-size: 34px; margin-bottom: 30px; }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 25px;
    }

    .card {
      background: linear-gradient(145deg, #1a1a1a, #222);
      border-radius: 18px;
      padding: 30px;
      box-shadow: 0 0 12px rgba(0, 255, 255, 0.07);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
    }

    .card h2 {
      font-size: 15px;
      color: #9a9a9a;
      margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .card .value {
      font-size: 36px;
      font-weight: bold;
      color: #ffffff;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .buttons { display: flex; gap: 12px; }

    .nav-btn {
      background: #ffffff;
      color: #1a1a1a;
      padding: 10px 18px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .nav-btn:hover { background: #e5e5e5; }

    .logout-btn {
      background: #ffffff;
      color: #c40812;
      border: 2px solid #c40812;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease, color 0.3s ease;
    }

    .logout-btn:hover {
      background: #f8d7da;
      color: #a4000d;
    }

    @media (max-width: 600px) {
      .dashboard { grid-template-columns: 1fr; }
      .topbar { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

 <div class="topbar">
  <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>
  <div class="buttons">
    <a href="index.php" class="nav-btn"> Why OFFT?</a>
    <a href="add_product.php" class="nav-btn"> Add Product</a>
    <a href="all_products.php" class="nav-btn"> All Products</a>
    <a href="logout.php" class="logout-btn"> Logout</a>
  </div>
</div>

  <div class="dashboard">
    <div class="card">
      <h2>Total Products</h2>
      <div class="value"><?= $productCount ?></div>
    </div>
    <div class="card">
      <h2>Total Stocks (Current)</h2>
      <div class="value"><?= $totalStocks ?></div>
    </div>
    <div class="card">
      <h2>Total Sales</h2>
      <div class="value">₱<?= number_format($sales, 2) ?></div>
    </div>
    <a href="orders.php" class="card">
      <h2>Orders</h2>
      <div class="value"><?= $orders ?></div>
    </a>
  </div>

</body>
</html>
