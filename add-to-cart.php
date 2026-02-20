<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF.");
    }
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $length = $_POST['length'];
    $wool_type = $_POST['wool_type'];

    // Fetch product to verify and get price
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product && $product['stock'] >= $quantity) {
        $cart_item_id = $product_id . '_' . $size . '_' . $color . '_' . $length . '_' . $wool_type;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$cart_item_id])) {
            $_SESSION['cart'][$cart_item_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cart_item_id] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color,
                'length' => $length,
                'wool_type' => $wool_type
            ];
        }
    }
}

redirect('cart.php');
?>
