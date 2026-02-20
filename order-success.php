<?php
require_once 'includes/header.php';

$ref = $_GET['ref'] ?? '';
?>

<div class="products-container" style="margin-top: 150px; text-align: center;">
    <div class="card" style="max-width: 600px; margin: 0 auto; padding: 4rem;">
        <i class="fas fa-check-circle" style="font-size: 5rem; color: var(--primary-color); margin-bottom: 2rem;"></i>
        <h1 style="font-family: 'Orbitron'; margin-bottom: 1.5rem;">Commande Réussie !</h1>
        <p style="color: var(--text-dim); font-size: 1.2rem; margin-bottom: 2rem;">
            Merci pour votre achat chez <strong>Crochet Art by Orly</strong>. Votre commande a été enregistrée avec succès.
        </p>
        <div style="background: rgba(0, 242, 255, 0.1); padding: 1rem; border-radius: 10px; margin-bottom: 3rem;">
            <p>Référence : <span style="color: var(--primary-color); font-weight: bold;"><?php echo e($ref); ?></span></p>
        </div>

        <div style="margin-bottom: 2rem;">
            <a href="generate-invoice.php?ref=<?php echo e($ref); ?>" class="btn-primary" style="text-decoration: none; padding: 1rem 2rem; border-radius: 30px;">
                <i class="fas fa-file-pdf"></i> Télécharger ma facture
            </a>
        </div>

        <a href="index.php" class="cta-button">Retour à l'accueil</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
