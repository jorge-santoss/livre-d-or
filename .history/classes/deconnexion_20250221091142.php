<?php
session_start();
session_destroy(); // Supprime la session
header("Location: index.php"); // Redirige vers la page d'accueil
exit();
