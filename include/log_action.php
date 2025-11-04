<?php
// include/log_action.php

// ✅ On démarre la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Connexion à la base de données
// On utilise __DIR__ pour être sûr que le chemin soit correct
require_once __DIR__ . '/connect.php';

/**
 * Enregistre une action dans :
 *  - la session (pour affichage rapide)
 *  - la table `log` (base de données)
 *  - le fichier texte `system.log`
 *
 * @param string $action        Le nom de l’action (ex : "ajouter_etudiant")
 * @param string $description   Détails de l’action
 * @param int|null $userId      ID de l’utilisateur (facultatif)
 * @param int $limit            Nombre max d’actions gardées en session
 */
function logAction(string $action, string $description = '', ?int $userId = null, int $limit = 10) {
    global $connexion; // 🔹 Permet d’utiliser la connexion PDO définie dans connect.php

    $logFile = __DIR__ . '/../system.log'; // 🔹 Fichier log à la racine du projet

    // 🔸 1. Enregistrer dans la session (historique court pour l'utilisateur connecté)
    if (!isset($_SESSION['actions'])) {
        $_SESSION['actions'] = [];
    }

    array_unshift($_SESSION['actions'], [
        'action' => $action,
        'description' => $description,
        'date' => date('d/m/Y H:i:s')
    ]);

    // Garder uniquement les $limit dernières actions
    $_SESSION['actions'] = array_slice($_SESSION['actions'], 0, $limit);

    // 🔸 2. Enregistrer dans la base de données (table `log`)
    try {
        $stmt = $connexion->prepare("
            INSERT INTO log (user_id, action, description, date_action)
            VALUES (:user_id, :action, :description, NOW())
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':action' => $action,
            ':description' => $description
        ]);
    } catch (PDOException $e) {
        // En cas d'erreur SQL, on logue aussi dans le fichier d'erreurs PHP
        error_log("❌ Erreur lors de l’enregistrement du log SQL : " . $e->getMessage());
    }

    // 🔸 3. Écrire aussi dans le fichier texte (system.log)
    try {
        $userPart = $userId ? "Utilisateur #$userId" : "Système";
        $entry = "[" . date('Y-m-d H:i:s') . "] [$userPart] [$action] $description" . PHP_EOL;
        file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        error_log("❌ Erreur lors de l’écriture du log fichier : " . $e->getMessage());
    }
}
