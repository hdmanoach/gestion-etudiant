<?php
// ================================
// index.php - fichier principal
// ================================

// 🔹 Inclure la session et démarrer
require_once 'include/session.php';
session_start();
require_once 'include/last_activity.php'; // Met à jour la dernière activité

// 🔹 Activer l'affichage des erreurs (dev)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔹 Buffer de sortie
ob_start();

// 🔹 Inclure le système de log d'actions
require_once 'include/log_action.php';

// 🔹 Déterminer la page actuelle
$page = $_GET["page"] ?? 'liste';

// 🔹 Vérification d'accès
$publicPages = ['login', 'logout']; // pages accessibles sans connexion
if (!isset($_SESSION['user_id']) && !in_array($page, $publicPages)) {
    header("Location: index.php?page=login");
    exit;
}

// 🔹 Définir le mode (notes / étudiants)
$notesPages = ['gestion_notes', 'enregistrer_note', 'enregistrer_notes', 'consulter_notes', 'actions'];
$_SESSION['mode'] = in_array($page, $notesPages) ? 'notes' : 'etudiants';

// 🔹 Définir le titre de la page
$title = match ($page) {
    "ajouter" => "Ajouter Étudiant",
    "inscrire" => "Inscrire Étudiant",
    "liste" => "Liste des Étudiants",
    "rechercher" => "Rechercher Étudiant",
    "inscriptions" => "Liste des Inscriptions",
    "supprimer" => "Supprimer Étudiant",
    "modifier" => "Modifier Étudiant",
    "user_dashboard" => "Tableau de Bord Utilisateur",
    "admin_dashboard" => "Tableau de Bord Admin",
    "gestion_notes" => "Gestion des Notes",
    "enregistrer_note" => "Enregistrer une Note",
    "enregistrer_notes" => "Enregistrer Plusieurs Notes",
    "consulter_notes" => "Consulter les Notes",
    "actions" => "Actions sur les Notes",
    "traiter_note" => "Traitement de la Note",
    "modifier_note" => "Modifier une Note",
    "supprimer_note" => "Supprimer une Note",
    "ajouter_user" => "Ajouter Utilisateur",
    "modifier_user" => "Modifier Utilisateur",
    "supprimer_user" => "Supprimer Utilisateur",
    "recherche_etudiant" => "Rechercher Etudiant",
    "logs" => "Logs Système",
    "fiche_etudiant" => "Fiche Étudiant",
    "login" => "Connexion",
    "logout" => "Déconnexion",
    default => "Accueil"
};

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        main.container {
            min-height: calc(100vh - 160px);
            background-color: #f8f9fa;
            padding-bottom: 20px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <?php include 'include/header.php'; ?>

    <!-- Contenu principal -->
    <main class="container mt-4">
        <?php
        // 🔹 Inclure la page correspondante
        switch ($page) {
            case "accueil":
                include 'index.php';
                break;
            case "ajouter":
            case "modifier":
                include 'include/add.php';
                break;
            case "inscrire":
                include 'include/inscription.php';
                break;
            case "liste":
                include 'include/liste.php';
                break;
            case "rechercher":
                include 'include/rechercher_etudiant.php';
                break;
            case "inscriptions":
                include 'include/liste_inscription.php';
                break;
            case "supprimer":
                include 'include/supprimer.php';
                break;
            case "login":
                include 'login.php';
                break;
            case "admin_dashboard":
                include 'include/admin_dashboard.php';
                break;
            case "user_dashboard":
                include 'include/user_dashboard.php';
                break;
            case "logout":
                include 'logout.php';
                break;
            case "enregistrer_note":
                include 'include/enregistrer_note.php';
                break;
            case "enregistrer_notes":
                include 'include/enregistrer_notes.php';
                break;
            case "actions":
                include 'include/actions.php';
                break;
            case "consulter_notes":
                include 'include/consulter_notes.php';
                break;
            case "traiter_note":
                include 'include/traiter_note.php';
                break;
            case "modifier_note":
                include 'include/modifier_note.php';
                break;
            case "supprimer_note":
                include 'include/supprimer_note.php';
                break;
            case "ajouter_user":
                include 'include/ajouter_user.php';
                break;
            case "modifier_user":
                include 'include/modifier_user.php';
                break;
            case "supprimer_user":
                include 'include/supprimer_user.php';
                break;
            case "logs":
                include 'include/logs.php';
                break;
            case "recherche_etudiant":
                include 'include/recherche_etudiant.php';
                break;
            case "fiche_etudiant":
                include 'include/fiche_etudiant.php';
                break;
            default:
                include 'include/liste.php';
        }
        ?>
    </main>

    <!-- Footer -->
    <?php include 'include/footer.php'; ?>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
ob_end_flush();
?>
