<?php
session_start();
include '../utils/config.php';
include '../class/auth.php';

$pdo = new PDO("mysql:host=localhost;dbname=livreor;charset=utf8", "root", "");

// Gestion de la suppression avant tout affichage
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete']; // Convertir en entier pour plus de sécurité
    
    // Vérifier si le commentaire existe et récupérer l'ID du propriétaire
    $checkQuery = "SELECT user_id FROM comments WHERE id = :id";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute(['id' => $deleteId]);
    $commentRow = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($commentRow) {
        $ownerId = $commentRow['user_id'];
        $currentUserRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'visiteur';
        $currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Vérifier les permissions : admin et moderateur peuvent tout supprimer,
        // l'utilisateur peut supprimer seulement ses propres commentaires
        if (
            $currentUserRole === 'admin' ||
            $currentUserRole === 'moderateur' ||
            ($currentUserRole === 'utilisateur' && $ownerId == $currentUserId)
        ) {
            $deleteCommentQuery = "DELETE FROM comments WHERE id = :id";
            $deleteStmt = $pdo->prepare($deleteCommentQuery);
            $deleteStmt->execute(['id' => $deleteId]);
        }
    }
    // Redirection pour éviter d'avoir ?delete= dans l'URL
    header("Location: livre-or.php");
    exit();
}

// Définir le nombre de commentaires par page
$commentsPerPage = isset($_GET['itemsPerPage']) && $_GET['itemsPerPage'] > 1 ? $_GET['itemsPerPage'] : 5;

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

// Récupérer l'ordre de tri
$ordre = isset($_GET['tri']) ? $_GET['tri'] : 'desc';

// Définir la requête SQL pour récupérer les commentaires
$commentsQuery = "SELECT comments.*, user.nom, user.id as user_id
                  FROM comments 
                  INNER JOIN user ON comments.user_id = user.id 
                  ORDER BY comments.date " . ($ordre === 'asc' ? 'ASC' : 'DESC') . " 
                  LIMIT :offset, :commentsPerPage";

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
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/ca3234fc7d.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
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
            <option value="desc">Du plus récent au plus ancien</option>
            <option value="asc" <?php if($ordre == 'asc') echo 'selected'; ?>>Du plus ancien au plus récent</option>
        </select>
        Nombre de commentaires à afficher par page :
        <input type="number" name="itemsPerPage" class="nbe-comment" value="<?= $commentsPerPage ?>" min="1">
        <input type="submit" value="Trier">
    </form>

    <?php 
    // Vérifier si l'utilisateur est connecté
    $currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $currentUserRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'visiteur';

    // Afficher les commentaires
    foreach ($comments as $comment) {
        $date = new DateTime($comment['date']);
        echo "<div class='comment'>
                <p class='user-date'>
                    <span class='user'>{$comment['nom']}</span>  
                    <span class='date'>le " . date_format($date, 'j/n/Y à G:i:s') . "</span>
                </p>
                <p class='comment-txt'>{$comment['comment']}</p>";
        
        // Affichage des boutons selon les permissions
        echo "<td>";
        if ($currentUserRole === 'admin') {
            echo '<a href="commentupdate.php?edit=' . $comment['id'] . '" class="btn">
                    <i class="fas fa-edit"></i> Modifier 
                  </a>';
            echo '<a href="livre-or.php?delete=' . $comment['id'] . '" class="btn" onclick="return confirm(\'Voulez-vous vraiment supprimer ce commentaire ?\');">
                    <i class="fas fa-trash"></i> Supprimer 
                  </a>';
        } elseif ($currentUserRole === 'utilisateur' && $comment['user_id'] == $currentUserId) {
            echo '<a href="commentupdate.php?edit=' . $comment['id'] . '" class="btn">
                    <i class="fas fa-edit"></i> Modifier 
                  </a>';
            echo '<a href="livre-or.php?delete=' . $comment['id'] . '" class="btn" onclick="return confirm(\'Voulez-vous vraiment supprimer ce commentaire ?\');">
                    <i class="fas fa-trash"></i> Supprimer 
                  </a>';
        } elseif ($currentUserRole === 'moderateur') {
            echo '<a href="livre-or.php?delete=' . $comment['id'] . '" class="btn" onclick="return confirm(\'Voulez-vous vraiment supprimer ce commentaire ?\');">
                    <i class="fas fa-trash"></i> Supprimer 
                  </a>';
        }
        echo "</td></div>";
    }

    // Afficher les liens de pagination
    echo "<div class='buttons'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i&amp;itemsPerPage=$commentsPerPage&amp;tri=$ordre'><button>$i</button></a> ";
    }
    echo "</div>";
    ?>
</main>

<footer>&copy; </footer>
</body>
</html>
