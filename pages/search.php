<?php
$pdo = new PDO("mysql:host=localhost;dbname=livreor;charset=utf8","root","");

/* if (!empty($_GET['itemsPerPage']) && $_GET['itemsPerPage']>1) {$commentsPerPage = $_GET['itemsPerPage'];} else {$commentsPerPage = 5;}
$totalCommentsQuery = "SELECT COUNT(*) as total FROM comments";
$stmt = $pdo->query($totalCommentsQuery);
$totalComments = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalComments / $commentsPerPage);
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentPage - 1) * $commentsPerPage; */

$search = '%'.$_GET['search'].'%';
$query = "SELECT * FROM comments 
INNER JOIN user ON comments.user_id = user.id 
WHERE comment LIKE :search";
// LIMIT :offset, :commentsPerPage";
$stmt = $pdo->prepare($query);

$stmt->bindParam(":search", $search, PDO::PARAM_STR); 
// $stmt->bindParam(":offset", $offset, PDO::PARAM_INT); 
// $stmt->bindParam(":commentsPerPage", $commentsPerPage, PDO::PARAM_INT);



$stmt->execute();
$result = $stmt->fetchAll();?>

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
            <!-- <h1></h1> -->
            <nav>
                <INPUT TYPE="button" VALUE="RETOUR" onClick="history.back();">
                <form action="search.php" method="get">
                    <input type="text" name="search" placeholder="Rechercher...">
                    <input type="submit">
                </form>
            </nav> 
        </header>
        <main>
            <?php if (!empty($result)) {
                foreach ($result as $comment) {
                    $date = new DateTime($comment['date']);
                    echo "<div class='comment'><p class='user-date'><span class='user'>{$comment['nom']}  </span><span class='date'>le ".date_format($date,'j/n/o à G:i:s')."</span></p>
                    <p class='comment-txt'>{$comment['comment']}</p></div>";
                }
            } else {
                echo "Aucun resultat n'a été trouvé.";
            }

            // echo "<div class='buttons'>";
            // for ($i = 1; $i <= $totalPages; $i++) {
            //     echo "<a href='?search=$search&amp;page=$i&amp;itemsPerPage=$commentsPerPage'><button>$i</button></a> "; }
            ?>
        </main>
        <footer>&copy; </footer>
    </body>
</html> 

