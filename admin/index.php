<?php
require_once '../includes/header.php';

if (!isAdmin()) {
    redirect('../login.php');
}

// Fetch some stats
$stmt_orders = $pdo->query("SELECT COUNT(*) as total FROM orders");
$total_orders = $stmt_orders->fetch()['total'];

$stmt_products = $pdo->query("SELECT COUNT(*) as total FROM products");
$total_products = $stmt_products->fetch()['total'];

$stmt_users = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$total_users = $stmt_users->fetch()['total'];

$stmt_revenue = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'Paid'");
$total_revenue = $stmt_revenue->fetch()['total'] ?? 0;
?>

<div class="products-container" style="margin-top: 120px;">
    <h1 class="section-title">Tableau de bord Administrateur</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
        <div class="card" style="text-align: center;">
            <h3>Commandes</h3>
            <p style="font-size: 2.5rem; color: var(--primary-color);"><?php echo $total_orders; ?></p>
        </div>
        <div class="card" style="text-align: center;">
            <h3>Produits</h3>
            <p style="font-size: 2.5rem; color: var(--secondary-color);"><?php echo $total_products; ?></p>
        </div>
        <div class="card" style="text-align: center;">
            <h3>Clients</h3>
            <p style="font-size: 2.5rem; color: var(--accent-color);"><?php echo $total_users; ?></p>
        </div>
        <div class="card" style="text-align: center;">
            <h3>Chiffre d'Affaires</h3>
            <p style="font-size: 1.5rem; color: #00ff00;"><?php echo formatPrice($total_revenue); ?></p>
        </div>
    </div>

    <div style="display: flex; gap: 2rem;">
        <a href="products.php" class="cta-button">Gérer les Produits</a>
        <a href="orders.php" class="cta-button" style="background: var(--secondary-color);">Gérer les Commandes</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
