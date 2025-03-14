<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$user_id = $_SESSION['user_id'];

include '../class/auth.php';
include '../utils/config.php';
include '../class/comment.php';

$commentObj = new Comment($pdo);
$message = '';

// Vérifier si l'ID du commentaire est passé dans l'URL
if (!isset($_GET['edit']) || empty($_GET['edit'])) {
    // Tu peux rediriger ou afficher un message d'erreur ici
    exit;
}

$comment_id = $_GET['edit'];

// Récupérer les informations du commentaire
$comment_data = $commentObj->getCommentById($comment_id);

if (!$comment_data || $comment_data['user_id'] !== $user_id) {
    // Tu peux rediriger ou afficher un message d'erreur ici
    exit;
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_comment = isset($_POST['comment']) ? $commentObj->testInput($_POST['comment']) : '';

    if (!empty($updated_comment)) {
        if ($commentObj->updateComment($comment_id, $updated_comment)) {
            header('Location: ../index.php');
            exit();
        } else {
            $message = "Erreur lors de la mise à jour du commentaire.";
        }
    } else {
        $message = "Le commentaire ne peut pas être vide.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://kit.fontawesome.com/ca3234fc7d.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Dancing+Script:wght@400..700
    <title>Modifier Commentaire</title>
    <link rel="stylesheet" href="../css/commentaire.css">
    <script>
        function openPopup(message) {
            document.getElementById('popup_message').innerText = message;
            document.getElementById('popup').style.display = 'block';
        }
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</head>
<body>
<header>
        <div class="navbar">
            
            <ul class="links">
                <li><a href="../index.php"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="./modif.php"><i class="fa-solid fa-user-pen"></i></a></li>
                <li><a href="./livre-or.php"><i class="fa-solid fa-book"></i></a></li>
                <li>
    <a href="<?php echo isset($_SESSION['user_id']) ? 'commentaire.php' : './connexion.php'; ?>">
        <i class="fa-solid fa-pen"></i>
    </a>
</li>

            </ul>
           
            <div class="buttons">
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null): ?>
        <a href="../class/deconnexion.php" class="action-connexion">Déconnexion</a>
    <?php else: ?>
        <a href="./pages/connexion.php" class="action-connexion">Connexion</a>
    <?php endif; ?>
</div>

            
            <div class="burger-menu-button">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
        <div class="burger-menu open">
            <ul class="links">
                <li><a href="../index.php"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="modif.php"><i class="fa-solid fa-user-pen"></i></a></li>
                <li><a href="livre-or.php"><i class="fa-solid fa-book"></i></a></li>
                <li>
    <a href="<?php echo isset($_SESSION['user_id']) ? 'commentaire.php' : './pages/connexion.php'; ?>">
        <i class="fa-solid fa-pen"></i>
    </a>
</li>
                <div class="divider"></div>
                <div class="buttons-burger-menu">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null): ?>
        <a href="../class/deconnexion.php" class="action-connexion">Déconnexion</a>
    <?php else: ?>
        <a href="./pages/connexion.php" class="action-connexion">Connexion</a>
    <?php endif; ?>
                       
                    </a>
                   
                </div>
            </ul>
        </div>
    </header>
    <script>
        const burgerMenuButton = document.querySelector('.burger-menu-button ')
        const burgerMenuButtonIcon = document.querySelector('.burger-menu-button i')
        const burgerMenu = document.querySelector('.burger-menu')

        burgerMenuButton.onclick = function () {
            burgerMenu.classList.toggle('open')
            const isOpen = burgerMenu.classList.contains('open')
            burgerMenuButtonIcon.classList = isOpen ? 'fa-solid fa-x' : 'fa-solid fa-bars'
        }
    </script>

<p class="livre_title">Modifier votre commentaire</p>
<label for="comment" class="mini-label">Commentaire :</label>
<form action="" method="post">
    <div class="comment_container">

        <div class="comment_card">
            
            <div class="table_comment">
                
                <textarea class="box" id="comment" name="comment" cols="50" rows="10"><?php echo htmlentities($comment_data['comment']); ?></textarea>
                <br>
                <input type="submit" value="Mettre à jour" class="button_connect">
            </div>
        </div>
    </div>
</form>

<div id="popup" class="popup" onclick="closePopup()">
    <div class="popup_content">
        <p id="popup_message" class="popup_text"></p>
        <button onclick="closePopup()">Fermer</button>
    </div>
</div>

<?php if (!empty($message)) echo "<script>openPopup('$message');</script>"; ?>

</body>
</html>
