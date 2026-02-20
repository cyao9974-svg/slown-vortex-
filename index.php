<?php require_once 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1>L'Élégance du Futur</h1>
        <p>Découvrez "Crochet Art by Orly", où le savoir-faire traditionnel rencontre le design futuriste de 2025.</p>
        <a href="/products.php" class="cta-button">Commander maintenant</a>
    </div>
</section>

<section class="categories-section">
    <h2 class="section-title">Nos Univers</h2>
    <div class="category-grid">
        <a href="/products.php?gender=Femme" class="category-card">
            <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=800&q=80" alt="Femme">
            <h3>Femme</h3>
        </a>
        <a href="/products.php?gender=Homme" class="category-card">
            <img src="https://images.unsplash.com/photo-1550246140-5119ae4790b8?auto=format&fit=crop&w=800&q=80" alt="Homme">
            <h3>Homme</h3>
        </a>
        <a href="/products.php?gender=Enfant" class="category-card">
            <img src="https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?auto=format&fit=crop&w=800&q=80" alt="Enfant">
            <h3>Enfant</h3>
        </a>
    </div>
</section>

<section class="products-container">
    <h2 class="section-title">Nouveautés</h2>
    <div class="products-grid">
        <?php
        // Fetch some random products for the homepage
        $stmt = $pdo->query("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id LIMIT 4");
        $products = $stmt->fetchAll();

        if (empty($products)) {
            echo "<p style='text-align:center;'>Bientôt de nouveaux articles disponibles.</p>";
        }

        foreach ($products as $product): ?>
            <div class="card product-card">
                <a href="/product-detail.php?id=<?php echo $product['id']; ?>">
                    <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>" class="product-image">
                    <div class="product-info">
                        <h3><?php echo e($product['name']); ?></h3>
                        <p class="category"><?php echo e($product['cat_name']); ?></p>
                        <p class="price"><?php echo formatPrice($product['price']); ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
