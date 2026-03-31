<div class="navbar" style="background: #000; padding: 15px; border-bottom: 1px solid #333; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000;">
    
    <div style="display: flex; gap: 20px; padding-left: 20px; align-items: center;">
        <a href="catalog.php" style="color:white; text-decoration:none; font-weight:bold;">🏠 Accueil</a>
        <a href="films.php" style="color:white; text-decoration:none; font-weight:bold;">🎬 Films</a>
        <a href="series.php" style="color:white; text-decoration:none; font-weight:bold;">📺 Séries</a>
    </div>

    <div style="flex-grow: 1; display: flex; justify-content: center; max-width: 400px;">
        <form action="catalog.php" method="GET" style="width: 100%; display: flex; position: relative;">
            <input type="text" name="search" placeholder="Rechercher un film ou une série..." 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                   style="width: 100%; padding: 10px 40px 10px 15px; border-radius: 25px; border: 1px solid #333; background: #141414; color: white; outline: none;">
            <button type="submit" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #e50914; cursor: pointer; font-size: 1.2em;">🔍</button>
        </form>
    </div>

    <div style="display: flex; align-items: center; gap: 15px; padding-right: 20px;">
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['user_id'] == 1): ?>
                <a href="admin_users.php" class="btn-admin" style="color: #f1c40f; text-decoration: none; font-weight: bold; border: 1px solid #f1c40f; padding: 5px 10px; border-radius: 5px; font-size: 0.8em; transition: 0.3s;">⚙️ Gérer Membres</a>
                <a href="admin_add.php" class="btn-admin" style="color: #f1c40f; text-decoration: none; font-weight: bold; border: 1px solid #f1c40f; padding: 5px 10px; border-radius: 5px; font-size: 0.8em; transition: 0.3s;">➕ Ajouter</a>
            <?php endif; ?>

            <span style="color: white; font-size: 0.9em;">👤 <?= htmlspecialchars($_SESSION['pseudo']) ?></span>
            <a href="logout.php" style="color:#e50914; text-decoration:none; font-weight:bold; font-size: 0.9em; margin-left: 10px;">Déconnexion</a>
        <?php else: ?>
            <a href="login.php" style="color:white; text-decoration:none; font-weight:bold; background:#e50914; padding:8px 15px; border-radius:5px; transition: 0.3s;" onmouseover="this.style.background='#ff0000'" onmouseout="this.style.background='#e50914'">Se connecter</a>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Effet au survol des boutons Admin */
    .btn-admin:hover {
        background: #f1c40f;
        color: #000 !important;
    }
</style>