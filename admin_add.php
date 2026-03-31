<?php
session_start(); // Important pour vérifier la session
require_once 'config/db.php';

// PROTECTION : Seul Berkan (ID 1) peut ajouter
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Accès interdit.");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $annee = (int)$_POST['annee'];
    $video_url = $_POST['video_url'];
    $type = $_POST['type']; 
    $categorie = $_POST['categorie']; // RÉCUPÉRATION DE LA CATÉGORIE
    
    $subs_fr = $_POST['subs_url']; 
    $subs_en = $_POST['subs_en'];  
    $subs_tr = $_POST['subs_tr'];  
    $subs_ar = $_POST['subs_ar'];  
    
    $saison = ($type === 'serie') ? (int)$_POST['saison'] : 0;
    $episode = ($type === 'serie') ? (int)$_POST['episode'] : 0;
    
    $img_nom = time() . "_" . basename($_FILES['image']['name']);
    $img_bdd = "uploads/covers/" . $img_nom; 

    if (!is_dir("uploads/covers/")) {
        mkdir("uploads/covers/", 0777, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $img_bdd)) {
        try {
            // Requête SQL mise à jour avec la colonne 'categorie'
            $sql = "INSERT INTO videos (titre, annee, image_url, video_url, saison, episode, type, subs_url, subs_en, subs_tr, subs_ar, categorie) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titre, $annee, $img_bdd, $video_url, $saison, $episode, $type, $subs_fr, $subs_en, $subs_tr, $subs_ar, $categorie]);
            
            $message = "<span style='color:#2ecc71;'>✅ " . ucfirst($type) . " ajouté en section $categorie !</span>";
        } catch (Exception $e) {
            $message = "<span style='color:#e74c3c;'>❌ Erreur SQL : " . $e->getMessage() . "</span>";
        }
    } else {
        $message = "<span style='color:#e74c3c;'>❌ Erreur upload image.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Ajouter du contenu - YearFlix</title>
    <style>
        body { background:#141414; color:white; font-family: Arial; margin:0; padding:0; text-align:center; }
        .container { padding: 50px 20px; }
        form { max-width:450px; margin:auto; background:#1f1f1f; padding:30px; border-radius:10px; border: 1px solid #333; } 
        input, select, button { width:100%; margin-bottom:15px; padding:12px; box-sizing: border-box; border-radius:5px; border:none; background: #333; color: white; }
        input[type="file"] { background: none; color: #ccc; }
        label { display:block; text-align:left; margin-bottom:5px; color:#aaa; font-size:0.9em; }
        .serie-only { display: none; } 
        button { background:#e50914; color:white; font-weight:bold; cursor:pointer; transition: 0.3s; margin-top: 10px; }
        button:hover { background: #ff0000; }
        .back-link { color: #ccc; text-decoration: none; display: inline-block; margin-top: 20px; }
        .sub-section { border-top: 1px solid #444; margin-top: 10px; padding-top: 15px; }
        h3 { font-size: 1em; color: #e50914; margin-bottom: 15px; text-align: left; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2 style="color:#e50914; margin-bottom: 20px;">Ajouter au Catalogue</h2>
        <p><?= $message ?></p>
        
        <form method="POST" enctype="multipart/form-data">
            <label>Titre du contenu :</label>
            <input type="text" name="titre" placeholder="Ex: Superman" required>
            
            <label>Année de sortie :</label>
            <input type="number" name="annee" placeholder="2025" value="2025" required>
            
            <div style="display:flex; gap:10px;">
                <div style="flex:1;">
                    <label>Type :</label>
                    <select name="type" id="typeSelect" onchange="toggleSerieFields()" required>
                        <option value="film">🎬 Film</option>
                        <option value="serie">📺 Série</option>
                    </select>
                </div>
                <div style="flex:1;">
                    <label>Catégorie :</label>
                    <select name="categorie" required>
                        <option value="Action">💥 Action</option>
                        <option value="Comédie">😂 Comédie</option>
                        <option value="Amour">❤️ Amour</option>
                        <option value="Horreur">😱 Horreur</option>
                        <option value="Animation">🦊 Animation</option>
                    </select>
                </div>
            </div>

            <label>Image de couverture :</label>
            <input type="file" name="image" required>
            
            <label>Lien Vidéo (MP4 direct) :</label>
            <input type="text" name="video_url" placeholder="https://ton-site.com/video.mp4" required>
            
            <div id="serieFields" class="serie-only">
                <div style="display:flex; gap:10px;">
                    <div style="flex:1;">
                        <label>Saison :</label>
                        <input type="number" name="saison" value="1">
                    </div>
                    <div style="flex:1;">
                        <label>Épisode :</label>
                        <input type="number" name="episode" value="1">
                    </div>
                </div>
            </div>

            <div class="sub-section">
                <h3>Sous-titres (Optionnel)</h3>
                <input type="text" name="subs_url" placeholder="Français (ex: subtitles/fr.vtt)">
                <input type="text" name="subs_en" placeholder="Anglais">
                <input type="text" name="subs_tr" placeholder="Turc">
                <input type="text" name="subs_ar" placeholder="Arabe">
            </div>
            
            <button type="submit">Enregistrer dans la base</button>
        </form>
        
        <a href="catalog.php" class="back-link">← Annuler et retourner au catalogue</a>
    </div>

    <script>
    function toggleSerieFields() {
        var type = document.getElementById('typeSelect').value;
        var fields = document.getElementById('serieFields');
        fields.style.display = (type === 'serie') ? 'block' : 'none';
    }
    </script>
</body>
</html>