<?php
session_start();
require_once 'includes/functions.php';

$id = $_GET['id'] ?? null;

if ($id && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

redirect('cart.php');
?>
