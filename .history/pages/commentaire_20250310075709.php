<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo "User is not logged in.";
    exit(); // Empêche d'afficher le formulaire si l'utilisateur n'est pas connecté
}

include '../utils/config.php';
include '../class/comment.php';

$message = '';
$commentObj = new Comment($pdo);

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id']; // Récupère l'ID de l'utilisateur connecté
    $comment = isset($_POST['comment']) ? $commentObj->testInput($_POST['comment']) : '';
    $date = date('Y-m-d H:i:s'); // Date actuelle

    // Vérification des champs
    if (!empty($user_id) && !empty($comment) && !empty($date)) {
        if ($commentObj->addComment($user_id, $comment, $date)) {
            header('Location: ../index.php');
            exit();
        } else {
            $message = 'Erreur lors de l\'insertion du commentaire.';
        }
    } else {
        $message = 'Au moins un des champs est vide.';
    }
}

// Récupérer tous les commentaires pour affichage
// La méthode getComments() doit renvoyer un tableau associatif contenant
// au minimum : id, user_id, username, comment et date.
$comments = $commentObj->getComments();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
    <link rel="stylesheet" href="../css/commentaire.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://kit.fontawesome.com/ca3234fc7d.js" crossorigin="anonymous"></script>
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
<header>
    <!-- Barre de navigation -->
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
                <a href="./connexion.php" class="action-connexion">Connexion</a>
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
                <a href="<?php echo isset($_SESSION['user_id']) ? 'commentaire.php' : './pconnexion.php'; ?>">
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
            </div>
        </ul>
    </div>
</header>
<script>
    const burgerMenuButton = document.querySelector('.burger-menu-button');
    const burgerMenuButtonIcon = document.querySelector('.burger-menu-button i');
    const burgerMenu = document.querySelector('.burger-menu');

    burgerMenuButton.onclick = function () {
        burgerMenu.classList.toggle('open');
        const isOpen = burgerMenu.classList.contains('open');
        burgerMenuButtonIcon.classList = isOpen ? 'fa-solid fa-x' : 'fa-solid fa-bars';
    }
</script>

<p class="livre_title">Livre d'Or</p>

<!-- Formulaire pour ajouter un commentaire -->
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

<!-- Affichage de la liste des commentaires -->
<div class="comments_list">
    <h2>Commentaires existants</h2>
    <table>
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Commentaire</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($comments as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['comment'])); ?></td>
                <td><?php echo $row['date']; ?></td>
                <td>
                    <?php
                    // Affichage du bouton de modification :
                    // L'admin peut modifier tous les commentaires,
                    // L'utilisateur peut modifier uniquement ses propres commentaires.
                    if ($_SESSION['role'] === 'admin' || ($_SESSION['role'] === 'utilisateur' && $row['user_id'] == $_SESSION['id'])) {
                        echo '<a href="update_comment.php?edit=' . $row['id'] . '" class="btn">
                                <i class="fas fa-edit"></i> Modifier 
                              </a>';
                    }
                    
                    // Affichage du bouton de suppression :
                    // L'admin et le modérateur peuvent supprimer n'importe quel commentaire,
                    // L'utilisateur peut supprimer uniquement le sien.
                    if ($_SESSION['role'] === 'admin' ||
                        $_SESSION['role'] === 'moderateur' ||
                        ($_SESSION['role'] === 'utilisateur' && $row['user_id'] == $_SESSION['id'])) {
                        echo '<a href="delete_comment.php?delete=' . $row['id'] . '" class="btn" onclick="return confirm(\'Voulez-vous vraiment supprimer ce commentaire ?\');">
                                <i class="fas fa-trash"></i> Supprimer 
                              </a>';
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

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
