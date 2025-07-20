<?php include('includes/header.php'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Inter', sans-serif;
    background-color: #000;
    color: #fff;
  }

  /* Transparent Navbar */
  .top-navbar {
    position: sticky;
    top: 0;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.85);
    padding: 1rem 2rem;
    border-bottom: 1px solid #222;
  }

  .top-navbar a {
    color: #fff;
    text-decoration: none;
    margin: 0 10px;
    font-weight: 500;
  }

  .top-navbar a:hover {
    color: #ccc;
  }

  .nav-left, .nav-right {
    display: flex;
    align-items: center;
  }

  .nav-center .logo {
    font-size: 1.6rem;
    font-weight: bold;
    letter-spacing: 2px;
  }

  .dropdown {
    position: relative;
  }

  .dropdown-menu {
    display: none;
    position: absolute;
    top: 130%;
    left: 0;
    background: #111;
    border: 1px solid #444;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    z-index: 999;
  }

  .dropdown:hover .dropdown-menu {
    display: block;
  }

  .dropdown-menu a {
    display: block;
    padding: 6px 0;
    color: #fff;
    white-space: nowrap;
  }

  .icon-search, .icon-user, .icon-cart {
    font-size: 1rem;
    margin: 0 8px;
    cursor: pointer;
  }

  .search-wrapper {
    position: relative;
    display: inline-block;
  }

  #search-filter-form {
    position: absolute;
    top: 40px;
    right: 0;
    background-color: #111;
    padding: 10px;
    border: 1px solid #444;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(255,255,255,0.05);
    display: none;
    flex-direction: row;
    gap: 8px;
    z-index: 1000;
  }

  #search-filter-form input,
  #search-filter-form select {
    padding: 8px 10px;
    font-size: 0.9rem;
    border: 1px solid #555;
    border-radius: 5px;
    background-color: #000;
    color: #fff;
  }

  #search-filter-form button {
    background-color: #fff;
    color: #000;
    border: none;
    cursor: pointer;
    padding: 8px 12px;
    font-weight: bold;
  }

  /* Hero Banner */
  .hero-banner {
    width: 100%;
    height: 80vh;
    background: url('assets/banner1.png') center center / cover no-repeat;
    position: relative;
  }

  /* Marquee */
   .marquee-container {
    overflow: hidden;
    white-space: nowrap;
    background-color: #000;
    color: #fff;
    border-top: 1px solid #fff;
    border-bottom: 1px solid #fff;
    padding: 12px 0;
  }

  .marquee-track {
    display: inline-flex;
    animation: scroll-left 20s linear infinite;
  }

  .marquee-track span {
    font-weight: 600;
    font-size: 1rem;
    padding-right: 50px;
  }

  @keyframes scroll-left {
    from {
      transform: translateX(0%);
    }
    to {
      transform: translateX(-50%);
    }
  }

  /* Product Section */
  .product-section {
    padding: 60px 40px;
    background-color: #000;
  }

  .product-section h2 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 30px;
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
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.08);
    text-decoration: none;
    color: #fff;
    transition: transform 0.2s ease;
    height: 380px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }

  .product-card:hover {
    transform: translateY(-5px);
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
  font-weight: bold;
  padding: 10px 15px 5px;
  color: #fff; 
  text-align: center;
  text-decoration: none; 
}

.product-card span {
  display: block;
  padding: 0 15px 15px;
  font-weight: 400;
  font-size: 0.9rem;
  color: #fff;
  text-align: center;
  text-decoration: none;
}
.product-card a {
  text-decoration: none !important;
  color: inherit;
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

<!-- Navbar -->
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
    <div class="dropdown">
      <a href="#">Collections ▾</a>
      <div class="dropdown-menu">
        <a href="#">Drop 1</a>
        <a href="#">Drop 2</a>
      </div>
    </div>
    <a href="#">For Her</a>
  </div>

  <div class="nav-center">
    <a href="#" class="logo">OFFT</a>
  </div>

  <div class="nav-right">
    <div class="dropdown">
      <a href="#"><img src="https://flagcdn.com/ph.svg" alt="PH Flag" style="width: 20px;"> PHP ▾</a>
      <div class="dropdown-menu">
        <a href="#">USD</a>
        <a href="#">EUR</a>
      </div>
    </div>

    <div class="search-wrapper">
      <a href="#" id="search-toggle">
        <i class="icon-search">🔍</i>
      </a>

      <form method="GET" class="filter-bar" id="search-filter-form">
        <input type="text" name="search" placeholder="Search..." value="<?= $_GET['search'] ?? '' ?>" />
        <select name="category">
          <option value="">All Categories</option>
          <?php
            $conn = new mysqli("localhost", "root", "", "offthreadz_db");
            $cat_result = $conn->query("SELECT * FROM categories");
            while ($cat = $cat_result->fetch_assoc()) {
              $selected = ($_GET['category'] ?? '') == $cat['id'] ? 'selected' : '';
              echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
            }
          ?>
        </select>
        <select name="sort">
          <option value="">Sort</option>
          <option value="newest" <?= ($_GET['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Newest</option>
          <option value="low" <?= ($_GET['sort'] ?? '') == 'low' ? 'selected' : '' ?>>Price: Low to High</option>
          <option value="high" <?= ($_GET['sort'] ?? '') == 'high' ? 'selected' : '' ?>>Price: High to Low</option>
        </select>
        <button type="submit">Apply</button>
      </form>
    </div>

    <a href="#"><i class="icon-user">👤</i></a>
    <a href="#"><i class="icon-cart">🛒</i></a>
  </div>
</header>

<!-- Hero -->
<div class="hero-banner"></div>

<!-- Looping Marquee -->
<div class="marquee-container">
  <div class="marquee-track">
    <span>OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •    </span>
    <span>OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •  OFFTHREADZ  •    </span>
  </div>
</div>

<!-- Products -->
<section class="product-section">
  <h2>Latest Drops</h2>
  <div class="product-grid">
    <?php
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    $sort = $_GET['sort'] ?? '';

    $sql = "SELECT * FROM products WHERE 1";

    if ($search !== '') {
      $search_safe = $conn->real_escape_string($search);
      $sql .= " AND (name LIKE '%$search_safe%' OR description LIKE '%$search_safe%')";
    }

    if ($category !== '') {
      $sql .= " AND category_id = " . intval($category);
    }

    switch ($sort) {
      case 'low': $sql .= " ORDER BY price ASC"; break;
      case 'high': $sql .= " ORDER BY price DESC"; break;
      default: $sql .= " ORDER BY created_at DESC";
    }

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
        echo '<h3 class="product-name">' . htmlspecialchars($row["name"]) . '</h3>';
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

<script>
  let lastScrollTop = 0;
  const navbar = document.getElementById("navbar");

  window.addEventListener("scroll", () => {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    navbar.style.transform = scrollTop > lastScrollTop ? "translateY(-100%)" : "translateY(0)";
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });

  document.getElementById('search-toggle').addEventListener('click', function(e) {
    e.preventDefault();
    const form = document.getElementById('search-filter-form');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'flex' : 'none';
  });
</script>
