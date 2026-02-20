<?php
require_once 'includes/header.php';

$gender = $_GET['gender'] ?? null;
$category = $_GET['category'] ?? null;

$query = "SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if ($gender) {
    $query .= " AND c.gender = ?";
    $params[] = $gender;
}

if ($category) {
    $query .= " AND c.name = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="products-container">
    <h1 class="section-title">
        <?php
        if ($category) echo e($category);
        elseif ($gender) echo "Collection " . e($gender);
        else echo "Tous nos articles";
        ?>
    </h1>

    <?php if (empty($products)): ?>
        <p style="text-align: center; margin: 5rem 0;">Aucun produit trouvé dans cette catégorie.</p>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="card product-card">
                    <a href="/product-detail.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>" class="product-image">
                        <div class="product-info">
                            <h3><?php echo e($product['name']); ?></h3>
                            <p class="category"><?php echo e($product['cat_name']); ?> (<?php echo e($gender ?: 'Mixte'); ?>)</p>
                            <p class="price"><?php echo formatPrice($product['price']); ?></p>
                            <p class="stock">Stock: <?php echo $product['stock']; ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
