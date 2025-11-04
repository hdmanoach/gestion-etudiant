<?php
    /*
        session.php
        Gère la session utilisateur et 
        redirige vers la page de connexion si l'utilisateur n'est pas authentifié.

        Ce fichier protège les pages nécessitant une authentification.
    */
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
}
?>
