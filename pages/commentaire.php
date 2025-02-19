<?php
include 'config.php';

$message = '';

// Fonction de validation des entrées
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['go']) && $_POST['go'] == 'Signer') {
    $id_user = test_input($_POST['id_user']);
    $comment = test_input($_POST['comment']);
    $date = test_input($_POST['date']);

    // Vérification des champs
    if (!empty($id_user) && !empty($comment) && !empty($date)) {
        // Connexion à la base de données
        $conn = new mysqli('localhost', 'root', '', 'livreor');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Préparation et exécution de la requête
        $stmt = $conn->prepare("INSERT INTO comment (id_user, comment, date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $id_user, $comment, $date);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $message = 'Erreur lors de l\'insertion du commentaire.';
        }

        // Fermeture de la connexion
        $stmt->close();
        $conn->close();
    } else {
        $message = 'Au moins un des champs est vide.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Dancing+Script:wght@400..700&family=Press+Start+2P&family=Quattrocento+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>
<p class="livre_title">Livre d'Or</p>
    <form action="commentaire.php" method="post">
        <main class="comment_card">
        <section class="table_card">
            <div class="table_comment">
                <h3 class="title_livre">Commentaire</h3>
                <div>
                    <label for="date">Date :</label>
                    <input type="text" id="date" name="date" maxlength="50" size="15" value="<?php if (isset($_POST['date'])) echo htmlentities(trim($_POST['date'])); ?>">
                </div>
                <br>
                <div>
                    <label for="id_user">Nom :</label>
                    <input type="text" id="id_user" name="id_user" maxlength="30" size="47" value="<?php if (isset($_POST['id_user'])) echo htmlentities(trim($_POST['id_user'])); ?>">
                </div>
            <br>
                <div>
                    <label for="comment">Commentaire :</label>
                    <textarea id="comment" name="comment" cols="50" rows="10"><?php if (isset($_POST['comment'])) echo htmlentities(trim($_POST['comment'])); ?></textarea>
                </div>
                <br>
                <div>
                    <input type="submit" name="go" value="Signer">
                </div>
                <?php if (!empty($message)) echo '<p>' . $message . '</p>'; ?>
            </div>
        </section>
        </main>
    </form>
</body>
</html>
