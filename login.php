<?php
session_start(); // À ne pas oublier pour garder la session ouverte
require_once 'config/db.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_active'] == 1) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['pseudo'] = $user['pseudo'];
            header("Location: catalog.php");
            exit();
        } else {
            $error = "Ton compte est en attente de validation par Berkan.";
        }
    } else {
        $error = "Pseudo ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - YearFlix</title>
    <style>
        body { background: #141414; color: white; font-family: Arial; text-align: center; padding-top: 50px; }
        form { max-width: 300px; margin: auto; display: flex; flex-direction: column; gap: 15px; background: #1f1f1f; padding: 20px; border-radius: 10px; }
        input { padding: 12px; border-radius: 5px; border: 1px solid #333; background: #333; color: white; }
        button { padding: 12px; background: #e50914; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .msg { padding: 10px; border-radius: 5px; margin-bottom: 10px; }
        .forgot-link { color: #aaa; text-decoration: none; font-size: 0.85em; transition: 0.3s; margin-top: -5px; }
        .forgot-link:hover { color: #fff; text-decoration: underline; }
    </style>
</head>
<body>
    <h1 style="color:#e50914">YearFlix</h1>
    <h2>Connexion</h2>
    <div style="max-width:300px; margin:auto;">
        <?php if(isset($_GET['pending'])): ?>
            <p class="msg" style="background:rgba(255,165,0,0.2); color:orange;">Inscription réussie ! Attends que l'admin valide ton compte.</p>
        <?php endif; ?>
        <?php if($error) echo "<p class='msg' style='background:rgba(255,0,0,0.2); color:red;'>$error</p>"; ?>
    </div>
    <form method="POST">
        <input type="text" name="pseudo" placeholder="Pseudo" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        
        <button type="submit">Se connecter</button>

        <a href="forgot_password.php" class="forgot-link">Mot de passe oublié ?</a>

        <a href="signup.php" style="color:#ccc; text-decoration:none; font-size:0.9em; margin-top: 10px;">Pas de compte ? S'inscrire</a>
    </form>
</body>
</html>