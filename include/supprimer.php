<?php
require_once 'include/connect.php';
require_once 'include/log_action.php'; // 🔹 Inclure le logger

session_start(); // 🔹 S'assurer que la session est démarrée pour récupérer user_id

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Récupération des infos de l'étudiant avant suppression
    $stmt = $connexion->prepare("SELECT nom, prenoms, photo FROM etudiant WHERE id = ?");
    $stmt->execute([$id]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($etudiant) {
        // Suppression de la photo
        if (!empty($etudiant['photo'])) {
            $photoPath = $etudiant['photo']; // Si le chemin complet est déjà stocké
            if (file_exists($photoPath)) unlink($photoPath);
        }

        // Suppression de l’étudiant
        $stmt = $connexion->prepare("DELETE FROM etudiant WHERE id = ?");
        $stmt->execute([$id]);

        // 🔹 Logger l’action avec user_id
        logAction(
            "supprimer_etudiant",
            "Étudiant supprimé : {$etudiant['nom']} {$etudiant['prenoms']}, ID : $id",
            $_SESSION['user_id'] ?? null
        );

        echo '<div class="alert alert-success text-center mt-4"> Étudiant supprimé avec succès.</div>';
        header("Refresh: 2; URL=index.php?page=liste");
        exit;
    } else {
        echo '<div class="alert alert-danger text-center mt-4"> Étudiant introuvable.</div>';
        header("Refresh: 2; URL=index.php?page=liste");
        exit;
    }

} else {
    header("Location: index.php?page=liste");
    exit;
}
