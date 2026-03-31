<?php
require_once __DIR__ . '/../config/db.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $new_password = trim($_POST['password'] ?? '');

    if ($email === '' || $new_password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Vérifier si l'email existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Mettre à jour le mot de passe
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $update->execute([$hash, $email]);

            $success = true;
        } else {
            $error = "Aucun compte trouvé avec cet email.";
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h2>Réinitialiser le mot de passe</h2>
<p>Entrez votre email et votre nouveau mot de passe.</p>

<?php if ($success): ?>
    <div class="card">
        ✅ Votre mot de passe a été réinitialisé avec succès.
        <br><br>
        <a class="btn" href="/public/login.php">Se connecter</a>
    </div>
<?php else: ?>

    <?php if ($error): ?>
        <div class="card">
            <strong>Erreur :</strong> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Nouveau mot de passe</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Réinitialiser</button>
    </form>

<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
