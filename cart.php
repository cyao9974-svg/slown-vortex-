<?php require_once 'includes/header.php'; ?>

<div class="products-container" style="margin-top: 120px;">
    <h1 class="section-title">Votre Panier</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p style="text-align: center;">Votre panier est vide.</p>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="products.php" class="cta-button">Découvrir nos articles</a>
        </div>
    <?php else: ?>
        <div class="card">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 1rem;">Produit</th>
                        <th style="padding: 1rem;">Personnalisation</th>
                        <th style="padding: 1rem;">Prix</th>
                        <th style="padding: 1rem;">Quantité</th>
                        <th style="padding: 1rem;">Total</th>
                        <th style="padding: 1rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                <img src="<?php echo e($item['image']); ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <span><?php echo e($item['name']); ?></span>
                            </td>
                            <td style="padding: 1rem; font-size: 0.9rem; color: var(--text-dim);">
                                Taille: <?php echo e($item['size']); ?><br>
                                Couleur: <?php echo e($item['color']); ?><br>
                                Longueur: <?php echo e($item['length']); ?><br>
                                Laine: <?php echo e($item['wool_type']); ?>
                            </td>
                            <td style="padding: 1rem;"><?php echo formatPrice($item['price']); ?></td>
                            <td style="padding: 1rem;"><?php echo $item['quantity']; ?></td>
                            <td style="padding: 1rem; color: var(--primary-color); font-weight: bold;"><?php echo formatPrice($subtotal); ?></td>
                            <td style="padding: 1rem;">
                                <a href="remove-from-cart.php?id=<?php echo urlencode($id); ?>" style="color: var(--accent-color);"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="margin-top: 2rem; text-align: right;">
                <h2 style="margin-bottom: 1.5rem;">Total: <?php echo formatPrice($total); ?></h2>
                <a href="checkout.php" class="cta-button">Passer à la caisse</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
