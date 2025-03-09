<?php
$pdo = new PDO("mysql:host=localhost;dbname=livreor;charset=utf8","root","");

if (!empty($_GET['itemsPerPage']) && $_GET['itemsPerPage']>1) {$commentsPerPage = $_GET['itemsPerPage'];} else {$commentsPerPage = 5;}

// Obtenir le nombre total de commentaires
$totalCommentsQuery = "SELECT COUNT(*) as total FROM comments";
$stmt = $pdo->query($totalCommentsQuery);
$totalComments = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalComments / $commentsPerPage);

// Obtenir la page actuelle
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculer l'offset pour la requête SQL
$offset = ($currentPage - 1) * $commentsPerPage;

// POST  $_POST['tri par']     $_POST['tri']
// if (empty($_POST['tri par'])) {
//     $triPar = `date`;
// } else {
//     $triPar = $_POST['tri par'];
// }

// Récupérer les commentaires pour la page actuelle
$
if ($_GET['tri'] = 'asc' ) {
    $commentsQuery = "SELECT * FROM comments 
    INNER JOIN user ON comments.user_id = user.id 
    ORDER BY `date` ASC
    LIMIT :offset, :commentsPerPage";
// } else if ($_POST['tri'] = 'asc') {
//  } else if ($_POST['tri'] = 'asc') {
} else {
    $commentsQuery = "SELECT * FROM comments 
    INNER JOIN user ON comments.user_id = user.id 
    ORDER BY `date` DESC
    LIMIT :offset, :commentsPerPage";
}
$stmt = $pdo->prepare($commentsQuery);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':commentsPerPage', $commentsPerPage, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Livre d'or</title>
        <link rel="shortcut icon" href="./Images/favicon.ico" type="image/x-icon">
        <meta name="description" content="Bonjour, Je m\'appelle Raïs Mahjoub et ceci est mon CV. N\'hesitez pas à venir me découvrir !">
        <meta name="robots" content="index, follow">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&family=Press+Start+2P&display=swap" rel="stylesheet">
    </head>
    <body>
    <header>
        <div class="navbar">
            
            <ul class="links">
                <li><a href="./index.php"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="./pages/modif.php"><i class="fa-solid fa-user-pen"></i></a></li>
                <li><a href="./pages/livre-or.php"><i class="fa-solid fa-book"></i></a></li>
                <li>
    <a href="<?php echo isset($_SESSION['user_id']) ? 'ajouter_commentaire.php' : 'connexion.php'; ?>">
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
                <li><a href="./pages/commentaire.php"></i></a></li>
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
        <nav>
                <form action="search.php" method="get">
                    <input type="text" name="search" placeholder="Rechercher...">
                    <input type="submit">
                </form>
            </nav> 
            <form action="livre-or.php" method="get">
                Trier :
                <select class="tri" name="tri">
                    <option value="desc">Du plus récent au plus ancient</option>
                    <option value="asc" <?php if($ordre=='asc') echo 'selected'; ?>>Du plus ancient au plus récent</option>
                </select>
                
                Nomdre de commentaires à afficher par page :
                <input type="number" name="itemsPerPage" class="nbe-comment" value=<?= $commentsPerPage ?>>
                <input type="submit" value="Trier">
            </form>
<?php 
// Afficher les commentaires
foreach ($comments as $comment) {
    $date = new DateTime($comment['date']);
    echo "<div class='comment'><p class='user-date'><span class='user'>{$comment['nom']}  </span><span class='date'>le ".date_format($date,'j/n/o à G:i:s')."</span></p>
    <p class='comment-txt'>{$comment['comment']}</p></div>";
}

// Afficher les liens de pagination
echo "<div class='buttons'>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='?page=$i&amp;itemsPerPage=$commentsPerPage&amp;tri=$ordre'><button>$i</button></a> ";
} ?>
</div>
        </main>
        <footer>&copy; </footer>
    </body>
</html> 