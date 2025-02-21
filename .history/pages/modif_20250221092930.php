<?php
session_start();
include '../utils/config2.php';
include '../classes/validator.php';
include '../classes/userupd.php';

$message = '';


// Vérifier que l'utilisateur est connecté

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = null; // Définit la variable si elle n'existe pas encore
}

var_dump($_SESSION); // Teste si la session contient bien "user_id"

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Définition correcte de $user_id
$user_id = $_SESSION['user_id'];

$userObj = new User($pdo, $user_id);


if (!$userObj->getIsOK()) {
    $message = "Utilisateur non trouvé.";
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nom']) && isset($_POST['email'])) {
        $nom = Validator::testInput($_POST['nom']);
        $email = Validator::testInput($_POST['email']);

        // Validation du nom
        if (empty($nom)) {
            $message .= "Le nom d'utilisateur est requis.<br>";
        } elseif (!Validator::validateUsername($nom)) {
            $message .= "Seuls les lettres, les chiffres et les traits de soulignement sont autorisés pour le nom d'utilisateur et il doit contenir au maximum 10 caractères.<br>";
        }

        // Validation de l'email
        if (empty($email)) {
            $message .= "L'email est requis.<br>";
        } elseif (!Validator::validateEmail($email)) {
            $message .= "Format d'identifiant email non valide.<br>";
        }

        // Gestion de la modification du mot de passe 
        // Si l'utilisateur souhaite changer son mot de passe, il doit fournir le mot de passe actuel, le nouveau et la confirmation
        $updatePassword = false;
        if (!empty($_POST['old_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_new_password'])) {
            // Vérifier que tous les champs relatifs au mot de passe sont renseignés
            if (empty($_POST['old_password'])) {
                $message .= "Le mot de passe actuel est requis pour changer le mot de passe.<br>";
            } else {
                $old_password = Validator::testInput($_POST['old_password']);
                // Vérifier que le mot de passe actuel correspond à celui de la base de données
                if (!password_verify($old_password, $userObj->getPassword())) {
                    $message .= "Le mot de passe actuel est incorrect.<br>";
                }
            }

            if (empty($_POST['new_password'])) {
                $message .= "Le nouveau mot de passe est requis.<br>";
            } else {
                $new_password = Validator::testInput($_POST['new_password']);
                if (!Validator::validatePassword($new_password)) {
                    $message .= "Le nouveau mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.<br>";
                }
            }

            if (empty($_POST['confirm_new_password'])) {
                $message .= "La confirmation du nouveau mot de passe est requise.<br>";
            } else {
                $confirm_new_password = Validator::testInput($_POST['confirm_new_password']);
                if (isset($new_password) && $new_password !== $confirm_new_password) {
                    $message .= "Les nouveaux mots de passe ne correspondent pas.<br>";
                }
            }

            if (empty($message)) {
                $updatePassword = true;
            }
        }

        // Si aucune erreur, procéder à la mise à jour
        if (empty($message)) {
            // Vérifier si le nom ou l'email est déjà utilisé par un autre utilisateur
            if ($userObj->userExistsExcludingCurrent($nom, $email, $user_id)) {
                $message = "Le nom d'utilisateur ou l'email existe déjà.";
            } else {
                // Préparer le mot de passe à enregistrer : soit le nouveau (haché), soit conserver l'ancien
                if ($updatePassword) {
                    $password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                } else {
                    $password_hashed = $userObj->getPassword();
                }
                // Mettre à jour l'utilisateur
                $userObj->updateUser($user_id, $nom, $email, $password_hashed);
                $message = "Mise à jour réussie !";
                // Rafraîchir les données de l'utilisateur
                $currentUser = [
                    'nom' => $userObj->getUsername(),
                    'email' => $userObj->getMail(),
                    'password' => $userObj->getPassword()
                ];
                

      
                
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/ind.css">
    <script src="https://kit.fontawesome.com/ca3234fc7d.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <title>Modification du Profil</title>
</head>
<body>
<header>
    <div class="navbar">
        <ul class="links">
            <li><a href="../index.php"><i class="fa-solid fa-house"></i></a></li>
            <li><a href="modif.php"><i class="fa-solid fa-user-pen"></i></a></li>
            <li><a href="#"><i class="fa-solid fa-book"></i></a></li>
            <li><a href="#"><i class="fa-solid fa-pen"></i></a></li>
        </ul>
        <div class="box">
            <a href="#">
                <input type="search" placeholder="search...">
                <i class="fa-solid fa-magnifying-glass"></i>
            </a>
        </div>
        <div class="buttons">

        <a href="../classes/deconnexion.php" class="action-connexion">Déconnexion</a>
   
</div>
        <div class="burger-menu-button">
            <i class="fa-solid fa-bars"></i>
        </div>
    </div>
    <div class="burger-menu open">
        <ul class="links">
            <li><a href="../index.php"><i class="fa-solid fa-house"></i></a></li>
            <li><a href="#"><i class="fa-solid fa-user-pen"></i></a></li>
            <li><a href="#"><i class="fa-solid fa-book"></i></a></li>
            <li><a href="#"></a></li>
            <div class="divider"></div>
            <div class="buttons-burger-menu">
                <a href="#" class="action-button-user">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
        </ul>
    </div>
</header>
<script>
    const burgerMenuButton = document.querySelector('.burger-menu-button')
    const burgerMenuButtonIcon = document.querySelector('.burger-menu-button i')
    const burgerMenu = document.querySelector('.burger-menu')

    burgerMenuButton.onclick = function () {
        burgerMenu.classList.toggle('open')
        const isOpen = burgerMenu.classList.contains('open')
        burgerMenuButtonIcon.classList = isOpen ? 'fa-solid fa-x' : 'fa-solid fa-bars'
    }
</script>
<main>
    <p class="livre_title">Livre D'Or</p>
    <div class="livre_form">
        <div class="livre_card">
            <form action="modif.php" method="post">
                <div class="tittle">
                    <h2>Modification du profil</h2>
                </div>
                <div class="text_area">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Nom" value="<?php echo htmlspecialchars($userObj->getUsername()); ?>"><br>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($userObj->getMail()); ?>"><br>

                    <label for="old_password">Mot de passe actuel</label>
                    <input type="password" id="old_password" name="old_password" placeholder="Mot de passe actuel"><br>

                    <label for="new_password">Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Nouveau mot de passe"><br>

                    <label for="confirm_new_password">Confirmer nouveau mot de passe</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="Confirmer nouveau mot de passe">
                    <span id='message'></span>
                </div>
                <input type="submit" value="Mettre à jour" class="button_livre">
            </form>
        </div>
    </div>
    <div class="messageErr">
        <?php if ($message) echo "<p>$message</p>"; ?>
    </div>
</main>
</body>
</html>
