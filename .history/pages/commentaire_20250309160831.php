<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo "User is not logged in.";
    exit(); // Empêche d'afficher le formulaire si l'utilisateur n'est pas connecté
}

echo "User is logged in. User ID: " . $_SESSION['id'];

include '../utils/config.php';
include '../class/comment.php';

$message = '';
$commentObj = new Comment($pdo);

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id']; // Récupère l'ID de l'utilisateur connecté
    $comment = isset($_POST['comment']) ? $commentObj->testInput($_POST['comment']) : '';
    $date = date('Y-m-d H:i:s'); // Date actuelle

    // Débogage des variables
    var_dump($user_id); // Vérifie l'ID de l'utilisateur
    var_dump($comment); // Vérifie le commentaire
    var_dump($date); // Vérifie la date

    // Vérification des champs
    if (empty($user_id)) {
        $message = 'L\'ID de l\'utilisateur est manquant.';
    } elseif (empty($comment)) {
        $message = 'Le champ commentaire est vide.';
    } elseif (empty($date)) {
        $message = 'La date est manquante.';
    } else {
        if ($commentObj->addComment($user_id, $comment, $date)) {
            header('Location: ../index.php');
            exit();
        } else {
            $message = 'Erreur lors de l\'insertion du commentaire.';
        }
    }
    
        } else {
            $message = 'Erreur lors de l\'insertion du commentaire.';
        }
     else {
        $message = 'Au moins un des champs est vide.';
    }


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
    <link rel="stylesheet" href="../css/guestbook.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Dancing+Script:wght@400..700&family=Press+Start+2P&family=Quattrocento+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
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
    <p class="livre_title">Livre d'Or</p>
    <form action="commentaire.php" method="post">
        <div class="comment_container">
            <div class="comment_card">
                <div class="table_card">
                    <div class="table_comment">
                        <div class="text_comment">
                            <h3 class="title_livre">Commentaire</h3>

                            <div>
                                <label for="comment">Commentaire :</label>
                                <textarea class="box" id="comment" name="comment" cols="50" rows="10"><?php if (isset($_POST['comment'])) echo htmlentities(trim($_POST['comment'])); ?></textarea>
                            </div>
                            <br>
                            <div>
                                <input type="submit" name="go" value="Signer" class="button_connect">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment_img">
                    <img src="../images/stylo.jpg" alt="pen_img" width="100%">
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

    <div class="messageErr_comment">
        <?php
        if (!empty($message)) {
            echo "<script>openPopup('$message');</script>";
        }
        ?>
    </div>
</body>

</html>
