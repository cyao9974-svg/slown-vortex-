<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();

if (!isAdmin()) {
    header('Location: ../login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produit non trouvé.");
}

$stmt = $pdo->query("SELECT * FROM categories ORDER BY gender, name");
$categories = $stmt->fetchAll();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF.");
    }

    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $category_id = $_POST['category_id'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';
    $sizes = $_POST['sizes'] ?? '';
    $colors = $_POST['colors'] ?? '';
    $lengths = $_POST['lengths'] ?? '';
    $wool_types = $_POST['wool_types'] ?? '';

    if ($name && $price) {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ?, image_url = ?, sizes = ?, colors = ?, lengths = ?, wool_types = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $stock, $category_id, $image_url, $sizes, $colors, $lengths, $wool_types, $id]);
        $message = "Produit mis à jour avec succès !";

        // Refresh product data
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit - Crochet art by Orly</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: var(--neon-blue); font-weight: bold; }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 10px;
        }
        .btn-submit {
            background: var(--gradient-neon);
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 20px;
        }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">ORLY <span>ADMIN</span></div>
            <nav>
                <a href="products.php"><i class="fas fa-arrow-left"></i> Retour</a>
            </nav>
        </header>

        <div class="form-container">
            <h2 style="margin-bottom: 30px; text-align: center;">Modifier le Produit</h2>
            <?php if ($message): ?>
                <div style="background: rgba(0,255,0,0.2); padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #0f0;">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <?php echo csrf_field(); ?>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Nom du produit</label>
                        <input type="text" name="name" value="<?= e($product['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Catégorie</label>
                        <select name="category_id">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                    <?= e($cat['gender'] . ' - ' . $cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Prix (CFA)</label>
                        <input type="number" name="price" value="<?= $product['price'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>URL Image</label>
                    <input type="text" name="image_url" value="<?= e($product['image_url']) ?>">
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Tailles (séparées par des virgules)</label>
                        <input type="text" name="sizes" value="<?= e($product['sizes']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Couleurs (séparées par des virgules)</label>
                        <input type="text" name="colors" value="<?= e($product['colors']) ?>">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Longueurs</label>
                        <input type="text" name="lengths" value="<?= e($product['lengths']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Types de laine</label>
                        <input type="text" name="wool_types" value="<?= e($product['wool_types']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="5"><?= e($product['description']) ?></textarea>
                </div>

                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</body>
</html>