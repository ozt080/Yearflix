<?php
require_once 'config/db.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Insertion avec is_active à 0 par défaut
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, email, password, is_active) VALUES (?, ?, ?, 0)");
        $stmt->execute([$pseudo, $email, $password]);
        $user_id = $pdo->lastInsertId();

        // --- ENVOI DU MAIL À BERKAN ---
        $to = "berkanoztas11@gmail.com";
        $subject = "Nouvelle inscription sur YearFlix : $pseudo";
        
        // Liens pour ton action directe depuis le mail
        $valide_link = "https://yearflix.free.nf/validate.php?id=$user_id&action=accept";
        $refuse_link = "https://yearflix.free.nf/validate.php?id=$user_id&action=delete";
        
        $message = "Bonjour Berkan,\n\nUn nouvel utilisateur veut s'inscrire :\n";
        $message .= "Pseudo : $pseudo\n";
        $message .= "Email : $email\n\n";
        $message .= "POUR ACCEPTER : $valide_link\n";
        $message .= "POUR REFUSER : $refuse_link";
        
        $headers = "From: no-reply@yearflix.free.nf\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8";

        mail($to, $subject, $message, $headers);
        // -------------------------------------

        header("Location: login.php?pending=1");
        exit();
    } catch (Exception $e) {
        $error = "Ce pseudo ou cet email est déjà utilisé.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - YearFlix</title>
    <style>
        body { background: #141414; color: white; font-family: Arial; text-align: center; padding-top: 50px; }
        form { max-width: 300px; margin: auto; display: flex; flex-direction: column; gap: 15px; background: #1f1f1f; padding: 20px; border-radius: 10px; }
        input { padding: 12px; border-radius: 5px; border: 1px solid #333; background: #333; color: white; }
        button { padding: 12px; background: #e50914; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        a { color: #ccc; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>
    <h1 style="color:#e50914">YearFlix</h1>
    <h2>Créer un compte</h2>
    <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="pseudo" placeholder="Pseudo" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">S'inscrire</button>
        <a href="login.php">Déjà un compte ? Connexion</a>
    </form>
</body>
</html>