<?php
require_once('connect.php');
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matiere_id = $_POST['matiere_id'];
    $inscription_id = $_POST['inscription_id'];
    $val_note = $_POST['val_note'];

    // Vérifier si la note existe déjà
    $check = $connexion->prepare("SELECT COUNT(*) FROM note WHERE matiere_id = ? AND inscription_id = ?");
    $check->execute([$matiere_id, $inscription_id]);
    if ($check->fetchColumn() > 0) {
        header("Location: index.php?page=enregistrer_note&error=exists");
        exit;
    }

    // Insérer la note
    $stmt = $connexion->prepare("INSERT INTO note (matiere_id, inscription_id, val_note) VALUES (?, ?, ?)");
    $ok = $stmt->execute([$matiere_id, $inscription_id, $val_note]);

    if ($ok) {
        // 🔹 Récupérer le nom de l’étudiant et le nom de la matière pour le log
        $sql = "SELECT e.nom, e.prenoms, m.libelle AS matiere
                FROM inscription i
                INNER JOIN etudiant e ON e.id = i.etudiant_id
                INNER JOIN matiere m ON m.id = ?
                WHERE i.id = ?";
        $infoStmt = $connexion->prepare($sql);
        $infoStmt->execute([$matiere_id, $inscription_id]);
        $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

        if ($info) {
            $action = "enregistrer_note";
            $description = "Note {$val_note} enregistrée pour {$info['nom']} {$info['prenoms']} en matière {$info['matiere']}";
            logAction($action, $description, $_SESSION['user_id'] ?? null); // si tu as la session utilisateur
        }


        header("Location: index.php?page=consulter_notes&success=1");
    } else {
        header("Location: index.php?page=enregistrer_note&error=1");
    }
    exit;
}
?>
