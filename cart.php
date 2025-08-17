<?php
session_start();
$conn = new mysqli("localhost","root","","offthreadz_db");
if ($conn->connect_error) die("DB connection failed");

if (isset($_POST['update_cart'])) {
  foreach ($_POST['qty'] as $key => $q) {
    $_SESSION['cart'][$key]['qty'] = max(1, intval($q));
  }
}
if (isset($_GET['remove'])) {
  unset($_SESSION['cart'][$_GET['remove']]);
}

$items = $_SESSION['cart'] ?? [];
$total = array_reduce($items, fn($sum, $i) => $sum + ($i['price'] * $i['qty']), 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><title>Your Cart</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
    body {
      font-family: 'Inter', sans-serif;
      background: #f9f9f9;
      color: #222;
      margin: 0;
      padding: 2rem;
    }
    h2 { text-align: center; margin-bottom: 2rem; font-weight: 600; font-size: 2rem; }
    .cart-table {
      width: 95%;
      margin: auto;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      border-radius: 12px;
      overflow: hidden;
    }
    .cart-table th, .cart-table td {
      padding: 1.5rem;
      text-align: left;
      border-bottom: 1px solid #eee;
      vertical-align: middle;
      font-size: 1rem;
    }
    .cart-table th {
      background: #fafafa;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }
    .product-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .product-info img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #ddd;
    }
    .qty-input {
      width: 70px;
      padding: 8px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .btn {
      padding: 10px 18px;
      background: #222;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background .2s;
      text-decoration: none;
    }
    .btn:hover { background: #444; }
    .remove-btn { background: #e00; }
    .remove-btn:hover { background: #c00; }
    .total-row td { font-size: 1.2rem; font-weight: 600; }
    .empty {
      text-align: center;
      opacity: 0.6;
      font-size: 1.1rem;
    }
    @media (max-width: 700px) {
      .product-info { flex-direction: column; align-items: flex-start; }
      .product-info img { width: 100%; max-width: 150px; }
    }
  </style>
</head>
<body>

<h2>Your Cart</h2>

<?php if (!$items): ?>
  <p class="empty">Your cart is empty.</p>
<?php else: ?>
<form method="post">
  <table class="cart-table">
    <tr>
      <th>Product</th><th>Size</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Remove</th>
    </tr>
    <?php foreach ($items as $key => $it): ?>
      <?php $subtotal = $it['price'] * $it['qty']; ?>
      <tr>
        <td data-label="Product">
          <div class="product-info">s
          <img src="<?= isset($it['image']) ? htmlspecialchars($it['image']) : 'default.jpg' ?>" 
             alt="<?= htmlspecialchars($it['name']) ?>">
            <span><?= htmlspecialchars($it['name']) ?></span>
          </div>
        </td>
        <td data-label="Size"><?= htmlspecialchars($it['size']) ?></td>
        <td data-label="Price">₱<?= number_format($it['price'],2) ?></td>
        <td data-label="Qty"><input class="qty-input" type="number" name="qty[<?=$key?>]" value="<?=$it['qty']?>" min="1"></td>
        <td data-label="Subtotal">₱<?= number_format($subtotal,2) ?></td>
        <td data-label="Remove"><a class="btn remove-btn" href="cart.php?remove=<?=$key?>">X</a></td>
      </tr>
    <?php endforeach; ?>
    <tr class="total-row">
      <td colspan="4" align="right">Total:</td>
      <td colspan="2">₱<?= number_format($total,2) ?></td>
    </tr>
  </table>
  <div style="text-align: center; margin-top: 1.5rem;">
    <a class="btn" href="checkout.php">Checkout</a>
    <a class="btn" href="products.php">Continue Shopping</a>
  </div>
</form>
<?php endif; ?>

</body>
</html>
