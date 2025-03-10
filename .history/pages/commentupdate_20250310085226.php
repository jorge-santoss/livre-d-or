<?php
session_start();
var_dump($_SESSION); // Debugging: Check if 'user_id' exists

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
        </ul>
    </div>
</header>

<p class="livre_title">Modifier votre commentaire</p>
<form action="" method="post">
    <div class="comment_container">
    <label for="comment class=m>Commentaire :</label>
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
