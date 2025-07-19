<?php include('includes/header.php'); ?>
<!-- Load Inter Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
  body {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background-color: #000;
    margin: 0;
    padding: 0;
    color: #fff;
  }

  .top-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.2rem 2rem;
    background-color: #000;
    position: sticky;
    top: 0;
    z-index: 1000;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  }

  .top-navbar a {
    color: #fff;
    text-decoration: none;
    margin: 0 10px;
    font-weight: 500;
  }

  .top-navbar a:hover {
    color: #aaa;
  }

  .nav-left, .nav-right {
    display: flex;
    align-items: center;
  }

  .nav-center .logo {
    font-size: 1.5rem;
    font-weight: bold;
    letter-spacing: 2px;
    color: #fff;
  }

  .dropdown-menu {
    background: #000;
    border: 1px solid #444;
  }

  .dropdown-menu a {
    color: #fff;
  }

  .hero {
    position: relative;
    height: 80vh;
    overflow: hidden;
  }

  .hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(60%);
  }

  .hero-text {
    position: absolute;
    top: 50%;
    left: 10%;
    transform: translateY(-50%);
    color: white;
  }

  .hero-text h1 {
    font-size: 3rem;
    margin: 0;
  }

  .shop-btn {
    padding: 12px 24px;
    background: white;
    color: black;
    border-radius: 4px;
    font-weight: 600;
    text-decoration: none;
    margin-top: 10px;
    display: inline-block;
  }

  .product-section {
    padding: 40px 60px;
    background-color: #000;
  }

  .product-section h2 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 30px;
    color: white;
  }

  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 40px;
  }

  .product-card {
    background-color: #111;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    text-decoration: none;
    color: white;
    transition: transform 0.2s ease;
    height: 380px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }

  .product-img-wrap {
    position: relative;
    width: 100%;
    height: 260px;
    overflow: hidden;
  }

  .product-img-wrap img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.4s ease;
  }

  .product-img-wrap .back {
    opacity: 0;
  }

  .product-card:hover .back {
    opacity: 1;
  }

  .product-card:hover .front {
    opacity: 0;
  }

  .product-card h3 {
    font-size: 1rem;
    font-weight: 600;
    padding: 10px 15px 5px;
    color: #fff;
    text-align: center;
  }

  .product-card span {
    display: block;
    padding: 0 15px 15px;
    font-weight: 400;
    font-size: 0.85rem;
    color: #fff;
    text-align: center;
  }

  .out-of-stock {
    opacity: 0.5;
    position: relative;
  }

  .out-of-stock::after {
    content: "OUT OF STOCK";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(255, 0, 0, 0.8);
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 0.95rem;
  }
</style>

<header id="navbar" class="top-navbar">
  <div class="nav-left">
    <a href="#">New Arrivals</a>
    <div class="dropdown">
      <a href="#">Shop ▾</a>
      <div class="dropdown-menu">
        <a href="#">All Products</a>
        <a href="#">T-Shirts</a>
        <a href="#">Hoodies</a>
      </div>
    </div>
    <a href="#">For Her</a>
  </div>

  <div class="nav-center">
    <a href="#" class="logo">OFFT</a>
  </div>

  <div class="nav-right">
    <div class="dropdown">
      <a href="#">
        <img src="https://flagcdn.com/ph.svg" alt="PH Flag" style="width: 20px;"> PHP ▾
      </a>
      <div class="dropdown-menu">
        <a href="#">USD</a>
        <a href="#">EUR</a>
      </div>
    </div>
    <a href="#"><i class="icon-user">👤</i></a>
    <a href="#"><i class="icon-cart">🛒</i></a>
  </div>
</header>

<section class="hero">
  <img src="assets/onee.jpg" alt="New Drop" class="hero-img">
  <div class="hero-text">
    <h1>DROP 03 OUT NOW</h1>
    <a href="products.php" class="shop-btn">SHOP NOW</a>
  </div>
</section>

<section class="product-section">
  <h2>Latest Drops</h2>
  <div class="product-grid">
    <?php
    $conn = new mysqli("localhost", "root", "", "offthreadz_db");

    $sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 6";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $product_id = $row["id"];
        $stock_sql = "SELECT SUM(stock) AS total_stock FROM product_sizes WHERE product_id = ?";
        $stmt = $conn->prepare($stock_sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stock_result = $stmt->get_result();
        $stock_data = $stock_result->fetch_assoc();
        $total_stock = $stock_data['total_stock'];
        $is_out = (is_numeric($total_stock) && $total_stock == 0);

        echo '<div class="product-card ' . ($is_out ? 'out-of-stock' : '') . '">';
        echo '<a href="product_details.php?id=' . $product_id . '">';
        echo '<div class="product-img-wrap">';
        echo '<img src="' . $row["front_image"] . '" class="front" alt="' . htmlspecialchars($row["name"]) . '">';
        echo '<img src="' . $row["back_image"] . '" class="back" alt="Back of ' . htmlspecialchars($row["name"]) . '">';
        echo '</div>';
        echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
        echo '<span>₱' . number_format($row["price"], 2) . '</span>';
        echo '</a>';
        echo '</div>';
      }
    } else {
      echo "<p>No products found.</p>";
    }

    $conn->close();
    ?>
  </div>
</section>

<?php include('includes/footer.php'); ?>
