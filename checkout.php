<?php
session_start();
$conn = new mysqli("localhost","root","","offthreadz_db");
if ($conn->connect_error) die("DB connection failed");

$items = $_SESSION['cart'] ?? [];
$total = array_reduce($items, fn($sum, $i) => $sum + ($i['price'] * $i['qty']), 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $barangay = $conn->real_escape_string($_POST['barangay']);
    $postal = $conn->real_escape_string($_POST['postal']);
    $city = $conn->real_escape_string($_POST['city']);
    $region = $conn->real_escape_string($_POST['region']);
    $fulladdress = $conn->real_escape_string($_POST['fulladdress']);
    $payment = $conn->real_escape_string($_POST['payment']);

    $address = "$fulladdress, Brgy. $barangay, $city, $region, $postal";

    if ($items && $name && $email && $phone && $barangay && $postal && $city && $region && $fulladdress && $payment) {
        // Insert order
        $conn->query("INSERT INTO orders (customer_name, email, phone, address, total_price, order_date) 
                      VALUES ('$name','$email','$phone','$address','$total',NOW())");
        $orderId = $conn->insert_id;

        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, image, size, price, qty) 
                                VALUES (?,?,?,?,?,?)");

        foreach ($items as $it) {
            $stmt->bind_param(
                "isssdi", 
                $orderId, 
                $it['name'], 
                $it['image'], 
                $it['size'], 
                $it['price'], 
                $it['qty']
            );
            $stmt->execute();
        }
        $stmt->close();

        unset($_SESSION['cart']);
        header("Location: thankyou.php?order_id=$orderId");
        exit;
    } else {
        $error = "Please complete all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 2rem;
      color: #222;
    }
    h2 { text-align:center; margin-bottom:1.5rem; }
    .checkout-container {
      max-width: 900px;
      margin: auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }
    .form-box, .summary-box {
      background: #fff;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    label { display:block; margin: .75rem 0 .25rem; font-weight:600; }
    input, textarea, select {
      width:100%;
      padding:10px;
      border:1px solid #ccc;
      border-radius:6px;
      font-size:1rem;
    }
    textarea { resize: vertical; min-height:80px; }
    .btn {
      background:#222; color:#fff; padding:12px 20px;
      border:none; border-radius:6px; cursor:pointer;
      font-weight:600; font-size:1rem; margin-top:1rem;
      width:100%;
    }
    .btn:hover { background:#444; }
    .summary-item {
      display:flex; justify-content:space-between; margin-bottom:.5rem; align-items:center;
    }
    .summary-total {
      font-weight:700; font-size:1.2rem; border-top:1px solid #ddd; padding-top:.5rem;
    }
    .error {color:red; text-align:center; margin-bottom:1rem;}
    .important {color:#e00; font-size:.9rem; margin-top:.25rem;}
  </style>
</head>
<body>

<h2>Checkout</h2>

<?php if (!$items): ?>
  <p style="text-align:center;">Your cart is empty. <a href="products.php">Shop now</a>.</p>
<?php else: ?>
<div class="checkout-container">
  <div class="form-box">
    <h3>Billing & Shipping</h3>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
      <label>Full Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Phone</label>
      <input type="text" name="phone" required>

      <label>Full Address</label>
      <textarea name="fulladdress" placeholder="Block and Lot #" required></textarea>
      <p class="important">IMPORTANT: Please input FULL Address (Block and Lot#)</p>

      <label>Barangay</label>
      <input type="text" name="barangay" required>

      <label>Postal Code</label>
      <input type="text" name="postal" required>

      <label>City</label>
      <input type="text" name="city" required>

      <label>Region</label>
      <select name="region" required>
        <option value="">-- Select Region --</option>
        <option>Region I - Ilocos Region</option>
        <option>Region II - Cagayan Valley</option>
        <option>Region III - Central Luzon</option>
        <option>Region IV-A - CALABARZON</option>
        <option>Region IV-B - MIMAROPA</option>
        <option>Region V - Bicol Region</option>
        <option>Region VI - Western Visayas</option>
        <option>Region VII - Central Visayas</option>
        <option>Region VIII - Eastern Visayas</option>
        <option>Region IX - Zamboanga Peninsula</option>
        <option>Region X - Northern Mindanao</option>
        <option>Region XI - Davao Region</option>
        <option>Region XII - SOCCSKSARGEN</option>
        <option>Region XIII - Caraga</option>
        <option>NCR - National Capital Region</option>
        <option>CAR - Cordillera Administrative Region</option>
        <option>BARMM - Bangsamoro Autonomous Region</option>
      </select>

      <label>Mode of Payment</label>
      <select name="payment" required>
        <option value="">-- Select Payment --</option>
        <option>Cash on Delivery</option>
        <option>GCash</option>
        <option>Bank Transfer</option>
      </select>

      <button type="submit" name="place_order" class="btn">Place Order</button>
    </form>
  </div>

   <div class="summary-box">
    <h3>Order Summary</h3>
    <?php foreach ($items as $it): ?>
      <div class="summary-item">
        <img src="<?= isset($it['image']) ? htmlspecialchars($it['image']) : 'default.jpg' ?>" 
             alt="<?= htmlspecialchars($it['name']) ?>" 
             style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #ddd;">
        <div style="flex:1;">
          <strong><?= htmlspecialchars($it['name']) ?></strong><br>
          <small>Qty: <?= $it['qty'] ?></small>
        </div>
        <span>₱<?= number_format($it['price'] * $it['qty'],2) ?></span>
      </div>
    <?php endforeach; ?>
    <div class="summary-item summary-total">
      <span>Total</span>
      <span>₱<?= number_format($total,2) ?></span>
    </div>
  </div>
</div>
<?php endif; ?>

</body>
</html>
