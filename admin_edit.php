<?php
session_start();
require_once 'config/db.php';

// Protection Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) { die("Accès interdit."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nouvelle_cat = $_POST['categorie'];
    
    $stmt = $pdo->prepare("UPDATE videos SET categorie = ? WHERE id = ?");
    $stmt->execute([$nouvelle_cat, $id]);
    echo "<p style='color:green;'>✅ Mis à jour !</p>";
}

// Récupérer tous les films pour les lister
$videos = $pdo->query("SELECT id, titre, categorie FROM videos ORDER BY titre ASC")->fetchAll();
?>

<body style="background:#141414; color:white; font-family:Arial;">
    <h2>Gérer les catégories</h2>
    <table border="1" style="width:100%; border-collapse:collapse;">
        <tr><th>Titre</th><th>Catégorie actuelle</th><th>Changer pour...</th></tr>
        <?php foreach($videos as $v): ?>
        <tr>
            <td><?= htmlspecialchars($v['titre']) ?></td>
            <td><?= $v['categorie'] ?></td>
            <td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id" value="<?= $v['id'] ?>">
                    <select name="categorie">
                        <option value="Action">Action</option>
                        <option value="Comédie">Comédie</option>
                        <option value="Amour">Amour</option>
                        <option value="Horreur">Horreur</option>
                        <option value="Animation">Animation</option>
                    </select>
                    <button type="submit">OK</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="catalog.php" style="color:white;">Retour au catalogue</a>
</body>