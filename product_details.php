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
  background-color: #111;
  color: white;
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
.slider {
  position: relative;
  max-width: 500px;
  flex: 1 1 400px;
  overflow: hidden;
  border-radius: 12px;
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
  background: rgba(0,0,0,0.5);
  color: white;
  border: none;
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
  color: #f1f1f1;
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
  border: none;
  border-radius: 5px;
  background: #f0f0f0;
  color: #000;
  cursor: pointer;
  font-weight: bold;
}
.size-btn.selected {
  background: #007bff;
  color: #fff;
}
.size-btn.disabled {
  background: #555;
  color: #ccc;
  cursor: not-allowed;
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
  background: #f0f0f0;
  color: #000;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.add-btn:disabled {
  background: #777;
  color: #ccc;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .container { flex-direction: column; }
}
</style>

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
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <form method="post" action="add_to_cart.php" id="addCartForm">
      <input type="hidden" name="product_id" value="<?= $product_id ?>">
      <input type="hidden" name="size" id="selectedSize">
      
      <div class="size-buttons">
        <?php foreach ($sizes as $size => $stock): ?>
          <?php
            $isAvailable = $stock > 0;
            $label = $isAvailable ? $size : "$size ❌";
            $btnClass = $isAvailable ? "size-btn" : "size-btn disabled";
          ?>
          <button type="button"
                  class="<?= $btnClass ?>"
                  data-size="<?= $size ?>"
                  data-stock="<?= $stock ?>"
                  <?= $isAvailable ? '' : 'disabled' ?>>
            <?= $label ?>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="notice" id="stockNotice"></div>

      <button type="submit" class="add-btn" id="addBtn" disabled>Add to Cart</button>
    </form>
  </div>
</div>

<script>
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

// Size selection logic
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
      if (stock === 10) {
        stockNotice.textContent = "Limited pieces";
      } else {
        stockNotice.textContent = `Only ${stock} left!`;
      }
    } else {
      stockNotice.textContent = '';
    }
  });
});
</script>

<?php include('includes/footer.php'); ?>
