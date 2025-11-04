<?php
require_once('connect.php');
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Charger les listes de sélection
$annees = $connexion->query("SELECT id, libelle FROM annee ORDER BY libelle DESC")->fetchAll(PDO::FETCH_ASSOC);
$classes = $connexion->query("SELECT id, libelle FROM classe ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
$matieres = $connexion->query("SELECT id, libelle FROM matiere ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);

// Variables pour les filtres
$annee_id = $_POST['annee_id'] ?? '';
$classe_id = $_POST['classe_id'] ?? '';
$matiere_id = $_POST['matiere_id'] ?? '';

$etudiants = [];
$message = '';

// --- Étape 1 : Si on a sélectionné une classe et une matière ---
if (!empty($annee_id) && !empty($classe_id) && !empty($matiere_id) && !isset($_POST['save_notes'])) {
    $sql = "SELECT i.id AS inscription_id, e.nom, e.prenoms
            FROM inscription i
            INNER JOIN etudiant e ON e.id = i.etudiant_id
            WHERE i.annee_id = :annee_id AND i.classe_id = :classe_id
            ORDER BY e.nom, e.prenoms";
    $stmt = $connexion->prepare($sql);
    $stmt->execute(['annee_id' => $annee_id, 'classe_id' => $classe_id]);
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- Étape 2 : Enregistrement des notes ---
if (isset($_POST['save_notes'])) {
    $matiere_id = $_POST['matiere_id'];
    $notes = $_POST['notes'] ?? [];

    // Récupérer le nom de la matière
    $matiere_stmt = $connexion->prepare("SELECT libelle FROM matiere WHERE id = ?");
    $matiere_stmt->execute([$matiere_id]);
    $matiere = $matiere_stmt->fetchColumn();

    $insert = $connexion->prepare("
        INSERT INTO note (matiere_id, inscription_id, val_note)
        VALUES (:matiere_id, :inscription_id, :val_note)
        ON DUPLICATE KEY UPDATE val_note = :val_note
    ");

    $count = 0;
    foreach ($notes as $inscription_id => $val_note) {
        if ($val_note !== '') {
            // Récupérer le nom complet de l'étudiant pour cet inscription_id
            $etudiant_stmt = $connexion->prepare("
                SELECT e.nom, e.prenoms 
                FROM etudiant e 
                INNER JOIN inscription i ON e.id = i.etudiant_id 
                WHERE i.id = ?
            ");
            $etudiant_stmt->execute([$inscription_id]);
            $etudiant = $etudiant_stmt->fetch(PDO::FETCH_ASSOC);

            $insert->execute([
                ':matiere_id' => $matiere_id,
                ':inscription_id' => $inscription_id,
                ':val_note' => $val_note
            ]);
            $count++;

            // 🔹 Log avec le nom complet de l'étudiant et le nom de la matière
            $etudiant_nom = $etudiant['nom'] . ' ' . $etudiant['prenoms'];
            logAction("Note de $val_note enregistrée pour $etudiant_nom en matière $matiere");
        }
    }

    $message = "<div class='alert alert-success text-center mt-3'>
                    ✅ $count note(s) enregistrée(s) avec succès !
                </div>";
}

?>
<!-- 🔹 Étape 1 : Sélection des filtres -->
<form method="POST" class="bg-light p-4 rounded-4 shadow-sm mb-4">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-journal-plus me-2"></i>
            Enregistrer Plusieurs Notes
        </h4>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Année scolaire :</label>
            <select name="annee_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($annees as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($annee_id == $a['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Classe :</label>
            <select name="classe_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($classe_id == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Matière :</label>
            <select name="matiere_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($matieres as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= ($matiere_id == $m['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-search"></i> Afficher les étudiants
        </button>
    </div>
</form>

<?= $message ?>

<!-- 🔹 Étape 2 : Saisie des notes -->
<?php if (!empty($etudiants)): ?>
    <form method="POST" class="bg-white p-4 rounded-4 shadow-sm">
        <input type="hidden" name="annee_id" value="<?= $annee_id ?>">
        <input type="hidden" name="classe_id" value="<?= $classe_id ?>">
        <input type="hidden" name="matiere_id" value="<?= $matiere_id ?>">

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nom complet</th>
                        <th>Note (0 - 20)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($etudiants as $e): ?>
                        <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($e['nom'] . ' ' . $e['prenoms']) ?></td>
                            <td class="text-center">
                                <select name="notes[<?= $e['inscription_id'] ?>]" id="note" class="form-select" required>
                                    <?php for ($n = 0; $n <= 20; $n += 0.25): ?>
                                        <option value="<?= number_format($n, 2) ?>"><?= number_format($n, 2) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-3">
            <button type="submit" name="save_notes" class="btn btn-success px-4">
                <i class="bi bi-save"></i> Enregistrer toutes les notes
            </button>
        </div>
    </form>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['save_notes'])): ?>
    <div class="alert alert-warning text-center">Aucun étudiant trouvé pour cette sélection.</div>
<?php endif; ?>
