<?php
//session_start();
require_once 'connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifie que seul l’admin y a accès
if (($_SESSION['role'] ?? 0) != 1) {
    header('Location: index.php');
    exit;
}

// Vérifie que l’ID est bien présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur manquant.";
    header("Location: index.php?page=admin_dashboard");
    exit;
}

$id = intval($_GET['id']);

// Vérifie si l'utilisateur existe
$stmt = $connexion->prepare("SELECT username FROM utilisateur WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Utilisateur introuvable.";
    header("Location: index.php?page=admin_dashboard");
    exit;
}

// Supprimer l'utilisateur
try {
    $stmt = $connexion->prepare("DELETE FROM utilisateur WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // ✅ Log de la suppression
    require_once 'include/log_action.php';
    logAction('supprimer_utilisateur', "Suppression de l'utilisateur {$user['username']}", $_SESSION['user_id'] ?? null);

    $_SESSION['success'] = "Utilisateur « {$user['username']} » supprimé avec succès.";
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur lors de la suppression : " . $e->getMessage();
}

// Redirection vers le tableau de bord admin
header("Location: index.php?page=admin_dashboard");
exit;
