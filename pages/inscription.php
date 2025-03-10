<?php
require_once '../utils/config.php';
require_once '../class/validator.php';
require_once '../class/user.php';

$message = '';
$nom = '';
$email = ''; // Initialise les variables pour éviter les erreurs.

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom             = isset($_POST['nom']) ? Validator::testInput($_POST['nom']) : '';
    $email           = isset($_POST['email']) ? Validator::testInput($_POST['email']) : '';
    $password        = isset($_POST['password']) ? Validator::testInput($_POST['password']) : '';
    $confirm_password= isset($_POST['confirm_password']) ? Validator::testInput($_POST['confirm_password']) : '';

    // Validation du nom d'utilisateur
    if (empty($nom)) {
        $message .= "Le nom d'utilisateur est requis. ";
    } elseif (!Validator::validateUsername($nom)) {
        $message .= "Seuls les lettres, les chiffres et les traits de soulignement sont autorisés et le nom doit contenir au maximum 10 caractères. ";
    }

    // Validation de l'email
    if (empty($email)) {
        $message .= "L'email est requis. ";
    } elseif (!Validator::validateEmail($email)) {
        $message .= "Format d'email non valide. ";
    }

    // Validation du mot de passe
    if (empty($password)) {
        $message .= "Le mot de passe est requis. ";
    } elseif (!Validator::validatePassword($password)) {
        $message .= "Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial. ";
    }

    // Vérification de la correspondance des mots de passe
    if ($password !== $confirm_password) {
        $message .= "Les mots de passe ne correspondent pas. ";
    }

    // Si aucune erreur, procéder à l'inscription
    if (empty($message)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($pdo);

        if ($user->userExists($nom, $email)) {
            $message = "L'utilisateur ou l'email existe déjà.";
        } else {
            $user->createUser($nom, $email, $password_hashed);
            $message = "Inscription réussie ! Redirection en cours...";
            echo "<script>openPopup('".$message."');</script>";
            header("Location: ./connexion.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/commentair.css">
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
        <form action="./inscription.php" method="post">
            <div class="inscription_card">
                <div class="form_card">
                    <div class="livre_form">
                        <div class="livre_card">
                            <div class="text_area">
                                <div class="tittle">
                                    <h2>Inscription</h2>
                                </div>

                                <input type="text" id="nom" name="nom" placeholder="Nom" value="<?php echo htmlspecialchars($nom); ?>"><br>
                                <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>"><br>
                                <input type="password" id="password" name="password" placeholder="Mot de passe"><br>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe">
                                <span id="message"></span>

                                <input type="submit" value="S'inscrire" class="button_inscription">

                                <p class="connexion">
                                    Avez-vous déjà un compte ?<br>
                                    <a href="./connexion.php">Connexion</a>
                                </p>
                            </div>
                        </div>
                        <div class="login_img1">
                            <img src="../images/stylo.jpg" alt="Image de stylo" width="100%">
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
    <?php if (!empty($message)) { ?>
        <script>openPopup("<?php echo addslashes($message); ?>");</script>
    <?php } ?>
</body>
</html>
