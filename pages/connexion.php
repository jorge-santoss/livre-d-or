<?php
session_start();
include '../utils/config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nom']) && isset($_POST['password'])) {
        $nom = htmlspecialchars($_POST['nom']);
        $password = $_POST['password'];

        // Vérifier si l'utilisateur existe
        $sql = "SELECT * FROM user WHERE nom = :nom";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom' => $nom]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
        } else {
            $message = "Mauvais identifiants";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>login</title>
    <link rel="stylesheet" href="../css/conect.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Dancing+Script:wght@400..700&family=Press+Start+2P&family=Quattrocento+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <main>
        <p class="livre_title">Livre d'Or</p>
        <form action="connexion.php" method="post">
            <div class="connexion_card">
                <div class="formulaire_card">
                    <div class="container_card">
                        <div class="text_area2">
                            <div class="tittle">
                                <h2>Connexion</h2>
                            </div>
                            <label for="username"></label>
                            <input type="text" id="username" name="username" placeholder="Nom"><br>
                            <label for="password"></label>
                            <input type="password" id="password" name="password" placeholder="Password"><br>
                            <form action="dashboard.php">
                                <input type="submit" value="Se connecter" class="button_connect">
                                <p class="inscription"> "Vous n'avez pas de compte"
                                    <br><a href="inscription.php">Inscription</a>
                                </p>
                        </div>
                    </div>
                    <div class="login_img">
                        <img src="../images/stylo.jpg" alt="pen_img" width="100%">
                    </div>
                </div>
            </div>
        </form>
        <?php if ($message) echo "<p>$message</p>"; ?>

    </main>
</body>

</html>