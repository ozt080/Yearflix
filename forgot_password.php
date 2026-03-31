<?php
require_once 'config/db.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $error = "Veuillez entrer votre email.";
    } else {
        // Correction : On utilise la table 'utilisateurs' comme dans ton login.php
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Pour un projet simple, on simule l'envoi
            $success = true;
        } else {
            $error = "Aucun compte trouvé avec cet email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié - YearFlix</title>
    <style>
        body { background: #141414; color: white; font-family: Arial; text-align: center; padding-top: 50px; }
        .container { max-width: 350px; margin: auto; background: #1f1f1f; padding: 30px; border-radius: 10px; border: 1px solid #333; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #333; background: #333; color: white; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #e50914; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; margin-bottom: 10px; }
        .btn-secondary { background: #333; color: white; }
        .msg { padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9em; }
        h1 { color: #e50914; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>YearFlix</h1>
    <div class="container">
        <h2>Mot de passe oublié</h2>
        <p style="color: #aaa; font-size: 0.9em; margin-bottom: 20px;">Entrez votre email pour réinitialiser votre mot de passe.</p>

        <?php if ($success): ?>
            <div class="msg" style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; border: 1px solid #2ecc71;">
                ✅ Si un compte existe avec cet email, vous pouvez maintenant modifier votre mot de passe.
            </div>
            <a class="btn" href="reset_password.php">Réinitialiser maintenant</a>
            <a class="btn btn-secondary" href="login.php">Retour à la connexion</a>
        <?php else: ?>

            <?php if ($error): ?>
                <div class="msg" style="background: rgba(231, 76, 60, 0.2); color: #e74c3c; border: 1px solid #e74c3c;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <input type="email" name="email" placeholder="Votre adresse email" required>
                <button class="btn" type="submit">Vérifier l'email</button>
            </form>
            
            <a href="login.php" style="color: #ccc; text-decoration: none; font-size: 0.85em;">Annuler</a>
        <?php endif; ?>
    </div>
</body>
</html>