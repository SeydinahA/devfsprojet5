<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Connexion</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
                <div class="mt-2">
                    <?php
                        require('connexion.php');

                        session_start();

                        $login = $password = "";
                        $loginErr = $passwordErr = "";

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST["login"])) {
                                $login = stripslashes($_POST["login"]);
                            }
                            if (isset($_POST["password"])) {
                                $password = stripslashes($_POST["password"]);
                            }

                            if (empty($login)) {
                                echo "Le login est requis.";
                            }
                            if (empty($password)) {
                                echo "Le mot de passe est requis.";
                            }

                            if (empty($loginErr) && empty($passwordErr)) {
                                $login = $conn->real_escape_string($login);
                                $query = "SELECT id, password FROM users WHERE login='$login'";
                                $result = $conn->query($query);

                                if ($result->num_rows == 1) {
                                    $row = $result->fetch_assoc();
                                    $hashedPassword = $row["password"];

                                    if (password_verify($password, $hashedPassword)) {
                                        $_SESSION["login"] = $login;
                                        header("Location: session.php");
                                        exit();
                                    } else {
                                        echo "Mauvais login ou mot de passe.";
                                    }
                                } else {
                                    echo "Mauvais login ou mot de passe.";
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