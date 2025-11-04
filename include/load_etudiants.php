<?php
    require_once('connect.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // 🔹 Vérifier si l'ID de l'année scolaire est fourni
    if (isset($_GET['annee_id'])) {
        $annee_id = intval($_GET['annee_id']);

        // 🔹 Requête : on récupère les inscriptions de cette année
        $sql = "SELECT i.id AS inscription_id, e.nom, e.prenoms
                FROM inscription i
                INNER JOIN etudiant e ON e.id = i.etudiant_id
                WHERE i.annee_id = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$annee_id]);
        $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔹 Si aucun étudiant trouvé
        if (empty($etudiants)) {
            echo '<option value="">Aucun étudiant inscrit pour cette année</option>';
            exit;
        }

        // 🔹 Sinon, afficher les options du select
        foreach ($etudiants as $et) {
            $nomComplet = htmlspecialchars($et['nom'] . ' ' . $et['prenoms']);
            echo "<option value='{$et['inscription_id']}'>{$nomComplet}</option>";
        }
    }
    ?>

?>
