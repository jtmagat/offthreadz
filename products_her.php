<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OFFTHREADZ</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Inter', sans-serif; background-color:#fff; color:#000; }

    /* Transparent Navbar */
    .top-navbar {
      position: sticky;
      top:0;
      z-index:1000;
      display:flex;
      justify-content:space-between;
      align-items:center;
      background-color: rgba(255,255,255,0.95);
      padding: 1rem 2rem;
      border-bottom:1px solid #000;
    }

    .top-navbar a {
      color:#000;
      text-decoration:none;
      margin:0 10px;
      font-weight:500;
    }

    .top-navbar a:hover { color:#555; }
    .nav-center .logo img { height:40px; width:auto; display:block; }
    .nav-left, .nav-right { display:flex; align-items:center; }
    .dropdown { position:relative; }

    .dropdown-menu {
      display:none;
      position:absolute;
      top:130%;
      left:0;
      background:#fff;
      border:1px solid #000;
      border-radius:8px;
      padding:0.5rem 1rem;
      z-index:999;
    }

    .dropdown:hover .dropdown-menu { display:block; }
    .dropdown-menu a { display:block; padding:6px 0; color:#000; white-space:nowrap; }

    /* Overlay container */
    #search-overlay {
      position:fixed;
      top:0;
      right:-100%;
      width:100%;
      height:100%;
      background-color: rgba(255,255,255,0.95);
      display:flex;
      justify-content:center;
      align-items:center;
      transition:right 0.4s ease;
      z-index:2000;
    }

    #search-overlay.active { right:0; }

    .search-form { display:flex; gap:10px; }
    .search-form input {
      padding:12px;
      font-size:1rem;
      border:1px solid #000;
      border-radius:6px;
      width:250px;
      background:#fff;
      color:#000;
    }
    .search-form button {
      padding:12px 18px;
      border:none;
      border-radius:6px;
      background:#000;
      color:#fff;
      font-weight:bold;
      cursor:pointer;
    }

    .hero-banner { width:100%; height:80vh; overflow:hidden; position:relative; }
    .slides { display:flex; height:100%; transition:transform 0.6s ease-in-out; }
    .slide { flex:0 0 100%; display:flex; justify-content:center; align-items:center; background:#fff; }
    .slide img { width:100%; height:100%; object-fit:contain; transform:scale(1.02); }
    .slide img:hover { transform:scale(1.1); }

    .arrow {
      position:absolute;
      top:50%;
      transform:translateY(-50%);
      background: rgba(0,0,0,0.5);
      border:none;
      font-size:2rem;
      color:white;
      cursor:pointer;
      padding:10px;
      z-index:10;
    }
    .arrow.left { left:15px; }
    .arrow.right { right:15px; }

    /* Marquee */
    .marquee-container {
      overflow:hidden;
      white-space:nowrap;
      background:#fff;
      color:#000;
      border-top:1px solid #000;
      border-bottom:1px solid #000;
      padding:12px 0;
    }
    .marquee-track { display:inline-flex; animation:scroll-left 20s linear infinite; }
    .marquee-track span { font-weight:600; font-size:1rem; padding-right:50px; }
    @keyframes scroll-left { from{transform:translateX(0%);} to{transform:translateX(-50%);} }

    /* Product Section */
    .product-section { padding:60px 40px; background:#fff; }
    .product-section h2 { text-align:center; font-size:2rem; margin-bottom:30px; }

    .product-grid {
      display:grid;
      grid-template-columns:repeat(auto-fill, minmax(250px,1fr));
      gap:40px;
    }

    .product-card {
      background:#f9f9f9;
      border:1px solid #000;
      border-radius:12px;
      overflow:hidden;
      box-shadow:0 6px 20px rgba(0,0,0,0.08);
      text-decoration:none;
      color:#000;
      transition:transform 0.2s ease;
      height:380px;
      display:flex;
      flex-direction:column;
      justify-content:flex-start;
    }

    .product-card:hover { transform:translateY(-5px); }
    .product-img-wrap { position:relative; width:100%; height:260px; overflow:hidden; }
    .product-img-wrap img { position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; transition:opacity 0.4s ease; }
    .product-img-wrap .back { opacity:0; }
    .product-card:hover .back { opacity:1; }
    .product-card:hover .front { opacity:0; }

    .product-card h3 { font-size:1rem; font-weight:bold; padding:10px 15px 5px; color:#000; text-align:center; }
    .product-card span { display:block; padding:0 15px 15px; font-weight:400; font-size:0.9rem; color:#000; text-align:center; }

    .product-card a { text-decoration:none !important; color:inherit; }

    .out-of-stock { opacity:0.5; position:relative; }
    .out-of-stock::after {
      content:"OUT OF STOCK";
      position:absolute;
      top:50%;
      left:50%;
      transform:translate(-50%, -50%);
      background-color: rgba(255,0,0,0.8);
      color:white;
      padding:6px 10px;
      border-radius:5px;
      font-weight:bold;
      font-size:0.95rem;
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
    <a href="products.php">For Him</a>
  </div>

  <div class="nav-center">
    <a href="for_her.php" class="logo">
      <img src="assets/offtwhitelogo.png" alt="OFFTHREADZ Logo">
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
      <a href="#" id="search-toggle">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
          <path d="M21 21l-6-6"></path>
          <circle cx="10" cy="10" r="7"></circle>
        </svg>
      </a>
      <div id="search-overlay">
        <form action="search.php" method="GET" class="search-form">
          <input type="text" name="query" placeholder="Search products..." required>
          <button type="submit">Search</button>
        </form>
      </div>

      <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="icon-user" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7.963 7.963 0 0112 15c1.657 0 3.167.504 4.379 1.374M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg></a>

      <a href="cart.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon-cart" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16l-1.5 12h-13L4 6zM9 6v-2h6v2" />
      </svg></a>
    </div>
  </div>
</header>

<!-- Hero Banner -->
<div class="hero-banner">
  <div class="slides">
    <div class="slide"><img src="assets/offtlogowhite.png" alt="Slide 3"></div>
    <div class="slide"><img src="assets/offindexpink.png" alt="Slide 2"></div>
    <div class="slide"><img src="assets/offtindexwhite.png" alt="Slide 1"></div>
  </div>
  <button class="arrow left">‹</button>
  <button class="arrow right">›</button>
</div>

<!-- Marquee -->
<div class="marquee-container">
  <div class="marquee-track">
    <span>ALL EYEZ ON ME • ALL EYEZ ON ME • ALL EYEZ ON ME • ALL EYEZ ON ME • ALL EYEZ ON ME •</span>
    <span>ALL EYEZ ON ME • ALL EYEZ ON ME • ALL EYEZ ON ME • ALL EYEZ ON ME • ALL EYEZ ON ME •</span>
  </div>
</div>

<!-- Product Section -->
<section class="product-section">
  <h2>Latest Drops - For Her</h2>
  <div class="product-grid">
    <?php
      $conn = new mysqli("localhost","root","","offthreadz_db");
      if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

      $limit = 8;
      $page = isset($_GET['page'])? intval($_GET['page']):1;
      $offset = ($page-1)*$limit;

      // ✅ Only female products
      $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM products WHERE gender='Female'";
      $sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

      $result = $conn->query($sql);
      if($result && $result->num_rows>0){
        while($row=$result->fetch_assoc()){
          $id = $row['id'];
          $name = htmlspecialchars($row['name']);
          $price = number_format($row['price'],2);
          $front = !empty($row['front_image'])?$row['front_image']:'assets/default-front.png';
          $back = !empty($row['back_image'])?$row['back_image']:'assets/default-back.png';
          $stock = $row['stock_s']+$row['stock_m']+$row['stock_l']+$row['stock_xl'];
          $out = ($stock<=0);

          echo '<div class="product-card '.($out?'out-of-stock':'').'">';
          echo '<a href="product_details_her.php?id='.$id.'">';
          echo '<div class="product-img-wrap">';
          echo '<img src="'.$front.'" class="front" alt="'.$name.'">';
          echo '<img src="'.$back.'" class="back" alt="Back of '.$name.'">';
          echo '</div>';
          echo '<h3>'.$name.'</h3>';
          echo '<span>₱'.$price.'</span>';
          echo '</a></div>';
        }
      } else { 
        echo "<p style='text-align:center;'>No products found.</p>"; 
      }

      $total_result = $conn->query("SELECT FOUND_ROWS() AS total");
      $total_row = $total_result->fetch_assoc();
      $total_products = $total_row['total'];
      $total_pages = ceil($total_products/$limit);

      if($total_pages>1){
        echo '<div style="text-align:center;margin-top:30px;">';
        for($i=1;$i<=$total_pages;$i++){
          $link='?page='.$i;
          $active = $i==$page?'style="font-weight:bold;"':''; 
          echo "<a href='$link' $active style='margin:0 10px;color:#000;'>$i</a>";
        }
        echo '</div>';
      }

      $conn->close();
    ?>
  </div>
</section>


<footer style="text-align:center;padding:20px;color:#333;border-top:1px solid #000;margin-top:40px;">
  © <?=date("Y")?> OFFTHREADZ. All rights reserved.
</footer>

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

        //search js
        searchOverlay.addEventListener("click", (e) => {
        if (e.target === searchOverlay) {
        searchOverlay.classList.remove("active");
        }
        });

        let currentIndex = 0;
const slides = document.querySelector(".slides");
const totalSlides = document.querySelectorAll(".slide").length;

// show slide
function showSlide(index) {
  currentIndex = (index + totalSlides) % totalSlides;
  slides.style.transform = `translateX(-${currentIndex * 100}%)`;
}

// auto-slide every 5 sec
let autoSlide = setInterval(() => showSlide(currentIndex + 1), 5000);

// manual arrows
document.querySelector(".arrow.left").addEventListener("click", () => {
  showSlide(currentIndex - 1);
  resetAuto();
});
document.querySelector(".arrow.right").addEventListener("click", () => {
  showSlide(currentIndex + 1);
  resetAuto();
});

// pause + reset auto-slide
function resetAuto() {
  clearInterval(autoSlide);
  autoSlide = setInterval(() => showSlide(currentIndex + 1), 5000);
}

// swipe/drag support
let startX = 0;
slides.addEventListener("mousedown", e => startX = e.clientX);
slides.addEventListener("mouseup", e => {
  if (startX - e.clientX > 50) { showSlide(currentIndex + 1); resetAuto(); }
  else if (e.clientX - startX > 50) { showSlide(currentIndex - 1); resetAuto(); }
});
slides.addEventListener("touchstart", e => startX = e.touches[0].clientX);
slides.addEventListener("touchend", e => {
  if (startX - e.changedTouches[0].clientX > 50) { showSlide(currentIndex + 1); resetAuto(); }
  else if (e.changedTouches[0].clientX - startX > 50) { showSlide(currentIndex - 1); resetAuto(); }
});

</script>
</body>
</html>
