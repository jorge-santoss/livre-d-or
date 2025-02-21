<?php
session_start();
session_destroy(); // Supprime la session
header("Location: "); // Redirige vers la page d'accueil
exit();
?>