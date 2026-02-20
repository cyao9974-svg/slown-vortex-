<?php
require_once 'includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF.");
    }
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = "L'utilisateur ou l'email existe déjà.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                redirect('login.php?registered=1');
            } else {
                $error = "Une erreur est survenue.";
            }
        }
    }
}
?>

<div class="form-container">
    <h1 class="section-title" style="font-size: 1.5rem;">Inscription</h1>
    <?php if ($error): ?>
        <p style="color: var(--accent-color); margin-bottom: 1rem;"><?php echo e($error); ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <?php echo csrf_field(); ?>
        <label>Nom d'utilisateur</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <label>Confirmer le mot de passe</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn-primary">S'inscrire</button>
    </form>
    <p style="margin-top: 1.5rem; text-align: center; color: var(--text-dim);">
        Déjà un compte ? <a href="login.php" style="color: var(--primary-color);">Connexion</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>
