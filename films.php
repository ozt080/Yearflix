<?php
session_start(); // Indispensable pour récupérer $_SESSION['user_id']
require_once 'config/db.php'; 

// Liste des catégories pour le tri
$genres = ['Action', 'Comédie', 'Amour', 'Horreur', 'Animation'];

try {
    // Si tu veux un affichage simple (tout en vrac comme dans ton code actuel) :
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE type = 'film' ORDER BY id DESC");
    $stmt->execute();
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>YearFlix - Films</title>
    <style>
        body { background-color: #141414; color: white; font-family: Arial, sans-serif; text-align: center; padding: 0; margin: 0; }
        
        /* NAVBAR */
        .navbar { background: #000; padding: 15px; border-bottom: 1px solid #333; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; }
        .nav-links { display: flex; gap: 20px; padding-left: 20px; }
        .nav-links a { color: white; text-decoration: none; font-weight: bold; }
        .nav-links a:hover { color: #e50914; }
        .user-info { display: flex; align-items: center; gap: 15px; padding-right: 20px; }
        
        /* GRILLE DE FILMS */
        .grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px; }
        .card { background: #1f1f1f; width: 220px; border-radius: 10px; overflow: hidden; border: 1px solid #333; transition: 0.3s; position: relative; }
        .card:hover { transform: scale(1.05); border-color: #e50914; z-index: 5; }
        .card img { width: 100%; height: 310px; object-fit: cover; }
        
        .card-body { padding: 10px; }
        .card-body h3 { font-size: 1rem; margin: 10px 0 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
        .btn { display: block; background: #e50914; color: white; padding: 10px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 5px; }
        .admin-link { color: #f1c40f; text-decoration: none; font-weight: bold; border: 1px solid #f1c40f; padding: 5px 10px; border-radius: 5px; font-size: 0.8em; }
        
        /* Styles pour les titres de catégories si tu décides de trier */
        .category-section { text-align: left; padding: 0 50px; margin-top: 40px; }
        .category-title { color: #e50914; border-left: 5px solid #e50914; padding-left: 15px; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="catalog.php">Accueil</a>
            <a href="films.php" style="color: #e50914;">🎬 Films</a>
            <a href="series.php">📺 Séries</a>
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

    <h1 style="color: #e50914; margin-top: 30px;">🎬 Tous les Films</h1>

    <?php foreach ($genres as $genre): 
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE type = 'film' AND categorie = ? ORDER BY id DESC");
        $stmt->execute([$genre]);
        $films_par_genre = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($films_par_genre)): ?>
            <div class="category-section">
                <h2 class="category-title"><?= $genre ?></h2>
                <div class="grid">
                    <?php foreach ($films_par_genre as $v): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($v['image_url']) ?>" alt="Affiche">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($v['titre']) ?></h3>
                            <p style="color: #aaa; font-size: 0.9em;"><?= htmlspecialchars($v['annee'] ?? '') ?></p>
                            <a href="watch.php?id=<?= $v['id'] ?>" class="btn">▶️ Regarder</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (empty($videos)): ?>
        <p style="margin-top: 50px; opacity: 0.5;">Aucun film n'a été trouvé dans le catalogue.</p>
    <?php endif; ?>

</body>
</html>