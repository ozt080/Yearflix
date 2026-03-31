<?php
session_start(); // Nécessaire pour vérifier si tu es connecté
require_once 'config/db.php'; 

// Liste des catégories (doit être la même que dans admin_add.php)
$genres = ['Action', 'Comédie', 'Amour', 'Horreur', 'Animation'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>YearFlix - Catalogue</title>
    <style>
        /* On active le défilement doux pour les liens internes */
        html { scroll-behavior: smooth; }
        
        body { background-color: #141414; color: white; font-family: Arial, sans-serif; margin: 0; padding-bottom: 50px; }
        .main-title { color: #e50914; text-align: center; margin-top: 30px; font-size: 2.5em; text-transform: uppercase; letter-spacing: 2px; }
        
        /* BARRE DE NAVIGATION RAPIDE PAR GENRE */
        .genre-nav { 
            background: #1f1f1f; 
            padding: 15px; 
            position: sticky; 
            top: 0; 
            z-index: 100; 
            display: flex; 
            justify-content: center; 
            gap: 15px; 
            border-bottom: 2px solid #e50914;
            flex-wrap: wrap;
        }
        .genre-nav a { 
            color: white; 
            text-decoration: none; 
            font-size: 0.9em; 
            padding: 5px 12px; 
            border-radius: 20px; 
            background: #333; 
            transition: 0.3s; 
        }
        .genre-nav a:hover { background: #e50914; }

        /* Titre principal (FILMS / SÉRIES) */
        .type-header { background: #e50914; color: white; padding: 10px 50px; margin-top: 50px; text-align: left; font-size: 1.5em; font-weight: bold; }
        
        /* Titre des catégories */
        .genre-title { color: #fff; text-align: left; margin: 30px 0 10px 50px; font-size: 1.3em; opacity: 0.8; }
        
        /* Conteneur de défilement */
        .scroll-container { display: flex; overflow-x: auto; gap: 20px; padding: 10px 50px 20px 50px; scrollbar-width: none; }
        .scroll-container::-webkit-scrollbar { display: none; }

        .card { min-width: 200px; max-width: 200px; background: #1f1f1f; border-radius: 8px; overflow: hidden; border: 1px solid #333; transition: 0.3s; position: relative; flex-shrink: 0; }
        .card:hover { transform: scale(1.05); border-color: #e50914; z-index: 10; box-shadow: 0 0 15px rgba(229, 9, 20, 0.4); }
        .card img { width: 100%; height: 280px; object-fit: cover; }
        
        .card-body { padding: 10px; text-align: center; }
        .btn { display: block; background: #e50914; color: white; padding: 8px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.85em; }
        .btn-serie { background: #333; border: 1px solid #e50914; }
        
        .btn-admin { display: block; margin-top: 8px; font-size: 0.75em; color: #aaa; text-decoration: none; border: 1px dashed #444; padding: 4px; border-radius: 4px; transition: 0.3s; }
        .btn-admin:hover { color: #fff; border-color: #e50914; background: #222; }

        h3 { margin: 5px 0; font-size: 0.9em; height: 1.2em; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
        .badge { position: absolute; top: 10px; right: 10px; background: rgba(229, 9, 20, 0.9); padding: 4px 7px; border-radius: 4px; font-size: 0.7em; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <h1 class="main-title">🎬 Catalogue YearFlix</h1>

    <div class="genre-nav">
        <span style="color:#e50914; font-weight:bold; margin-right:10px;">Aller à :</span>
        <?php foreach ($genres as $genre): ?>
            <a href="#<?= strtolower($genre) ?>"><?= $genre ?></a>
        <?php endforeach; ?>
    </div>

    <div id="films-top" class="type-header">FILMS</div>
    
    <?php foreach ($genres as $genre): 
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE type = 'film' AND categorie = ? ORDER BY id DESC");
        $stmt->execute([$genre]);
        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($films)): ?>
            <h3 class="genre-title" id="<?= strtolower($genre) ?>"><?= $genre ?> (Films)</h3>
            <div class="scroll-container">
                <?php foreach ($films as $f): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($f['image_url']) ?>" alt="Affiche">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($f['titre']) ?></h3>
                        <a href="watch.php?id=<?= $f['id'] ?>" class="btn">▶ Regarder</a>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1): ?>
                            <a href="admin_edit_quick.php?id=<?= $f['id'] ?>" class="btn-admin">⚙️ Modifier</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>


    <div id="series-top" class="type-header" style="margin-top: 80px;">SÉRIES</div>

    <?php foreach ($genres as $genre): 
        $stmt = $pdo->prepare("SELECT *, COUNT(*) as total FROM videos WHERE type = 'serie' AND categorie = ? GROUP BY titre ORDER BY id DESC");
        $stmt->execute([$genre]);
        $series = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($series)): ?>
            <h3 class="genre-title"><?= $genre ?> (Séries)</h3>
            <div class="scroll-container">
                <?php foreach ($series as $s): ?>
                <div class="card">
                    <div class="badge"><?= $s['total'] ?> ÉPISODES</div>
                    <img src="<?= htmlspecialchars($s['image_url']) ?>" alt="Affiche">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($s['titre']) ?></h3>
                        <a href="list_episodes.php?titre=<?= urlencode($s['titre']) ?>" class="btn btn-serie">📂 Épisodes</a>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1): ?>
                            <a href="admin_edit_quick.php?id=<?= $s['id'] ?>" class="btn-admin">⚙️ Modifier</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</body>
</html>