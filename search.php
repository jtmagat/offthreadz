<?php
session_start();
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'] ?? '';
$products = [];

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $like = "%" . $query . "%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= count($products) ?> result<?= count($products) !== 1 ? 's' : '' ?> for "<?= htmlspecialchars($query) ?>"</title>
  <style>
    /* RESET */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: "Inter", sans-serif;
      background: #121212;
      color: #fff;
      line-height: 1.6;
      padding-bottom: 50px;
    }

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

    .top-navbar a:hover { color: #ccc; }

    .nav-center .logo img {
      height: 40px;
      width: auto;
      display: block;
    }

    .nav-left, .nav-right {
      display: flex;
      align-items: center;
    }

    .dropdown { position: relative; }
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
    .dropdown:hover .dropdown-menu { display: block; }
    .dropdown-menu a {
      display: block;
      padding: 6px 0;
      color: #fff;
      white-space: nowrap;
    }

    /* SEARCH OVERLAY */
    #search-overlay {
      position: fixed;
      top: 0;
      right: -100%;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.95);
      display: flex;
      justify-content: center;
      align-items: center;
      transition: right 0.4s ease;
      z-index: 2000;
    }
    #search-overlay.active { right: 0; }
    .search-form { display: flex; gap: 10px; }
    .search-form input {
      padding: 12px;
      font-size: 1rem;
      border: none;
      border-radius: 6px;
      width: 250px;
    }
    .search-form button {
      padding: 12px 18px;
      border: none;
      border-radius: 6px;
      background: #fff;
      color: #000;
      font-weight: bold;
      cursor: pointer;
    }

    /* MAIN */
    main {
      padding: 40px;
      max-width: 1200px;
      margin: auto;
    }
    h2 {
      margin-bottom: 20px;
      font-size: 1.5rem;
      border-left: 5px solid #fff;
      padding-left: 10px;
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
      color: #fff;
      transition: transform 0.2s ease;
      height: 420px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }
    .product-card:hover { transform: translateY(-5px); }

    .product-img-wrap {
      position: relative;
      width: 100%;
      height: 300px;
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
    .product-img-wrap .back { opacity: 0; }
    .product-card:hover .back { opacity: 1; }
    .product-card:hover .front { opacity: 0; }

    .product-card h3 {
      font-size: 1rem;
      font-weight: bold;
      padding: 10px 15px 5px;
      text-align: center;
    }
    .product-card span {
      display: block;
      padding: 0 15px 10px;
      font-size: 0.95rem;
      text-align: center;
    }

    .btn {
      display: block;
      background: #fff;
      color: #000;
      padding: 10px 15px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      margin: 0 auto 15px;
      width: fit-content;
      transition: background 0.3s, color 0.3s;
    }
    .btn:hover {
      background: #ccc;
      color: #000;
    }

    .no-results {
      background: #1e1e1e;
      padding: 30px;
      text-align: center;
      border-radius: 10px;
      margin-top: 30px;
      font-size: 1.1rem;
      color: #aaa;
    }
  </style>
</head>
<body>
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
      <a href="index.php" class="logo">
        <img src="assets/offtlogoo.png" alt="OFFTHREADZ Logo">
      </a>
    </div>

    <div class="nav-right">
      <div class="dropdown">
        <a href="#"><img src="https://flagcdn.com/ph.svg" alt="PH Flag" style="width: 20px;"> PHP ▾</a>
        <div class="dropdown-menu">
          <a href="#">USD</a>
          <a href="#">EUR</a>
        </div>
      </div>

      <div class="header-icons">
        <!-- Search Icon -->
        <a href="#" id="search-toggle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
            <path d="M21 21l-6-6"></path>
            <circle cx="10" cy="10" r="7"></circle>
          </svg>
        </a>

        <!-- Hidden Search Overlay -->
        <div id="search-overlay">
          <form action="search.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search products..." required>
            <button type="submit">Search</button>
          </form>
        </div>

        <!-- User -->
        <a href="#">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon-user" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7.963 7.963 0 0112 15c1.657 0 3.167.504 4.379 1.374M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </a>

        <!-- Cart -->
        <a href="cart.php">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon-cart" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16l-1.5 12h-13L4 6zM9 6v-2h6v2" />
          </svg>
        </a>
      </div>
    </div>
  </header>

  <!-- MAIN -->
  <main>
    <h2><?= count($products) ?> result<?= count($products) !== 1 ? 's' : '' ?> for "<?= htmlspecialchars($query) ?>"</h2>

    <?php if (empty($products)): ?>
      <div class="no-results">No products found for "<strong><?= htmlspecialchars($query) ?></strong>".</div>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <div class="product-img-wrap">
              <img src="<?= htmlspecialchars($product['front_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="front">
              <img src="<?= htmlspecialchars($product['back_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?> back" class="back">
            </div>
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <span>₱<?= number_format($product['price'], 2) ?></span>
            <a href="product_details.php?id=<?= $product['id'] ?>" class="btn">View</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <script>
    let lastScrollTop = 0;
    const navbar = document.getElementById("navbar");

    window.addEventListener("scroll", () => {
      let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      navbar.style.transform = scrollTop > lastScrollTop ? "translateY(-100%)" : "translateY(0)";
      lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });

    const searchToggle = document.getElementById("search-toggle");
    const searchOverlay = document.getElementById("search-overlay");

    searchToggle.addEventListener("click", (e) => {
      e.preventDefault();
      searchOverlay.classList.toggle("active");
    });

    searchOverlay.addEventListener("click", (e) => {
      if (e.target === searchOverlay) {
        searchOverlay.classList.remove("active");
      }
    });
  </script>
</body>
</html>
