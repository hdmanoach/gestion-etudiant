<?php
require_once 'connect.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    echo "<div class='alert alert-warning'>Veuillez entrer un mot-clé pour rechercher un étudiant.</div>";
    exit;
}

$stmt = $connexion->prepare("
    SELECT e.id, e.nom, e.prenoms, e.photo, i.annee_id, c.libelle AS classe
    FROM etudiant e
    LEFT JOIN inscription i ON e.id = i.etudiant_id
    LEFT JOIN classe c ON i.classe_id = c.id
    WHERE e.nom LIKE :q OR e.prenoms LIKE :q
    ORDER BY e.nom ASC
");
$stmt->execute([':q' => "%$q%"]);
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h4 class="fw-bold text-primary mb-4">
        <i class="bi bi-search me-2"></i>Résultats de recherche pour "<span class="text-dark"><?= htmlspecialchars($q) ?></span>"
    </h4>

    <?php if (empty($resultats)): ?>
        <div class="alert alert-info">Aucun étudiant trouvé.</div>
    <?php else: ?>
        <div class="list-group shadow-sm">
            <?php foreach ($resultats as $etu): ?>
                <a href="index.php?page=fiche_etudiant&id=<?= $etu['id'] ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                    <img src="<?= htmlspecialchars($etu['photo'] ?: 'uploads/default.png') ?>" alt="Photo" class="rounded-circle me-3" width="50" height="50">
                    <div>
                        <strong><?= htmlspecialchars($etu['nom'] . ' ' . $etu['prenoms']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($etu['classe'] ?? 'Non inscrit') ?> — <?= htmlspecialchars($etu['annee'] ?? '-') ?></small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
