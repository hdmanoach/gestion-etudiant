<?php
require_once 'include/connect.php';
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions

// --- Récupération des listes ---
$etudiants = $connexion->query("SELECT id, CONCAT(nom, ' ', prenoms) AS nom_complet FROM etudiant ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
$classes   = $connexion->query("SELECT * FROM classe ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
$annees    = $connexion->query("SELECT * FROM annee ORDER BY libelle DESC")->fetchAll(PDO::FETCH_ASSOC);

// --- Traitement du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $classe_id   = $_POST['classe_id'];
    $annee_id    = $_POST['annee_id'];

    // Vérifier si déjà inscrit pour l'année choisie
    $check = $connexion->prepare("SELECT * FROM inscription WHERE etudiant_id = ? AND annee_id = ?");
    $check->execute([$etudiant_id, $annee_id]);

    if ($check->rowCount() > 0) {
        $message = "<div class='alert alert-warning text-center mt-3'>⚠️ Cet étudiant est déjà inscrit pour cette année.</div>";
    } else {
        $stmt = $connexion->prepare("INSERT INTO inscription (etudiant_id, classe_id, annee_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$etudiant_id, $classe_id, $annee_id])) {

            // Récupérer les libellés pour le log
            $etudiant_nom = $connexion->prepare("SELECT CONCAT(nom, ' ', prenoms) FROM etudiant WHERE id = ?");
            $etudiant_nom->execute([$etudiant_id]);
            $etudiant_nom = $etudiant_nom->fetchColumn();

            $classe_libelle = $connexion->prepare("SELECT libelle FROM classe WHERE id = ?");
            $classe_libelle->execute([$classe_id]);
            $classe_libelle = $classe_libelle->fetchColumn();

            $annee_libelle = $connexion->prepare("SELECT libelle FROM annee WHERE id = ?");
            $annee_libelle->execute([$annee_id]);
            $annee_libelle = $annee_libelle->fetchColumn();

            // 🔹 Log de l’inscription avec user_id
            logAction(
                "Inscription Étudiant",
                "Étudiant inscrit : $etudiant_nom en classe $classe_libelle pour l'année $annee_libelle",
                $_SESSION['user_id'] ?? null
            );

            $message = "<div class='alert alert-success text-center mt-3'>✅ Inscription réussie !</div>";
            header("Refresh:2; url=index.php?page=inscriptions");
        } else {
            $message = "<div class='alert alert-danger text-center mt-3'>❌ Erreur lors de l'inscription. Veuillez réessayer.</div>";
        }
    }
}
?>

<div class="bg-light rounded-4 shadow-sm p-4 mx-auto mt-4" style="max-width: 700px;">
    <h4 class="text-center fw-bold text-primary mb-4">
        <i class="bi bi-journal-plus me-2"></i>Formulaire d’inscription
    </h4>

    <?php if (isset($message)) echo $message; ?>

    <form method="post" class="row g-3">
        <div class="col-12">
            <label for="etudiant_id" class="form-label fw-semibold">Étudiant</label>
            <select name="etudiant_id" id="etudiant_id" class="form-select" required>
                <option value="">-- Sélectionnez un étudiant --</option>
                <?php foreach ($etudiants as $et): ?>
                    <option value="<?= $et['id'] ?>"><?= htmlspecialchars($et['nom_complet']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="classe_id" class="form-label fw-semibold">Classe</label>
            <select name="classe_id" id="classe_id" class="form-select" required>
                <option value="">-- Choisir la classe --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="annee_id" class="form-label fw-semibold">Année scolaire</label>
            <select name="annee_id" id="annee_id" class="form-select" required>
                <option value="">-- Choisir l’année --</option>
                <?php foreach ($annees as $a): ?>
                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="bi bi-check-circle me-2"></i>Enregistrer
            </button>
            <a href="index.php?page=liste_inscriptions" class="btn btn-outline-secondary px-4 py-2 ms-2">
                <i class="bi bi-x-circle me-2"></i>Annuler
            </a>
        </div>
    </form>
</div>
