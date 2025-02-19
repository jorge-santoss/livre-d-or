<?php
include '../utils/config.php';
include 'Validator.php';
include 'User.php';

// var_dump($_POST);
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nom']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['confirm_password'])) {
        $nom = Validator::testInput($_POST['nom']);
        $email = Validator::testInput($_POST['email']);
        $password = Validator::testInput($_POST['password']);
        $confirm_password = Validator::testInput($_POST['confirm_password']);

        // Validate username
        if (empty($nom)) {
            $message .= "Le nom d'utilisateur est requis.<br>";
        } elseif (!Validator::validateUsername($nom)) {
            $message .= "Seuls les lettres, les chiffres et les traits de soulignement sont autorisés pour le nom d'utilisateur et il doit contenir au maximum 10 caractères.<br>";
        }

        // Validate email
        if (empty($email)) {
            $message .= "L'email est requis.<br>";
        } elseif (!Validator::validateEmail($email)) {
            $message .= "Format d'identifiant email non valide.<br>";
        }

        // Validate password
        if (empty($password)) {
            $message .= "Le mot de passe est requis.<br>";
        } elseif (!Validator::validatePassword($password)) {
            $message .= "Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.<br>";
        }

        // Confirm password match
        if ($password !== $confirm_password) {
            $message .= "Les mots de passe ne correspondent pas.<br>";
        }

        if (empty($message)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $user = new User($pdo);

            if ($user->userExists($nom, $email)) {
                $message = "L'utilisateur ou l'email existe déjà.";
            } else {
                $user->createUser($nom, $email, $password_hashed);
                $message = "Inscription réussie !";
                header("Location: connexion.php"); // Rediriger après inscription réussie
                exit();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>signup</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Dancing+Script:wght@400..700&family=Press+Start+2P&family=Quattrocento+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <main>
        <p class="livre_title">Livre D'Or</p>
            <div class="livre_form">
                <div class="livre_card">
                    <form action="inscription.php" method="post">
                        <div class="tittle">
                            <h2>Inscription</h2>
                        </div>
                        <div class="text_area">
                            <label for="Nom"></label>
                            <input type="text" id="nom" name="nom" placeholder="nom"><br>

                            <label for="email"></label>
                            <input type="email" id="email" name="email" placeholder="Email"><br>

                            <label for="password"></label>
                            <input type="password" id="password" name="password" placeholder="Mot de passe"><br>

                            <label for="confirm_password"></label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm mot de passe">
                            <span id='message'></span>
                        </div>

                        <input type="submit" value="S'inscrire" class="button_livre">

                        <p class="inscription"> "Avez vous déjà un compte?"
                            <br><a href="connexion.php">Connexion</a>
                        </p>
                    </form>
                </div>
            </div>
        <div class="messageErr">    
            <?php if ($message) echo "<p>$message</p>"; ?>
        </div>
    </main>
</body>

</html>