<?php
require_once __DIR__ . '/config/db.php';

$id = intval($_GET['id']);
$stmt = $pdo->prepare("DELETE FROM videos WHERE id = ?");
$stmt->execute([$id]);

header("Location: /admin_list.php");
exit;
