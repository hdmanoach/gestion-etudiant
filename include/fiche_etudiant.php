<?php
require_once 'connect.php';

$id = $_GET['id'] ?? 0;

// 🔹 Récupération des infos de l'étudiant avec sa classe et son année
$stmt = $connexion->prepare("
    SELECT 
        e.*, 
        i.id AS inscription_id,
        c.libelle AS classe,
        a.libelle AS annee
    FROM etudiant e
    LEFT JOIN inscription i ON e.id = i.etudiant_id
    LEFT JOIN classe c ON i.classe_id = c.id
    LEFT JOIN annee a ON i.annee_id = a.id
    WHERE e.id = :id
");
$stmt->execute([':id' => $id]);
$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    echo "<div class='alert alert-danger'>Étudiant introuvable.</div>";
    exit;
}

// 🔹 Récupération des notes via inscription_id
$stmtNotes = $connexion->prepare("
    SELECT 
        m.libelle AS matiere, 
        n.val_note
    FROM note n
    JOIN matiere m ON n.matiere_id = m.id
    WHERE n.inscription_id = :inscription_id
");
$stmtNotes->execute([':inscription_id' => $etudiant['inscription_id']]);
$notes = $stmtNotes->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="<?= htmlspecialchars($etudiant['photo'] ?: 'uploads/default.png') ?>" 
                     class="rounded-circle me-4" width="100" height="100">
                <div>
                    <h4 class="fw-bold mb-0">
                        <?= htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenoms']) ?>
                    </h4>
                    <p class="text-muted mb-1">
                        <?= htmlspecialchars($etudiant['classe'] ?? 'Non inscrit') ?>
                    </p>
                    <p class="small text-secondary">
                        Année : <?= htmlspecialchars($etudiant['annee'] ?? '-') ?>
                    </p>
                </div>
            </div>

            <hr>

            <h5 class="fw-bold text-primary mt-3">
                <i class="bi bi-clipboard-data me-2"></i>Notes
            </h5>
            <?php if (empty($notes)): ?>
                <p class="text-muted">Aucune note enregistrée.</p>
            <?php else: ?>
                <table class="table table-striped table-bordered align-middle mt-2">
                    <thead class="table-light">
                        <tr>
                            <th>Matière</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notes as $note): ?>
                            <tr>
                                <td><?= htmlspecialchars($note['matiere']) ?></td>
                                <td><strong><?= htmlspecialchars($note['val_note']) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card img {
        object-fit: cover;
    }
</style>
