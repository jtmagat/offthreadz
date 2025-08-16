<?php
session_start();
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $size = $_POST['size'] ?? '';
    $qty = intval($_POST['qty'] ?? 1);

    if ($product_id > 0 && !empty($size) && $qty > 0) {
        // Kunin ang product info (kasama image)
        $stmt = $conn->prepare("SELECT id, name, price, front_image FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $key = $product_id . '_' . $size;

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['qty'] += $qty;
            } else {
                $_SESSION['cart'][$key] = [
                    'id'    => $product_id,
                    'name'  => $product['name'],
                    'price' => floatval($product['price']),
                    'size'  => $size,
                    'qty'   => $qty,
                    'image' => $product['front_image'] // 👈 ADD IMAGE
                ];
            }
        }
    }
}

header("Location: cart.php");
exit;
?>
