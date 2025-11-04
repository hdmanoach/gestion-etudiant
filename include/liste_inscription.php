<?php
require_once 'include/connect.php';
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions

// --- Filtres ---
$classe_id = $_GET['classe_id'] ?? '';
$annee_id = $_GET['annee_id'] ?? '';

$where = [];
$params = [];

if (!empty($classe_id)) { $where[] = "i.classe_id = ?"; $params[] = $classe_id; }
if (!empty($annee_id)) { $where[] = "i.annee_id = ?"; $params[] = $annee_id; }

$sql = "SELECT i.id, e.nom, e.prenoms, e.photo, c.libelle AS classe, a.libelle AS annee
        FROM inscription i
        JOIN etudiant e ON i.etudiant_id = e.id
        JOIN classe c ON i.classe_id = c.id
        JOIN annee a ON i.annee_id = a.id";

if ($where) $sql .= " WHERE " . implode(" AND ", $where);

$stmt = $connexion->prepare($sql);
$stmt->execute($params);
$inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$classes = $connexion->query("SELECT * FROM classe ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
$annees = $connexion->query("SELECT * FROM annee ORDER BY libelle DESC")->fetchAll(PDO::FETCH_ASSOC);
// 🔹 Log de la consultation des inscriptions avec filtres
$filtre_msg = [];
if (!empty($classe_id)) $filtre_msg[] = "classe ID $classe_id";
if (!empty($annee_id)) $filtre_msg[] = "année ID $annee_id";

logAction(
    "Consultation des inscriptions",
    "L'utilisateur a consulté la liste des inscriptions" . 
    (!empty($filtre_msg) ? " avec filtres: " . implode(", ", $filtre_msg) : ""),
    $_SESSION['user_id'] ?? null
);
?>
<h4 class="text-center fw-bold text-primary mt-4">
    <i class="bi bi-list-ul me-2"></i>Liste des inscriptions
</h4>

<form method="get" class="d-flex justify-content-center align-items-center gap-3 mt-3">
    <input type="hidden" name="page" value="inscriptions">

    <select name="classe_id" class="form-select w-auto">
        <option value="">Toutes les classes</option>
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($classe_id == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="annee_id" class="form-select w-auto">
        <option value="">Toutes les années</option>
        <?php foreach ($annees as $a): ?>
            <option value="<?= $a['id'] ?>" <?= ($annee_id == $a['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-funnel me-1"></i>Filtrer
    </button>
</form>

<div class="table-responsive mt-4">
    <table class="table table-bordered table-striped align-middle mx-auto" style="max-width: 1000px;">
        <thead class="table-dark text-center">
            <tr>
                <th>Photo</th>
                <th>Nom & Prénoms</th>
                <th>Classe</th>
                <th>Année</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach ($inscriptions as $i): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($i['photo']) ?>" width="45" height="45" class="rounded-circle shadow-sm">
                    </td>
                    <td><?= htmlspecialchars($i['nom'] . ' ' . $i['prenoms']) ?></td>
                    <td><?= htmlspecialchars($i['classe']) ?></td>
                    <td><?= htmlspecialchars($i['annee']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($inscriptions)): ?>
                <tr><td colspan="4" class="text-muted">Aucune inscription trouvée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
