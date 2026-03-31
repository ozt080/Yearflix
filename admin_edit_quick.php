<?php
session_start();
require_once 'config/db.php';

// Protection Admin (ID 1 pour Berkan)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) { 
    die("Accès interdit."); 
}

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: catalog.php"); exit; }

// Récupérer les infos actuelles de la vidéo
$stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$id]);
$video = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouvelle_cat = $_POST['categorie'];
    $nouveau_lien = $_POST['video_url']; // Nouveau champ récupéré
    
    // On met à jour la catégorie ET le lien de la vidéo
    $update = $pdo->prepare("UPDATE videos SET categorie = ?, video_url = ? WHERE id = ?");
    $update->execute([$nouvelle_cat, $nouveau_lien, $id]);
    
    // Retour au catalogue à la section modifiée
    header("Location: catalog.php#" . strtolower($nouvelle_cat));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier <?= htmlspecialchars($video['titre']) ?></title>
    <style>
        body { background:#141414; color:white; font-family:Arial; text-align:center; padding:50px; }
        .box { background:#1f1f1f; padding:30px; border-radius:10px; display:inline-block; border:2px solid #e50914; width: 400px; }
        select, input, button { width: 90%; padding:10px; margin-top:15px; border-radius:5px; border: 1px solid #333; }
        label { display: block; margin-top: 15px; font-size: 0.9em; color: #aaa; text-align: left; margin-left: 5%; }
        button { background:#e50914; color:white; border:none; cursor:pointer; font-weight:bold; font-size: 1em; }
        button:hover { background:#b20710; }
        .annuler { display:block; margin-top:20px; color:#aaa; text-decoration:none; font-size:0.8em; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Modifier : <?= htmlspecialchars($video['titre']) ?></h2>
        
        <form method="POST">
            <label>Catégorie :</label>
            <select name="categorie">
                <?php 
                $genres = ['Action', 'Comédie', 'Amour', 'Horreur', 'Animation'];
                foreach($genres as $g): ?>
                    <option value="<?= $g ?>" <?= ($video['categorie'] == $g) ? 'selected' : '' ?>>
                        <?= $g ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Lien de la vidéo (URL) :</label>
            <input type="text" name="video_url" value="<?= htmlspecialchars($video['video_url']) ?>" placeholder="Coller le lien ici (ex: YouTube, Uqload...)">
            
            <button type="submit">Enregistrer les changements</button>
            <a href="catalog.php" class="annuler">Annuler</a>
        </form>
    </div>
</body>
</html>