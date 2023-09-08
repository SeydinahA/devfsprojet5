<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Inscription</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmation mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>
                <div class="mt-2">
                    <?php
                        require('connexion.php');

                        $nom = $prenom = $login = $password = $confirmPassword = "";
                        $nomErr = $prenomErr = $loginErr = $passwordErr = $confirmPasswordErr = "";

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST["nom"])) {
                                $nom = stripslashes($_POST["nom"]);
                            }
                            if (isset($_POST["prenom"])) {
                                $prenom = stripslashes($_POST["prenom"]);
                            }
                            if (isset($_POST["login"])) {
                                $login = stripslashes($_POST["login"]);
                            }
                            if (isset($_POST["password"])) {
                                $password = stripslashes($_POST["password"]);
                            }
                            if (isset($_POST["confirm_password"])) {
                                $confirmPassword = stripslashes($_POST["confirm_password"]);
                            }

                            if (empty($nom)) {
                                echo "Le nom est requis.<br>";
                            }

                            if (empty($prenom)) {
                                echo "Le prenom est requis.<br>";
                            }

                            if (empty($login)) {
                                echo "Le login est requis.<br>";
                            }

                            if (empty($password)) {
                                echo "Le mot_de_passe est requis.<br>";
                            }


                            if (empty($confirmPassword)) {
                                echo "La confirmation du mot_de_passe est requis.<br>";
                            }

                            if (empty($nomErr) && empty($prenomErr) && empty($loginErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
                            
                                // Vérifiez la conformité des mots de passes saisi par l'utilisateur...
                                if ($password !== $confirmPassword) {
                                    echo "Les mots de passe ne correspondent pas.";
                                } else {
                                    // Vérifier si le login existe déjà
                                    $checkLoginQuery = "SELECT id FROM users WHERE login='$login'";
                                    $result = $conn->query($checkLoginQuery);

                                    if ($result->num_rows > 0) {
                                        echo "Ce login est déjà pris. Veuillez saisir un autre login.";
                                    } else {
                                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                                        $insertQuery = "INSERT INTO users (nom, prenom, login, password) VALUES ('$nom', '$prenom', '$login', '$hashedPassword')";

                                        if ($conn->query($insertQuery) === TRUE) {
                                            echo "Inscription réussie.";
                                        } else {
                                            echo "Erreur : Veuillez vous inscrire à nouveau";
                                        }
                                    }
                                }
                            }

                            $conn->close();
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

