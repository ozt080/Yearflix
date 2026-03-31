<?php
session_start();
require_once 'config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$id]);
$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) { die("Contenu introuvable."); }

// Sécurité YouTube : Transforme automatiquement les liens classiques en liens Embed
$url = $video['video_url'];
if (strpos($url, 'youtube.com/watch?v=') !== false) {
    $url = str_replace('watch?v=', 'embed/', $url);
}

$next_episode_id = null;
if ($video['type'] === 'serie') {
    $stmt_next = $pdo->prepare("SELECT id FROM videos WHERE titre = ? AND saison = ? AND episode = ? LIMIT 1");
    $stmt_next->execute([$video['titre'], $video['saison'], $video['episode'] + 1]);
    $next_episode = $stmt_next->fetch();
    if ($next_episode) { $next_episode_id = $next_episode['id']; }
}

$is_embed = (strpos($url, 'uqload') !== false || strpos($url, 'embed') !== false);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Regarder <?= htmlspecialchars($video['titre']) ?> - YearFlix</title>
    <style>
        body { background:#000; color:white; font-family:Arial; text-align:center; margin:0; }
        .content { padding:20px; }
        .video-container { position:relative; width:100%; max-width:900px; margin:auto; background:#1a1a1a; border-radius:10px; border:2px solid #e50914; overflow:hidden; box-shadow: 0 0 20px rgba(229, 9, 20, 0.5); }
        video, iframe { width:100%; height:500px; display:block; border:none; }
        .nav-controls { margin-top:20px; display:flex; justify-content:center; gap:15px; }
        .btn-next { background:#e50914; color:white; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold; }
        .btn-back { background:#333; color:white; padding:12px 25px; text-decoration:none; border-radius:5px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1><?= htmlspecialchars($video['titre']) ?></h1>

        <div class="video-container">
            <?php if ($is_embed): ?>
                <iframe src="<?= htmlspecialchars($url) ?>" allowfullscreen></iframe>
            <?php else: ?>
                <video controls>
                    <source src="<?= htmlspecialchars($url) ?>" type="video/mp4">
                    <?php if (!empty($video['subs_url'])): ?><track src="<?= $video['subs_url'] ?>" kind="subtitles" srclang="fr" label="Français" default><?php endif; ?>
                </video>
            <?php endif; ?>
        </div>

        <div class="nav-controls">
            <a href="catalog.php" class="btn-back">⬅ Retour</a>
            <?php if ($next_episode_id): ?>
                <a href="watch.php?id=<?= $next_episode_id ?>" class="btn-next">Épisode Suivant ➡</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>