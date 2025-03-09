<?php
session_start();
include '../utils/config.php';
include '../class/Validator.php';
include '../class/user.php';

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
            $message .= "Le nom d'utilisateur est requis.";
        } elseif (!Validator::validateUsername($nom)) {
            $message .= "Seuls les lettres, les chiffres et les traits de soulignement sont autorisés pour le nom d'utilisateur et il doit contenir au maximum 10 caractères.";
        }

        // Validate email
        if (empty($email)) {
            $message .= "L'email est requis.";
        } elseif (!Validator::validateEmail($email)) {
            $message .= "Format d'identifiant email non valide.";
        }

        // Validate password
        if (empty($password)) {
            $message .= "Le mot de passe est requis.";
        } elseif (!Validator::validatePassword($password)) {
            $message .= "Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.";
        }

        // Confirm password match
        if ($password !== $confirm_password) {
            $message .= "Les mots de passe ne correspondent pas.";
        }

        if (empty($message)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $user = new User($pdo);
        
            if ($user->userExists($nom, $email)) {
                echo "<script>alert('L\'utilisateur ou l\'email existe déjà.'); window.location.href = 'inscription.php';</script>";
                exit();
            } else {
                $user->createUser($nom, $email, $password_hashed);
                echo "<script>alert('Inscription réussie !'); window.location.href = 'connexion.php';</script>";
                exit();
            }

        }
           
    }
    var_dump()
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>signup</title>
    <link rel="stylesheet" href="../css/guestbook.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Dancing+Script:wght@400..700&family=Press+Start+2P&family=Quattrocento+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <script>
        function openPopup(message) {
            document.getElementById('popup_message').innerText = message;
            document.getElementById('popup').style.display = 'inline-block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</head>

<body>
    <main>
        <p class="livre_title">Livre D'Or</p>
        <form action="inscription.php" method="post">
            <div class="inscription_card">
                <div class="form_card">
                    <div class="livre_form">
                        <div class="livre_card">
                            <div class="text_area">
                                <div class="tittle">
                                    <h2>Inscription</h2>
                                </div>

                                <label for="Nom"></label>
                                <input type="text" id="nom" name="nom" placeholder="nom"><br>

                                <label for="email"></label>
                                <input type="email" id="email" name="email" placeholder="Email"><br>

                                <label for="password"></label>
                                <input type="password" id="password" name="password" placeholder="Mot de passe"><br>

                                <label for="confirm_password"></label>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm mot de passe">
                                <span id='message'></span>


                                <input type="submit" value="S'inscrire" class="button_inscription">

                                <p class="connexion"> "Avez vous déjà un compte?"
                                    <br><a href="./connexion.php">Connexion</a>
                                </p>
                            </div>
                        </div>
                        <div class="login_img1">
                            <img src="../images/stylo.jpg" alt="pen_img" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="popup" class="popup" onclick="closePopup()">
            <div class="popup_content">
                <p id="popup_message" class="popup_text"></p>
                <button onclick="closePopup()">Close</button>
            </div>
        </div>

        
    </main>
    <div class="messageErr">
            <?php
            if (!empty($message)) {
                echo "<script>openPopup('$message');</script>";
            }
            ?>
        </div>
</body>

</html>