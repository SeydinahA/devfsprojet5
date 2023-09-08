<!DOCTYPE html>
<html>
<head>
    <title>Enregistrer un produit pris du stock</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Enregistrer un produit pris du stock</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="nom_produit">Nom du produit:</label>
                <input type="text" class="form-control" name="nom_produit" required>
            </div>
            <div class="form-group">
                <label for="quantite_prise">Quantité prise:</label>
                <input type="number" class="form-control" name="quantite_prise" required>
            </div>
            <div class="form-group">
                <label for="prix_produit">Prix du produit:</label>
                <input type="number" step="0.01" class="form-control" name="prix_produit" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Enregistrer">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "gestion_stock";
            // Connexion à la base de données
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("La connexion a échoué : " . $conn->connect_error);
            }

            $nom_produit = stripslashes($_POST['nom_produit']);
            $quantite_prise = stripslashes(intval($_POST['quantite_prise']));
            $prix_produit = stripslashes(floatval($_POST['prix_produit']));

            $sql_check_product = "SELECT id, quantite_produit FROM produits WHERE nom_produit = '$nom_produit'";
            $result_check_product = $conn->query($sql_check_product);

            if ($result_check_product->num_rows > 0) {
                $row = $result_check_product->fetch_assoc();
                $id_produit = $row['id'];
                $available_quantity = $row['quantite_produit'];

                if ($available_quantity >= $quantite_prise) {
                    $new_quantity = $available_quantity - $quantite_prise;
                    $sql_update_stock = "UPDATE produits SET quantite_produit = $new_quantity WHERE id = $id_produit";

                    if ($conn->query($sql_update_stock) === TRUE) {
                        $sql_insert_product_taken = "INSERT INTO produits_pris (id_produit, quantite_prise, prix_produit, date_prise) VALUES ($id_produit, $quantite_prise, $prix_produit, NOW())";

                        if ($conn->query($sql_insert_product_taken) === TRUE) {
                            echo "<p class='text-success'>Produit pris du stock enregistré avec succès.</p>";
                        } else {
                            echo "<p class='text-danger'>Erreur lors de l'enregistrement du produit pris : " . $conn->error . "</p>";
                        }
                    } else {
                        echo "<p class='text-danger'>Erreur lors de la mise à jour du stock : " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p class='text-danger'>La quantité demandée n'est pas disponible en stock.</p>";
                }
            } else {
                echo "<p class='text-danger'>Le produit n'a pas été trouvé en stock.</p>";
            }

            $conn->close();
        }
        ?>

        <!-- Affichage des produits pris sous forme de tableau -->
        <?php
         $servername = "localhost";
         $username = "root";
         $password = "";
         $dbname = "gestion_stock";

         $conn = new mysqli($servername, $username, $password, $dbname);
         if ($conn->connect_error) {
             die("La connexion a échoué : " . $conn->connect_error);
         }
        $sql_get_taken_products = "SELECT produits_pris.*, produits.nom_produit FROM produits_pris INNER JOIN produits ON produits_pris.id_produit = produits.id";
        $result = $conn->query($sql_get_taken_products);

        if ($result !== false && $result->num_rows > 0) {
            echo "<h3 class='mt-4'>Produits pris du stock :</h3>";
            echo "<table class='table'>";
            echo "<thead>
                    <tr>
                        <th>Nom du produit</th>
                        <th>Quantité prise</th>
                        <th>Prix du produit</th>
                        <th>Date de prise</th>
                    </tr>
                </thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {  
                echo "<tr>";
                echo "<td>" . $row['nom_produit'] . "</td>";
                echo "<td>" . $row['quantite_prise'] . "</td>";
                echo "<td>" . $row['prix_produit'] . "</td>";
                echo "<td>" . (isset($row['date_prise']) ? $row['date_prise'] : '') . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='mt-4'>Aucun produit pris du stock enregistré.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>