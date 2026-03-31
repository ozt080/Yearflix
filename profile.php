<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<h1>Mon profil</h1>

<p>Pseudo : <?= $_SESSION['username'] ?></p>
<p>Email : <?= $_SESSION['email'] ?></p>

<p><a href="catalogue.php">Aller au catalogue</a></p>
<p><a href="logout.php">Se déconnecter</a></p>
