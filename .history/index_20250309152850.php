
<?php 
session_start(); // Toujours
include("./utils/config2.php");
include("./class/récent.php");

$commentObj = new Comment($pdo);
$comments = $commentObj->getAllComments();

?>

<?php


if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = null; // Définit la variable si elle n'existe pas encore
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://kit.fontawesome.com/ca3234fc7d.js" crossorigin="anonymous"></script>
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <title>Index</title>
</head>

<body>
<header>
        <div class="navbar">
            
            <ul class="links">
                <li><a href="./index.php"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="./pages/modif.php"><i class="fa-solid fa-user-pen"></i></a></li>
                <li><a href="./pages/livre-or.php"><i class="fa-solid fa-book"></i></a></li>
                <li>
    <a href="<?php echo isset($_SESSION['user_id']) ? './pages/ajouter_commentaire.php' : './pages/connexion.php'; ?>">
        <i class="fa-solid fa-pen"></i>
    </a>
</li>

            </ul>
           
            <div class="buttons">
    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null): ?>
        <a href="./class/deconnexion.php" class="action-connexion">Déconnexion</a>
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
                <li><a href="./index.php"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="./pages/modif.php"><i class="fa-solid fa-user-pen"></i></a></li>
                <li><a href="./pages/livre-or.php"><i class="fa-solid fa-book"></i></a></li>
                <li>
    <a href="<?php echo isset($_SESSION['user_id']) ? './pages/ajouter_commentaire.php' : './pages/connexion.php'; ?>">
        <i class="fa-solid fa-pen"></i>
    </a>
</li>
                <div class="divider"></div>
                <div class="buttons-burger-menu">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null): ?>
        <a href="./class/deconnexion.php" class="action-connexion">Déconnexion</a>
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
<main>
    <div class="page">
    <div class="intro">
        <br>
        <br>
            <h2>Bienvenue sur notre Livre d'Or !</h2>
            
            <br>
            <h2>Laissez une trace de votre passage en écrivant un message.</h2>
            <h2> Découvrez les témoignages des visiteurs et partagez votre expérience !</h2>
            </div>
            <div class="boxe">
            <div class="book_card">
                <form action="./pages/livre-or.php">
                    <input type="submit"  value="Livre d'or" class="btn" style="height:50px; width:120px" >
                </form>
            <form action="<?php echo isset($_SESSION['user_id']) ? './pages/ajouter_commentaire.php' : './pages/connexion.php'; ?>" method="post">
    <input type="submit" value="Ajouté un commentaire" class="btn" style="height:50px; width:120px">
</form>

            </div>
        </div>
        <div class="recent">
<H2>témoignages récent</H2>
<br>
        <?php foreach ($comments as $comment_récent): ?>
    <li class="comment_item">
        <h3><?php echo htmlspecialchars($comment_récent['comment']); ?></h3>
        
        <p>-<?php echo htmlspecialchars($comment_récent['nom']); ?></p>
    </li>
<?php endforeach; ?>


        </div>
            </div>
       
</main>


</body>
</html>