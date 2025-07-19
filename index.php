<?php include('includes/header.php'); ?>
<!-- Load Inter Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<!-- Load Inter Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Load Inter Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style> 
  body {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
  }

  .top-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.2rem 2rem;
    background-color: white;
    position: sticky;
    top: 0;
    z-index: 1000;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  }

  .top-navbar a {
    color: #000;
    text-decoration: none;
    margin: 0 10px;
    font-weight: 500;
  }

  .top-navbar a:hover {
    color: #6e6b6bff;
  }

  .nav-left, .nav-right {
    display: flex;
    align-items: center;
  }

  .nav-center .logo {
    font-size: 1.5rem;
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
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    z-index: 999;
  }

  .dropdown:hover .dropdown-menu {
    display: block;
  }

  .dropdown-menu a {
    display: block;
    padding: 5px 0;
    white-space: nowrap;
  }

  .icon-search, .icon-user, .icon-cart {
    font-size: 1.1rem;
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
    background-color: white;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    display: none;
    flex-direction: row;
    gap: 8px;
    z-index: 1000;
  }

  #search-filter-form input,
  #search-filter-form select,
  #search-filter-form button {
    padding: 8px 10px;
    font-size: 0.9rem;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  #search-filter-form button {
    background-color: #111;
    color: white;
    border: none;
    cursor: pointer;
  }

  .product-section {
    padding: 40px 60px;
    background-color: #fff;
  }

  .product-section h2 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 30px;
    color: #111;
  }

  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* wider cards */
    gap: 40px;
  }

.product-card {
  background-color: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  text-decoration: none;
  color: inherit;
  transition: transform 0.2s ease;
  height: 380px; /* ⬅️ shorter card */
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
  height: 260px; /* ⬅️ shorter image height */
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

  /* ✅ Product name - bold and black */
  .product-card h3 {
    font-size: 1rem;
    font-weight: 600;
    padding: 10px 15px 5px;
    color: #000;
    text-align: center;
    text-decoration: none;
  }

  /* ✅ Product price - normal weight, smaller font, black */
  .product-card span {
    display: block;
    padding: 0 15px 15px;
    font-weight: 400;
    font-size: 0.85rem;
    color: #000;
    text-align: center;
    text-decoration: none;
  }

  .product-card a {
    text-decoration: none;
  }

  .btn-view {
    display: none;
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

  .hero {
  position: relative;
  height: 80vh;
  overflow: hidden;
}

.hero-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(70%);
}

.hero-text {
  position: absolute;
  top: 50%;
  left: 10%;
  transform: translateY(-50%);
  color: white;
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
      <a href="#">
        <img src="https://flagcdn.com/ph.svg" alt="PH Flag" style="width: 20px;"> PHP ▾
      </a>
      <div class="dropdown-menu">
        <a href="#">USD</a>
        <a href="#">EUR</a>
      </div>
    </div>
    <div class="search-wrapper">
      <a href="#" id="search-toggle">
        <i class="icon-search">🔍</i>
      </a>

      <form method="GET" class="filter-bar" id="search-filter-form" style="display: none;">
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
  const navbar = document.getElementById("navbar");s

  window.addEventListener("scroll", () => {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop) {
      navbar.style.transform = "translateY(-100%)";
    } else {
      navbar.style.transform = "translateY(0)";
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });

  document.getElementById('search-toggle').addEventListener('click', function(e) {
    e.preventDefault();
    const form = document.getElementById('search-filter-form');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'flex' : 'none';
  });
</script>
