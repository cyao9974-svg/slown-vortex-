<?php
require_once '../includes/header.php';

if (!isAdmin()) {
    redirect('../login.php');
}

$stmt = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll();
?>

<div class="products-container" style="margin-top: 120px;">
    <h1 class="section-title">Gestion des Commandes</h1>

    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Client</th>
                        <th style="padding: 1rem;">Total</th>
                        <th style="padding: 1rem;">Paiement</th>
                        <th style="padding: 1rem;">Ref</th>
                        <th style="padding: 1rem;">Statut</th>
                        <th style="padding: 1rem;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem;">#<?php echo $o['id']; ?></td>
                            <td style="padding: 1rem;"><?php echo e($o['username']); ?></td>
                            <td style="padding: 1rem; color: var(--primary-color);"><?php echo formatPrice($o['total_amount']); ?></td>
                            <td style="padding: 1rem;"><?php echo e($o['payment_method']); ?></td>
                            <td style="padding: 1rem; font-family: monospace;"><?php echo e($o['payment_reference']); ?></td>
                            <td style="padding: 1rem;">
                                <span style="padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; background: <?php echo $o['status'] === 'Paid' ? '#004400' : '#444'; ?>;">
                                    <?php echo e($o['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; font-size: 0.8rem; color: var(--text-dim);"><?php echo $o['created_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
