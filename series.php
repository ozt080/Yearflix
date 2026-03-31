<?php
session_start(); // Indispensable pour la navbar (pseudo + admin)
require_once 'config/db.php'; 

// Liste des catégories pour le tri
$genres = ['Action', 'Comédie', 'Amour', 'Horreur', 'Animation'];

try {
    // On récupère tout pour vérifier si la table est vide à la fin
    $stmt_check = $pdo->query("SELECT id FROM videos WHERE type = 'serie' LIMIT 1");
    $has_series = $stmt_check->fetch();
} catch (Exception $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>YearFlix - Séries</title>
    <style>
        body { background-color: #141414; color: white; font-family: Arial, sans-serif; text-align: center; padding: 0; margin: 0; }
        .navbar { background: #000; padding: 15px; border-bottom: 1px solid #333; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; }
        .nav-links { display: flex; gap: 20px; padding-left: 20px; }
        .nav-links a { color: white; text-decoration: none; font-weight: bold; }
        .nav-links a:hover { color: #e50914; }
        .user-info { display: flex; align-items: center; gap: 15px; padding-right: 20px; }
        
        /* GRILLE ET CARTES */
        .grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px; }
        .card { background: #1f1f1f; width: 230px; border-radius: 10px; overflow: hidden; border: 1px solid #333; transition: 0.3s; position: relative; }
        .card:hover { transform: scale(1.05); border-color: #e50914; }
        .card img { width: 100%; height: 320px; object-fit: cover; }
        
        .btn { display: block; background: #e50914; color: white; padding: 10px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 10px; }
        .admin-link { color: #f1c40f; text-decoration: none; font-weight: bold; border: 1px solid #f1c40f; padding: 5px 10px; border-radius: 5px; font-size: 0.8em; }
        .count-badge { position: absolute; top: 10px; right: 10px; background: rgba(229, 9, 20, 0.9); padding: 5px 10px; border-radius: 5px; font-size: 0.8em; font-weight: bold; z-index: 2; }
        
        .category-section { text-align: left; padding: 0 50px; margin-top: 40px; }
        .category-title { color: #e50914; border-left: 5px solid #e50914; padding-left: 15px; text-transform: uppercase; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="catalog.php">Accueil</a>
            <a href="films.php">🎬 Films</a>
            <a href="series.php" style="color: #e50914;">📺 Séries</a>
        </div>
        <div class="user-info">
             <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['user_id'] == 1): ?>
                    <a href="admin_users.php" class="admin-link">⚙️ Membres</a>
                    <a href="admin_add.php" class="admin-link">➕ Ajouter</a>
                <?php endif; ?>
                <span>👤 <?= htmlspecialchars($_SESSION['pseudo'] ?? 'Utilisateur') ?></span>
                <a href="logout.php" style="color:#e50914; text-decoration:none; font-weight:bold;">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" style="color:white; text-decoration:none; font-weight:bold; background:#e50914; padding:8px 15px; border-radius:5px;">Connexion</a>
            <?php endif; ?>
        </div>
    </div>

    <h1 style="color: #e50914; margin-top: 30px;">📺 Toutes les Séries</h1>

    <?php foreach ($genres as $genre): 
        // Requête par genre avec GROUP BY pour éviter les doublons d'épisodes
        $stmt = $pdo->prepare("SELECT *, COUNT(*) as total_episodes FROM videos WHERE type = 'serie' AND categorie = ? GROUP BY titre ORDER BY id DESC");
        $stmt->execute([$genre]);
        $series_par_genre = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($series_par_genre)): ?>
            <div class="category-section">
                <h2 class="category-title"><?= $genre ?></h2>
                <div class="grid">
                    <?php foreach ($series_par_genre as $v): ?>
                    <div class="card">
                        <div class="count-badge"><?= $v['total_episodes'] ?> Ép.</div>
                        <img src="<?= htmlspecialchars($v['image_url']) ?>" alt="Affiche">
                        <div style="padding: 10px;">
                            <h3 style="margin: 0 0 5px 0; font-size: 1.1em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($v['titre']) ?>
                            </h3>
                            <a href="list_episodes.php?titre=<?= urlencode($v['titre']) ?>" class="btn">📂 Voir les épisodes</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (!$has_series): ?>
        <p style="margin-top: 50px; opacity: 0.5;">Aucune série disponible pour le moment.</p>
    <?php endif; ?>

</body>
</html>