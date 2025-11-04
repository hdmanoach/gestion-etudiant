<?php
session_start(); // Démarrer la session

require_once 'include/connect.php';
require_once 'include/log_action.php'; // 🔹 Pour enregistrer dans la table log

if (isset($_SESSION['username'])) {
    // 🔹 Journaliser la déconnexion de l’utilisateur
    logAction('deconnexion', "L'utilisateur {$_SESSION['username']} s'est déconnecté.", $_SESSION['user_id'] ?? null);
}

// 🔹 Nettoyage de la session
session_unset();
session_destroy();

// 🔹 Redirection vers la page de connexion
header("Location: index.php?page=login");
exit;
?>
