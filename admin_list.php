<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="container">
  <h2>Liste des vidéos</h2>

  <div class="grid">
    <?php foreach ($videos as $v): ?>
      <div class="card">
        <img src="<?= ($v['cover_path']) ?>" alt="">
        <h3><?= ($v['title']) ?></h3>

        <a class="btn" href="/watch.php?id=<?= $v['id'] ?>">Voir</a>
        <a class="btn" style="background:#2a3446;" href="/admin_delete.php?id=<?= $v['id'] ?>">Supprimer</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
