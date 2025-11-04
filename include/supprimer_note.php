<?php
require_once('connect.php'); // Ajuste le chemin si nécessaire
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions
header('Content-Type: application/json'); // Toujours renvoyer du JSON

try {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de la note manquant.']);
        exit;
    }

    $noteId = intval($_POST['id']);

    // --- Récupérer les infos de la note avant suppression ---
    $stmtInfo = $connexion->prepare("
        SELECT e.nom, e.prenoms, m.libelle AS matiere
        FROM note n
        INNER JOIN inscription i ON i.id = n.inscription_id
        INNER JOIN etudiant e ON e.id = i.etudiant_id
        INNER JOIN matiere m ON m.id = n.matiere_id
        WHERE n.id = :id
    ");
    $stmtInfo->execute([':id' => $noteId]);
    $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

    if (!$info) {
        echo json_encode(['success' => false, 'message' => 'La note n’existe pas ou a déjà été supprimée.']);
        exit;
    }

    // --- Supprimer la note ---
    $stmtDelete = $connexion->prepare("DELETE FROM note WHERE id = :id");
    $ok = $stmtDelete->execute([':id' => $noteId]);

    if ($ok) {
        // 🔹 Log de la suppression
        $action = "supprimer_note";
        $description = "La note de {$info['nom']} {$info['prenoms']} en {$info['matiere']} a été supprimée.";
        logAction($action, $description, $_SESSION['user_id'] ?? null);

        echo json_encode(['success' => true, 'message' => 'Note supprimée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Impossible de supprimer la note.']);
    }

} catch (Exception $e) {
    // En cas d'erreur serveur inattendue
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}
