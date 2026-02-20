<?php
require_once 'includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('products.php');
}

$stmt = $pdo->prepare("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='products-container'><p>Produit non trouvé.</p></div>";
    require_once 'includes/footer.php';
    exit();
}

$sizes = explode(',', $product['sizes']);
$colors = explode(',', $product['colors']);
$lengths = explode(',', $product['lengths']);
$wool_types = explode(',', $product['wool_types']);
?>

<div class="products-container" style="margin-top: 120px;">
    <div style="display: flex; gap: 4rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <div class="card" style="padding: 0; overflow: hidden;">
                <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>" style="width: 100%; display: block;">
            </div>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-family: 'Orbitron'; margin-bottom: 1rem;"><?php echo e($product['name']); ?></h1>
            <p class="category" style="color: var(--primary-color); margin-bottom: 1rem;"><?php echo e($product['cat_name']); ?></p>
            <p class="price" style="font-size: 2rem; margin-bottom: 2rem;"><?php echo formatPrice($product['price']); ?></p>

            <div style="margin-bottom: 2rem;">
                <h3>Description</h3>
                <p style="color: var(--text-dim);"><?php echo nl2br(e($product['description'])); ?></p>
            </div>

            <form action="add-to-cart.php" method="POST" class="customization-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                <div style="margin-bottom: 1.5rem;">
                    <label>Taille :</label>
                    <select name="size" required>
                        <?php foreach($sizes as $s): if(trim($s)): ?>
                            <option value="<?php echo trim(e($s)); ?>"><?php echo trim(e($s)); ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label>Couleur :</label>
                    <select name="color" required>
                        <?php foreach($colors as $c): if(trim($c)): ?>
                            <option value="<?php echo trim(e($c)); ?>"><?php echo trim(e($c)); ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label>Longueur :</label>
                    <select name="length" required>
                        <?php foreach($lengths as $l): if(trim($l)): ?>
                            <option value="<?php echo trim(e($l)); ?>"><?php echo trim(e($l)); ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label>Type de Laine :</label>
                    <select name="wool_type" required>
                        <?php foreach($wool_types as $w): if(trim($w)): ?>
                            <option value="<?php echo trim(e($w)); ?>"><?php echo trim(e($w)); ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label>Quantité :</label>
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 80px;">
                </div>

                <?php if ($product['stock'] > 0): ?>
                    <button type="submit" class="btn-primary">Ajouter au panier</button>
                <?php else: ?>
                    <button type="button" class="btn-primary" style="background: #444; cursor: not-allowed;" disabled>Rupture de stock</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<style>
.customization-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: var(--text-main);
}
</style>

<?php require_once 'includes/footer.php'; ?>
