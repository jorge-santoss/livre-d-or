<?php
session_start();

include '../utils/config.php';
include '../class/auth.php';

$message = '';
$auth = new Auth($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nom']) && isset($_POST['password'])) {
        $nom = $_POST['nom'];
        $password = $_POST['password'];

        // Attempt to login

        // if ($auth->login($nom, $password)) {
        //     echo "Login successful. User ID: " . $_SESSION['user_id']; // Debugging
        //     header('Location: commentaire.php'); // Ensure no output before this line
        //     exit();



        if ($auth->login($nom, $password)) {
            header('Location: ./.php');
            exit();
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
                            <label for="nom"></label>
                            <input type="text" id="nom" name="nom" placeholder="Nom"><br>
                            <label for="password"></label>
                            <input type="password" id="password" name="password" placeholder="Password"><br>
                            <input type="submit" value="Se connecter" class="button_connect">
                            <p class="inscription"> "Vous n'avez pas de compte?"
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
        <div id="popup" class="popup" onclick="closePopup()">
            <div class="popup_content">
                <p id="popup_message" class="popup_text"></p>
                <button onclick="closePopup()">Close</button>
            </div>
        </div>

        <div class="message">
        <?php
        if (!empty($message)) {
            echo "<script>openPopup('$message');</script>";
        }
        ?>
        </div>
    </main>
</body>
</html>
