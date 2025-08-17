<?php
$conn = new mysqli("localhost","root","","offthreadz_db");
$order_id = $_GET['order_id'] ?? 0;

// Get order
$order = $conn->query("SELECT * FROM orders WHERE id=$order_id")->fetch_assoc();
$items = $conn->query("SELECT * FROM order_items WHERE order_id=$order_id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Thank You</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f2f2f2; padding: 30px; }
    .box { background: #fff; padding: 20px; border-radius: 10px; max-width: 800px; margin: auto; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
    h2 { color: green; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: center; }
    th { background: #333; color: #fff; }
    img { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border:1px solid #ccc; }
    a { display:inline-block; margin-top:15px; color:#0066cc; text-decoration:none; font-weight:600; }
    a:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Thank you for your order!</h2>
    <p>Your Order ID: <strong>#<?= $order['id'] ?></strong></p>
    <p>Total Paid: <strong>₱<?= number_format($order['total_price'],2) ?></strong></p>

    <h3>Items Ordered:</h3>
    <table>
      <tr><th>Image</th><th>Product</th><th>Size</th><th>Qty</th><th>Price</th></tr>
      <?php while($it = $items->fetch_assoc()): ?>
        <tr>
          <td>
            <img src="<?= $it['image'] ? htmlspecialchars($it['image']) : 'default.jpg' ?>" 
                 alt="<?= htmlspecialchars($it['product_name']) ?>">
          </td>
          <td><?= htmlspecialchars($it['product_name']) ?></td>
          <td><?= htmlspecialchars($it['size']) ?></td>
          <td><?= $it['qty'] ?></td>
          <td>₱<?= number_format($it['price'],2) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
    <br>
    <a href="products.php">Continue Shopping</a>
  </div>
</body>
</html>
