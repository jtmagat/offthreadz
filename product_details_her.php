<?php
session_start();
include('includes/header.php');
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

if (!isset($_GET['id'])) {
  echo "<p style='color:white;'>No product ID provided.</p>";
  exit;
}

$product_id = intval($_GET['id']);
$product_sql = "SELECT * FROM products WHERE id = $product_id";
$product_result = $conn->query($product_sql);

if (!$product_result || $product_result->num_rows === 0) {
  echo "<p style='color:white;'>Product not found.</p>";
  exit;
}

$product = $product_result->fetch_assoc();

// Get sizes
$sizes_result = $conn->query("SELECT size, stock FROM product_sizes WHERE product_id = $product_id");
$sizes = [];
while ($s = $sizes_result->fetch_assoc()) {
  $sizes[$s['size']] = $s['stock'];
}

$images = [];
if (!empty($product['front_image'])) $images[] = $product['front_image'];
if (!empty($product['back_image'])) $images[] = $product['back_image'];
if (!empty($product['image3'])) $images[] = $product['image3'];
if (!empty($product['image4'])) $images[] = $product['image4'];
?>

<style>
body {
  background-color: #fff;
  color: #000;
  font-family: sans-serif;
}

.container {
  max-width: 1100px;
  margin: auto;
  padding: 40px 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
}

/* Transparent Navbar - White Theme */
.top-navbar {
  position: sticky;
  top: 0;
  z-index: 1000;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.9);
  padding: 1rem 2rem;
  border-bottom: 1px solid #000;
}

.top-navbar a {
  color: #000;
  text-decoration: none;
  margin: 0 10px;
  font-weight: 500;
}

.top-navbar a:hover {
  color: #555;
}

.nav-center .logo img {
  height: 40px;
  width: auto;
  display: block;
}

.nav-left, .nav-right {
  display: flex;
  align-items: center;
}

.dropdown {
  position: relative;
}

.dropdown-menu {
  display: none;
  position: absolute;
  top: 130%;
  left: 0;
  background: #fff;
  border: 1px solid #000;
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
  color: #000;
  white-space: nowrap;
}

/* Overlay Search */
#search-overlay {
  position: fixed;
  top: 0;
  right: -100%;
  width: 100%;
  height: 100%;
  background-color: rgba(255,255,255,0.97);
  display: flex;
  justify-content: center;
  align-items: center;
  transition: right 0.4s ease;
  z-index: 2000;
}

#search-overlay.active {
  right: 0;
}

.search-form {
  display: flex;
  gap: 10px;
}

.search-form input {
  padding: 12px;
  font-size: 1rem;
  border: 1px solid #000;
  border-radius: 6px;
  width: 250px;
}

.search-form button {
  padding: 12px 18px;
  border: 1px solid #000;
  border-radius: 6px;
  background: #000;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
}

.slider {
  position: relative;
  max-width: 500px;
  flex: 1 1 400px;
  overflow: hidden;
  border-radius: 12px;
  border: 1px solid #000;
}

.slider img {
  width: 100%;
  display: none;
  border-radius: 10px;
}

.slider img.active {
  display: block;
}

.slider .arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 2rem;
  background: rgba(255,255,255,0.8);
  color: #000;
  border: 1px solid #000;
  padding: 10px;
  cursor: pointer;
}

.arrow.left { left: 10px; }
.arrow.right { right: 10px; }

.details {
  flex: 1 1 400px;
  max-width: 600px;
}

.details h1 {
  font-size: 30px;
  margin-bottom: 10px;
}

.details .price {
  font-size: 24px;
  color: #111;
  margin: 10px 0;
}

