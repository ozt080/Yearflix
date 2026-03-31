<?php
$pdo = new PDO("mysql:host=localhost;dbname=yearflix", "root", "");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Hash du mot de passe
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (email, password_hash, display_name, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $hash, $display_name]);

$to = "berkanoztas11@gmail.com";
$subject = "Nouvelle inscription sur YearFlix";
$messageMail = "Un nouvel utilisateur s'est inscrit : $display_name ($email)\n\nConnecte-toi à ton admin pour l'accepter.";
$headers = "From: noreply@yearflix.com";

mail($to, $subject, $messageMail, $headers);



    $message = "Compte créé, tu peux maintenant te connecter.";
}
?>

<h1>Inscription</h1>

<?php if ($message): ?>
  <p><?= $message ?></p>
<?php endif; ?>

<form method="post">
  <label>Pseudo :</label><br>
  <input type="text" name="username" required><br><br>

  <label>Email :</label><br>
  <input type="email" name="email" required><br><br>

  <label>Mot de passe :</label><br>
  <input type="password" name="password" required><br><br>

  <button type="submit">Créer mon compte</button>
</form>
