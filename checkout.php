<?php
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (empty($_SESSION['cart'])) {
    redirect('cart.php');
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF.");
    }
    $payment_method = $_POST['payment_method'];

    // Simulate payment API call
    $payment_success = true; // In real life, check API response
    $reference = strtoupper(bin2hex(random_bytes(5)));

    if ($payment_success) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method, payment_reference, status) VALUES (?, ?, ?, ?, 'Paid')");
            $stmt->execute([$_SESSION['user_id'], $total, $payment_method, $reference]);
            $order_id = $pdo->lastInsertId();

            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, selected_size, selected_color, selected_length, selected_wool_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($_SESSION['cart'] as $item) {
                $stmt_item->execute([
                    $order_id,
                    $item['id'],
                    $item['quantity'],
                    $item['price'],
                    $item['size'],
                    $item['color'],
                    $item['length'],
                    $item['wool_type']
                ]);
                $stmt_stock->execute([$item['quantity'], $item['id']]);
            }

            $pdo->commit();
            unset($_SESSION['cart']);
            redirect('order-success.php?ref=' . $reference);
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Erreur lors de la commande : " . $e->getMessage();
        }
    } else {
        $error = "Le paiement a échoué.";
    }
}
?>

<div class="products-container" style="margin-top: 120px;">
    <h1 class="section-title">Finaliser la commande</h1>

    <div style="display: flex; gap: 3rem; flex-wrap: wrap;">
        <div class="card" style="flex: 1; min-width: 300px;">
            <h3>Résumé</h3>
            <div style="margin: 1.5rem 0;">
                <?php foreach($_SESSION['cart'] as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--text-dim);">
                        <span><?php echo e($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                        <span><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr style="border-color: var(--glass-border); margin: 1rem 0;">
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem;">
                <span>Total à payer</span>
                <span style="color: var(--primary-color);"><?php echo formatPrice($total); ?></span>
            </div>
        </div>

        <div class="card" style="flex: 1; min-width: 300px;">
            <h3>Paiement Sécurisé</h3>
            <?php if ($error): ?>
                <p style="color: var(--accent-color); margin-bottom: 1rem;"><?php echo e($error); ?></p>
            <?php endif; ?>
            <form action="checkout.php" method="POST" style="margin-top: 2rem;">
                <?php echo csrf_field(); ?>
                <label>Choisir votre mode de paiement :</label>
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Wave" checked>
                        <span>Wave</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Orange Money">
                        <span>Orange Money</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="MTN Money">
                        <span>MTN Money</span>
                    </label>
                </div>
                <button type="submit" class="btn-primary">Payer maintenant</button>
            </form>
        </div>
    </div>
</div>

<style>
.payment-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255,255,255,0.03);
    border-radius: 10px;
    cursor: pointer;
    border: 1px solid var(--glass-border);
}
.payment-option input { width: auto; margin-bottom: 0; }
.payment-option:hover { border-color: var(--primary-color); }
</style>

<?php require_once 'includes/footer.php'; ?>