.size-buttons {
  margin: 20px 0;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.size-btn {
  padding: 10px 18px;
  border: 1px solid #000;
  border-radius: 5px;
  background: #fff;
  color: #000;
  cursor: pointer;
  font-weight: bold;
  position: relative;
}

.size-btn.selected {
  background: #000;
  color: #fff;
}

.size-btn.disabled {
  background: #eee;
  color: #999;
  border: 1px solid #aaa;
  cursor: not-allowed;
}

.size-btn.low-stock::after {
  content: 'Low';
  font-size: 10px;
  color: red;
  position: absolute;
  top: -5px;
  right: -5px;
}

.qty-selector {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 15px;
}

.qty-selector button {
  padding: 5px 12px;
  font-size: 16px;
  cursor: pointer;
  border: 1px solid #000;
  background: #fff;
}

.qty-selector input {
  width: 50px;
  text-align: center;
  font-size: 16px;
  border: 1px solid #000;
  padding: 5px;
}

.notice {
  margin-top: 10px;
  font-size: 14px;
  color: orange;
}

.add-btn {
  margin-top: 20px;
  width: 100%;
  padding: 12px;
  font-size: 16px;
  background: #000;
  color: #fff;
  border: 1px solid #000;
  border-radius: 5px;
  cursor: pointer;
}

.add-btn:disabled {
  background: #ccc;
  color: #666;
  border: 1px solid #aaa;
  cursor: not-allowed;
}

.tabs {
  margin-top: 30px;
}

.tabs button {
  background: #fff;
  color: #000;
  border: 1px solid #000;
  padding: 10px 20px;
  margin-right: 5px;
  cursor: pointer;
}

.tabs button.active {
  background: #000;
  color: #fff;
}

.tab-content {
  margin-top: 20px;
  display: none;
}

.tab-content.active {
  display: block;
}

@media (max-width: 768px) {
  .container { flex-direction: column; }
}

.related-products {
  margin-top: 60px;
  padding: 20px 0;
  border-top: 1px solid #000;
}
/* Product Section */
.product-section {
  padding: 60px 40px;
  background-color: #fff;
  border-top: 1px solid #000;
}

.product-section h2 {
  text-align: center;
  font-size: 2rem;
  margin-bottom: 30px;
  color: #000;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 40px;
}

.product-card {
  background-color: #fff;
  border: 1px solid #000;
  border-radius: 12px;
  overflow: hidden;
  text-decoration: none;
  color: #000;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  height: 380px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

.product-card:hover { 
  transform: translateY(-5px); 
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

.product-img-wrap {
  position: relative;
  width: 100%;
  height: 260px;
  overflow: hidden;
  border-bottom: 1px solid #000;
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
  color: #000;
  text-align: center;
}

.product-card span, 
.product-card p {
  display: block;
  padding: 0 15px 15px;
  font-weight: 400;
  font-size: 0.9rem;
  color: #111;
  text-align: center;
}

.product-card a {
  text-decoration: none !important;
  color: inherit;
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
          <a href="products_her.php" class="logo">
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
      </header>

  <div class="container">
    <div class="slider">
    <?php foreach ($images as $i => $img): ?>
      <img src="<?= $img ?>" class="<?= $i === 0 ? 'active' : '' ?>">
    <?php endforeach; ?>
    <button class="arrow left" onclick="slide(-1)">‹</button>
    <button class="arrow right" onclick="slide(1)">›</button>
  </div>

  <div class="details">
   <h1><?= htmlspecialchars($product['name']) ?></h1>
  <div class="price">₱<?= number_format($product['price'], 2) ?></div>



    <form method="post" action="add_to_cart.php">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <input type="hidden" name="size" id="selectedSize">
    <input type="hidden" name="qty" id="selectedQty" value="1">
      
      <div class="size-buttons">
        <?php foreach ($sizes as $size => $stock): ?>
          <?php
            $isAvailable = $stock > 0;
            $btnClass = "size-btn" . (!$isAvailable ? " disabled" : "") . ($stock > 0 && $stock < 10 ? " low-stock" : "");
            $label = $isAvailable ? $size : "$size ❌";
          ?>
          <button type="button"
                  class="<?= $btnClass ?>"
                  data-size="<?= $size ?>"
                  data-stock="<?= $stock ?>"
                  <?= !$isAvailable ? 'disabled' : '' ?>>
            <?= $label ?>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="notice" id="stockNotice"></div>

      <!-- Quantity Selector -->
      <div class="qty-selector">
        <button type="button" onclick="adjustQty(-1)">−</button>
        <input type="text" id="qtyInput" value="1" readonly>
        <button type="button" onclick="adjustQty(1)">+</button>
      </div>

      <button type="submit" class="add-btn" id="addBtn" disabled>Add to Cart</button>
    </form>

    <!-- Tabs -->
    <div class="tabs">
      <button onclick="showTab('desc')" class="tab-btn active">Description</button>
      <button onclick="showTab('sizing')" class="tab-btn">Sizing</button>
      <button onclick="showTab('shipping')" class="tab-btn">Shipping</button>
      <button onclick="showTab('returns')" class="tab-btn">Returns</button>
    </div>

    <div id="desc" class="tab-content active"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
    <div id="sizing" class="tab-content">Use our sizing chart to find your fit. (Insert chart/image here.)</div>
    <div id="shipping" class="tab-content">Ships nationwide via J&T Express. Delivery in 2-5 business days.</div>
    <div id="returns" class="tab-content">Returns accepted within 7 days for unused items with tags.</div>
  </div>
</div>

<!-- Related Products -->
<div class="related-products">
  <h2 style="margin-bottom:20px;">You May Also Like</h2>
  <div class="product-grid">
    <?php
      // Get current product's gender
      $gender_sql = "SELECT gender FROM products WHERE id = $product_id";
      $gender_result = $conn->query($gender_sql);
      $gender_row = $gender_result->fetch_assoc();
      $current_gender = $gender_row['gender']; // Male or Female

      // Get related products with the same gender
      $related_sql = "SELECT * FROM products 
                      WHERE id != $product_id 
                      AND gender = '$current_gender'
                      LIMIT 4";
      $related_result = $conn->query($related_sql);

      while ($row = $related_result->fetch_assoc()):
    ?>
      <div class="product-card">
        <a href="product<?php echo strtolower($current_gender) == 'female' ? '_her' : ''; ?>.php?id=<?= $row['id'] ?>">
          <img src="<?= htmlspecialchars($row['front_image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
          <h3><?= htmlspecialchars($row['name']) ?></h3>
          <p>₱<?= number_format($row['price'], 2) ?></p>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</div>



<script>
// Image slider
let currentSlide = 0;
const slides = document.querySelectorAll('.slider img');
function showSlide(index) {
  slides[currentSlide].classList.remove('active');
  currentSlide = (index + slides.length) % slides.length;
  slides[currentSlide].classList.add('active');
}
function slide(direction) {
  showSlide(currentSlide + direction);
}

// Size selection
const sizeButtons = document.querySelectorAll('.size-btn:not(.disabled)');
const selectedSizeInput = document.getElementById('selectedSize');
const addBtn = document.getElementById('addBtn');
const stockNotice = document.getElementById('stockNotice');

sizeButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    sizeButtons.forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedSizeInput.value = btn.dataset.size;
    addBtn.disabled = false;

    const stock = parseInt(btn.dataset.stock);
    if (stock < 10) {
      stockNotice.textContent = stock === 10 ? "Limited pieces" : `Only ${stock} left!`;
    } else {
      stockNotice.textContent = '';
    }
  });
});

// Quantity control
function adjustQty(change) {
  const input = document.getElementById('qtyInput');
  const hidden = document.getElementById('selectedQty');
  let qty = parseInt(input.value);
  qty = Math.max(1, qty + change);
  input.value = qty;
  hidden.value = qty;
}

// Tabs
function showTab(tabId) {
  document.querySelectorAll('.tab-content').forEach(tab => {
    tab.classList.remove('active');
  });
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  document.getElementById(tabId).classList.add('active');
  event.target.classList.add('active');
}

</script> 

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

