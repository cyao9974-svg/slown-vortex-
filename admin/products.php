<?php
require_once '../includes/header.php';

if (!isAdmin()) {
    redirect('../login.php');
}

$message = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (verify_csrf_token($_POST['csrf_token'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $message = "Produit supprimé avec succès.";
    } else {
        $message = "Erreur CSRF.";
    }
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Erreur CSRF.");
    }
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];
    $sizes = $_POST['sizes'];
    $colors = $_POST['colors'];
    $lengths = $_POST['lengths'];
    $wool_types = $_POST['wool_types'];

    $stmt = $pdo->prepare("INSERT INTO products (name, category_id, description, price, stock, image_url, sizes, colors, lengths, wool_types) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category_id, $description, $price, $stock, $image_url, $sizes, $colors, $lengths, $wool_types]);
    $message = "Produit ajouté avec succès.";
}

$stmt = $pdo->query("SELECT p.*, c.name as cat_name, c.gender FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY gender, name")->fetchAll();
?>

<div class="products-container" style="margin-top: 120px;">
    <h1 class="section-title">Gestion des Produits</h1>

    <?php if ($message): ?>
        <p style="color: var(--primary-color); text-align: center; margin-bottom: 2rem;"><?php echo e($message); ?></p>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem;">
        <!-- Add Product Form -->
        <div class="card">
            <h3>Ajouter un Produit</h3>
            <form action="products.php" method="POST" style="margin-top: 1.5rem;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="add_product" value="1">
                <label>Nom</label>
                <input type="text" name="name" required>

                <label>Catégorie</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['gender'] . ' - ' . $cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Prix</label>
                <input type="number" name="price" step="0.01" required>

                <label>Stock</label>
                <input type="number" name="stock" required>

                <label>Image URL</label>
                <input type="text" name="image_url" placeholder="https://..." required>

                <label>Tailles (séparées par des virgules)</label>
                <input type="text" name="sizes" value="S,M,L,XL">

                <label>Couleurs (séparées par des virgules)</label>
                <input type="text" name="colors" value="Bleu,Rouge,Jaune">

                <label>Longueurs</label>
                <input type="text" name="lengths" value="Courte,Longue">

                <label>Types de laine</label>
                <input type="text" name="wool_types" value="Coton,Velours,Acrylique">

                <label>Description</label>
                <textarea name="description" rows="4"></textarea>

                <button type="submit" class="btn-primary">Ajouter</button>
            </form>
        </div>

        <!-- Products List -->
        <div class="card">
            <h3>Liste des Produits</h3>
            <div style="overflow-x: auto; margin-top: 1.5rem;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                            <th style="padding: 1rem;">Image</th>
                            <th style="padding: 1rem;">Nom</th>
                            <th style="padding: 1rem;">Cat</th>
                            <th style="padding: 1rem;">Prix</th>
                            <th style="padding: 1rem;">Stock</th>
                            <th style="padding: 1rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td style="padding: 1rem;"><img src="<?php echo e($p['image_url']); ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;"></td>
                                <td style="padding: 1rem;"><?php echo e($p['name']); ?></td>
                                <td style="padding: 1rem; font-size: 0.8rem;"><?php echo e($p['gender'] . ' - ' . $p['cat_name']); ?></td>
                                <td style="padding: 1rem;"><?php echo formatPrice($p['price']); ?></td>
                                <td style="padding: 1rem;"><?php echo $p['stock']; ?></td>
                                <td style="padding: 1rem;">
                                    <a href="edit-product.php?id=<?php echo $p['id']; ?>" style="color: var(--secondary-color); margin-right: 10px;"><i class="fas fa-edit"></i></a>
                                    <form action="products.php" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" style="background: none; border: none; color: var(--accent-color); cursor: pointer;" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
