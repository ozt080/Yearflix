<?php
// On démarre la session pour pouvoir la manipuler
session_start();

// On vide toutes les variables de session (pseudo, user_id, etc.)
$_SESSION = array();

// On détruit la session sur le serveur
session_destroy();

// On redirige vers la page de connexion
// IMPORTANT : Le fichier doit s'appeler login.php sur ton serveur
header("Location: login.php");
exit();
?>