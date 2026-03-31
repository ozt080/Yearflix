<?php
session_start(); // N'oublie pas le session_start() pour vérifier l'admin !
require_once 'config/db.php';

// PROTECTION : Seul Berkan (ID 1) peut valider des comptes
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Accès interdit. Vous n'avez pas les droits pour valider des comptes.");
}

$message = "";

// On vérifie si l'ID et l'action existent dans le lien
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    die("Action non autorisée.");
}

$id = (int)$_GET['id'];
$action = $_GET['action'];

try {
    if ($id == 1) {
        die("Action impossible sur le compte administrateur principal.");
    }

    if ($action == 'accept') {
        // ACTIVER (Bouton Vert)
        $stmt = $pdo->prepare("UPDATE utilisateurs SET is_active = 1 WHERE id = ?");
        $stmt->execute([$id]);
        $message = "✅ L'utilisateur a été activé !";
    } elseif ($action == 'deactivate') {
        // DÉSACTIVER (Bouton Rouge)
        $stmt = $pdo->prepare("UPDATE utilisateurs SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
        $message = "🚫 L'utilisateur a été désactivé.";
    } elseif ($action == 'delete') {
        // SUPPRIMER
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        $message = "❌ L'utilisateur a été supprimé définitivement.";
    } else {
        $message = "❓ Action inconnue.";
    }
} catch (Exception $e) {
    $message = "❌ Erreur lors de l'opération : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation Admin - YearFlix</title>
    <style>
        body { background: #141414; color: white; font-family: Arial; text-align: center; padding-top: 100px; }
        h1 { color: #e50914; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #e50914; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s; }
        .btn:hover { background: #ff0000; }
        .container { background: #1f1f1f; max-width: 600px; margin: auto; padding: 20px; border-radius: 10px; border: 1px solid #333; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des comptes</h1>
        <p style="font-size:1.2rem;"><?php echo $message; ?></p>
        <br>
        <a href="admin_users.php" class="btn">Retour à la liste des membres</a>
    </div>
</body>
</html>