<?php
require_once 'config/db.php';

// On sécurise le titre récupéré dans l'URL
$titre = isset($_GET['titre']) ? $_GET['titre'] : '';

try {
    // On récupère tous les épisodes classés par saison PUIS par numéro d'épisode
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE titre = ? AND type = 'serie' ORDER BY saison ASC, episode ASC");
    $stmt->execute([$titre]);
    $episodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

if (!$episodes) { 
    die("Série introuvable ou aucun épisode disponible."); 
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Épisodes de <?= htmlspecialchars($titre) ?> - YearFlix</title>
    <style>
        body { background:#141414; color:white; font-family:Arial, sans-serif; text-align:center; margin:0; }
        
        .list-container { max-width: 800px; margin: 30px auto; background:#1f1f1f; padding:30px; border-radius:15px; border: 1px solid #333; }
        
        .header-serie { display: flex; align-items: center; justify-content: center; gap: 30px; margin-bottom: 30px; flex-wrap: wrap; }
        
        .cover { width: 180px; height: 260px; object-fit: cover; border-radius:10px; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        
        .serie-info { text-align: left; }
        .serie-info h1 { margin: 0; color: #e50914; font-size: 2.5em; }
        .serie-info p { color: #aaa; margin: 5px 0; }

        .episodes-grid { display: grid; grid-template-columns: 1fr; gap: 10px; text-align: left; }
        
        .episode-link { 
            display: flex; 
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px; 
            color: white; 
            text-decoration: none; 
            background: #2b2b2b;
            border-radius: 8px;
            transition: 0.2s; 
        }
        
        .episode-link:hover { background:#e50914; transform: translateX(5px); }
        
        .play-icon { font-size: 1.2em; }

        /* Style pour mobile */
        @media (max-width: 600px) {
            .header-serie { flex-direction: column; text-align: center; }
            .serie-info { text-align: center; }
            .list-container { margin: 10px; padding: 15px; }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="list-container">
        <div class="header-serie">
            <img src="<?= htmlspecialchars($episodes[0]['image_url']) ?>" class="cover" alt="Affiche">
            <div class="serie-info">
                <h1><?= htmlspecialchars($titre) ?></h1>
                <p>Saison <?= htmlspecialchars($episodes[0]['saison']) ?></p>
                <p><?= count($episodes) ?> épisodes disponibles</p>
            </div>
        </div>

        <h3 style="text-align: left; border-bottom: 2px solid #e50914; padding-bottom: 10px; margin-bottom: 20px;">Liste des épisodes</h3>
        
        <div class="episodes-grid">
            <?php foreach ($episodes as $ep): ?>
                <a href="watch.php?id=<?= $ep['id'] ?>" class="episode-link">
                    <span>
                        <strong style="color: #e50914;">EP <?= htmlspecialchars($ep['episode']) ?></strong> 
                        &nbsp;&nbsp; Saison <?= htmlspecialchars($ep['saison']) ?> - Épisode <?= htmlspecialchars($ep['episode']) ?>
                    </span>
                    <span class="play-icon">▶️</span>
                </a>
            <?php endforeach; ?>
        </div>
        
        <br>
        <a href="series.php" style="color: #aaa; text-decoration: none;">← Retour aux séries</a>
    </div>
</body>
</html>