<?php
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "";
$baseDeDonnees = "gestion_stock";

// Connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

$quantiteEnStock = "";
$nom_produit = "";

if (isset($_POST['nom_produit'])) {
    // Récupération du nom du produit depuis le formulaire et sécurisation
    $nom_produit = $_POST['nom_produit'];
    $nom_produit = mysqli_real_escape_string($connexion, $nom_produit);

    // Recherche de la quantité en stock dans la base de données
    $requete = "SELECT nom_produit, quantite_produit FROM produits
                WHERE nom_produit = '$nom_produit'";

    $resultat = $connexion->query($requete);

    if ($resultat->num_rows > 0) {
        $row = $resultat->fetch_assoc();
        $quantiteEnStock = $row["quantite_produit"];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vérification de la Quantité en Stock</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Entrez le nom du produit :</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="nom_produit" placeholder="Nom du produit">
            </div>
            <button type="submit" class="btn btn-primary">Vérifier la quantité</button>
        </form>

        <?php
        if (!empty($quantiteEnStock)) {
            echo '<h3 class="mt-3">Résultats :</h3>';
            echo '<table class="table">';
            echo '<thead><tr><th>Nom du produit</th><th>Quantité en stock</th></tr></thead>';
            echo '<tbody><tr><td>' . $nom_produit . '</td><td>' . $quantiteEnStock . '</td></tr></tbody>';
            echo '</table>';
        } elseif (isset($nom_produit)) {
            echo '<p class="mt-3">Le produit ' . $nom_produit . ' n\'a pas été trouvé dans le stock.</p>';
        } 
        ?>

    </div>

</body>
</html>