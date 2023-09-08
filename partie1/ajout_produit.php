<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_stock";
// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_produit = htmlspecialchars($_POST["nom"]);
    $description_produit = htmlspecialchars($_POST["description"]);
    $quantite_produit = intval($_POST["quantite"]);
    $prix_produit = floatval($_POST["prix"]);

    $stmt = $conn->prepare("INSERT INTO produits (nom_produit, description_produit, quantite_produit, prix_produit) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $nom_produit, $description_produit, $quantite_produit, $prix_produit);

    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']); // Recharge la page pour afficher les produits ajoutés
        exit();
    } else {
        echo "Erreur lors de l'ajout du produit : " . $stmt->error;
    }
}

// Récupération des produits depuis la base de données
$produits = [];
$result = $conn->query("SELECT nom_produit, description_produit, quantite_produit, prix_produit FROM produits");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produits[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Ajouter un nouveau produit</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="nom">Nom du produit:</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="description">Description Produit :</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité :</label>
                <input type="number" class="form-control" id="quantite" name="quantite" required>
            </div>
            <div class="form-group">
                <label for="prix">Prix:</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </form>
    </div>

    <div class="container mt-4">
        <h2>Liste des produits</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom du produit</th>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $produit) { ?>
                    <tr>
                        <td><?php echo $produit['nom_produit']; ?></td>
                        <td><?php echo $produit['description_produit']; ?></td>
                        <td><?php echo $produit['quantite_produit']; ?></td>
                        <td><?php echo $produit['prix_produit']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>