<?php
require_once('connect.php');
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Étape 1 : Charger les listes déroulantes ---
$annees = $connexion->query("SELECT id, libelle FROM annee ORDER BY libelle DESC")->fetchAll(PDO::FETCH_ASSOC);
$classes = $connexion->query("SELECT id, libelle FROM classe ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
$matieres = $connexion->query("SELECT id, libelle FROM matiere ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);

// --- Étape 2 : Initialiser les filtres ---
$annee_id = $_POST['annee_id'] ?? '';
$classe_id = $_POST['classe_id'] ?? '';
$matiere_id = $_POST['matiere_id'] ?? '';

$notes = [];

if (!empty($annee_id) && !empty($classe_id) && !empty($matiere_id)) {
    // --- Étape 3 : Requête pour récupérer les notes des étudiants ---
    $sql = "SELECT
                e.id AS etudiant_id,
                e.nom,
                e.prenoms,
                n.id AS note_id,
                n.val_note
            FROM inscription i
            INNER JOIN etudiant e ON e.id = i.etudiant_id
            LEFT JOIN note n ON n.inscription_id = i.id AND n.matiere_id = :matiere_id
            WHERE i.annee_id = :annee_id AND i.classe_id = :classe_id
            ORDER BY e.nom, e.prenoms";

    $stmt = $connexion->prepare($sql);
    $stmt->execute([
        ':annee_id' => $annee_id,
        ':classe_id' => $classe_id,
        ':matiere_id' => $matiere_id
    ]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!-- 🔹 Formulaire de sélection -->
<form method="POST" class="bg-light shadow-sm border-0 rounded-4 p-4 mb-4">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-card-checklist me-2"></i>
            Consultation des Notes par Matière
        </h4>
    </div>
    <div class="row g-3">
        <!-- Année -->
        <div class="col-md-4">
            <label for="annee" class="form-label">Année :</label>
            <select name="annee_id" id="annee" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($annees as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($annee_id == $a['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Classe -->
        <div class="col-md-4">
            <label for="classe" class="form-label">Classe :</label>
            <select name="classe_id" id="classe" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($classe_id == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Matière -->
        <div class="col-md-4">
            <label for="matiere" class="form-label">Matière :</label>
            <select name="matiere_id" id="matiere" class="form-select" required>
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
            <i class="bi bi-search"></i> Consulter
        </button>
    </div>
</form>

<!-- 🔹 Affichage du tableau des notes -->
<?php if (!empty($notes)): ?>
    <div class="table-responsive shadow-sm rounded-4">
        <table class="table table-striped align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th>#</th>
                    <th>Nom et Prénoms</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($notes as $n): ?>
                    <tr id="row-<?= $n['note_id'] ?>">
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= htmlspecialchars($n['nom'] . ' ' . $n['prenoms']) ?></td>
                        <td class="text-center"><?= $n['val_note'] !== null ? number_format($n['val_note'], 2) : '<span class="text-muted">Non noté</span>' ?></td>
                        <td class="text-center">
                            <?php if ($n['val_note'] !== null): ?>
                                <a href="index.php?page=modifier_note&id=<?= $n['note_id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="openDeleteModal(<?= $n['note_id'] ?>, '<?= htmlspecialchars($n['nom'] . ' ' . $n['prenoms']) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>


                            <?php else: ?>
                                <span class="text-muted">Aucune note</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="alert alert-warning text-center">Aucune note trouvée pour cette sélection.</div>
<?php endif; ?>


<!-- Modal de confirmation de suppression --><!-- ✅ Message d'alerte (affiché après suppression) -->
<div id="alert-container" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 2000;"></div>

<!-- 🔹 Fenêtre modale de confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
            <p id="deleteMessage">Voulez-vous vraiment supprimer cette note ?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</button>
        </div>
        </div>
    </div>
</div>

<!-- ✅ Script AJAX de suppression -->
<!-- ✅ jQuery (obligatoire avant ton script AJAX) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let noteIdToDelete = null;
    function openDeleteModal(id, nomEtudiant) {
        noteIdToDelete = id;
        document.getElementById('deleteMessage').textContent = 
            "Voulez-vous vraiment supprimer la note de " + nomEtudiant + " ?";
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!noteIdToDelete) return;

        // Requête AJAX sans jQuery (plus léger et fiable)
        fetch('include/supprimer_note.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(noteIdToDelete)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const row = document.getElementById('row-' + noteIdToDelete);
                if (row) {
                    row.remove(); // Supprime la ligne du tableau
                }
                showAlert('✅ ' + res.message, 'success');
            } else {
                showAlert('❌ ' + res.message, 'danger');
            }
        })
        .catch(() => {
            showAlert('⚠️ Impossible de contacter le serveur.', 'warning');
        });

        // Fermer la modale
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    });

    // Fonction pour afficher un message Bootstrap dynamique
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show shadow" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

</script>
