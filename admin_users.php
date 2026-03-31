<?php
require_once 'config/db.php';
session_start();

// PROTECTION : Seul Berkan (ID 1)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Accès interdit.");
}

// 1. On récupère les nouveaux en attente (is_active = 0)
$stmt_wait = $pdo->query("SELECT * FROM utilisateurs WHERE is_active = 0 AND id != 1 ORDER BY id DESC");
$en_attente = $stmt_wait->fetchAll();

// 2. On récupère les membres validés AVEC le calcul de présence
// On considère "En ligne" si la dernière activité date de moins de 5 minutes
$stmt_ok = $pdo->query("SELECT *, 
    (derniere_activite > DATE_SUB(NOW(), INTERVAL 5 MINUTE)) as is_online 
    FROM utilisateurs WHERE is_active = 1 AND id != 1 ORDER BY id DESC");
$valides = $stmt_ok->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des membres - YearFlix</title>
    <style>
        body { background: #141414; color: white; font-family: Arial; padding: 20px; text-align: center; }
        .section-title { color: #e50914; margin-top: 40px; border-bottom: 2px solid #333; padding-bottom: 10px; display: inline-block; width: 80%; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; background: #1f1f1f; }
        th, td { border: 1px solid #333; padding: 12px; text-align: center; }
        th { background: #000; color: #e50914; font-size: 0.8em; }
        
        .btn { text-decoration: none; font-weight: bold; padding: 8px 12px; border-radius: 5px; font-size: 0.8em; transition: 0.3s; }
        .btn-v { color: #2ecc71; border: 1px solid #2ecc71; }
        .btn-v:hover { background: #2ecc71; color: white; }
        .btn-r { color: #e74c3c; border: 1px solid #e74c3c; }
        .btn-r:hover { background: #e74c3c; color: white; }
        
        /* Les pastilles de statut */
        .status-dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
        .bg-online { background: #2ecc71; box-shadow: 0 0 8px #2ecc71; } /* Vert brillant */
        .bg-offline { background: #555; } /* Gris éteint */
    </style>
</head>
<body>

    <h2 class="section-title">⏳ Nouveaux membres en attente</h2>
    <table>
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Décision</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($en_attente)): ?>
                <tr><td colspan="3" style="color:#888;">Aucune demande en attente.</td></tr>
            <?php else: ?>
                <?php foreach ($en_attente as $u): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($u['pseudo']) ?></strong></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <a href="validate.php?id=<?= $u['id'] ?>&action=accept" class="btn btn-v">✅ Accepter</a>
                        <a href="validate.php?id=<?= $u['id'] ?>&action=delete" class="btn btn-r" style="margin-left:10px;">❌ Refuser</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h2 class="section-title">👥 Membres déjà actifs</h2>
    <table>
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($valides as $v): ?>
            <tr>
                <td><?= htmlspecialchars($v['pseudo']) ?></td>
                <td>
                    <?php if ($v['is_online']): ?>
                        <span style="color:#2ecc71;"><span class="status-dot bg-online"></span> En ligne</span>
                    <?php else: ?>
                        <span style="color:#888;"><span class="status-dot bg-offline"></span> Hors ligne</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="validate.php?id=<?= $v['id'] ?>&action=deactivate" class="btn btn-r">🚫 Désactiver</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br><br>
    <a href="catalog.php" style="color:#888; text-decoration:none;">← Retour au Catalogue</a>

</body>
</html>