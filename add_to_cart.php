<?php
session_start();

$product_id = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? null;

if (!$product_id || !$size) {
    header("Location: products.php?error=missing");
    exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$key = $product_id . '-' . $size;
if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['qty'] += 1;
} else {
    $_SESSION['cart'][$key] = [
        'product_id' => $product_id,
        'size' => $size,
        'qty' => 1
    ];
}

header("Location: cart.php");
exit;
