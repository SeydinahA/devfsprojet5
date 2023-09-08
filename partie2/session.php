<?php
  // Initialiser la session
  session_start();
  // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
  if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit(); 
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page à accès limité</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <h1>Bienvenue <?php echo $_SESSION['login']; ?>!</h1>
    <p>Ce contenu est uniquement accessible aux utilisateurs authentifiés.</p>
    <a href="deconnexion.php">Se déconnecter</a>
</body>
</html>



