<?php
require_once 'includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF.");
    }
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        redirect('index.php');
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

<div class="form-container">
    <h1 class="section-title" style="font-size: 1.5rem;">Connexion</h1>
    <?php if (isset($_GET['registered'])): ?>
        <p style="color: var(--primary-color); margin-bottom: 1rem;">Inscription réussie ! Connectez-vous.</p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: var(--accent-color); margin-bottom: 1rem;"><?php echo e($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <?php echo csrf_field(); ?>
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn-primary">Se connecter</button>
    </form>
    <p style="margin-top: 1.5rem; text-align: center; color: var(--text-dim);">
        Pas encore de compte ? <a href="register.php" style="color: var(--primary-color);">Inscription</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>
