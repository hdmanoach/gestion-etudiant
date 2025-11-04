<?php
// user_dashboard.php
//session_start();
require_once 'connect.php'; // connexion à la base

$username = $_SESSION['username'] ?? 'Invité';
$role = $_SESSION['role'] ?? 0;

// --- Requêtes SQL de statistiques utilisateur ---
$user_id = $_SESSION['user_id'] ?? 0;

// Étudiants créés par cet utilisateur
$nbEtudiantsCrees = $connexion->query("SELECT COUNT(*) FROM etudiant WHERE created_by = $user_id")->fetchColumn();
$nbEtudiantsInscrits = $connexion->query("SELECT COUNT(*) FROM inscription WHERE created_by = $user_id")->fetchColumn();
$nbEtudiantsModifies = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'modifier_etudiant' AND user_id = $user_id")->fetchColumn();
$nbEtudiantsSupprimes = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'supprimer_etudiant' AND user_id = $user_id")->fetchColumn();

$nbNotesAttribuees = $connexion->query("SELECT COUNT(*) FROM note WHERE created_by = $user_id")->fetchColumn();
$nbNotesModifiees = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'modifier_note' AND user_id = $user_id")->fetchColumn();
$nbNotesSupprimees = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'supprimer_note' AND user_id = $user_id")->fetchColumn();
?>

<div class="container mt-4">
    <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord utilisateur</h3>

    <div class="row g-3">
        <!-- Statistiques Étudiants -->
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body text-center">
                    <h6 class="text-muted">Étudiants créés</h6>
                    <h2 class="fw-bold text-primary"><?= $nbEtudiantsCrees ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body text-center">
                    <h6 class="text-muted">Étudiants inscrits</h6>
                    <h2 class="fw-bold text-success"><?= $nbEtudiantsInscrits ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body text-center">
                    <h6 class="text-muted">Étudiants modifiés</h6>
                    <h2 class="fw-bold text-warning"><?= $nbEtudiantsModifies ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-danger border-4">
                <div class="card-body text-center">
                    <h6 class="text-muted">Étudiants supprimés</h6>
                    <h2 class="fw-bold text-danger"><?= $nbEtudiantsSupprimes ?></h2>
                </div>
            </div>
        </div>

        <!-- Statistiques Notes -->
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body text-center">
                    <h6 class="text-muted">Notes attribuées</h6>
                    <h2 class="fw-bold text-primary"><?= $nbNotesAttribuees ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body text-center">
                    <h6 class="text-muted">Notes modifiées</h6>
                    <h2 class="fw-bold text-warning"><?= $nbNotesModifiees ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-danger border-4">
                <div class="card-body text-center">
                    <h6 class="text-muted">Notes supprimées</h6>
                    <h2 class="fw-bold text-danger"><?= $nbNotesSupprimees ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 3 majeurs d'une classe -->
    <div class="mt-5">
        <h5 class="fw-semibold mb-3">🏅 Top 3 des majeurs de promotion</h5>
        <form method="GET"  action="index.php?page=user_dashboard" class="d-flex gap-2 mb-3">
            <select name="classe" class="form-select w-auto">
                <option value="">-- Classe --</option>
                <?php
                $classes = $connexion->query("SELECT * FROM classe")->fetchAll();
                foreach ($classes as $c) echo "<option value='{$c['id']}'>{$c['libelle']}</option>";
                ?>
            </select>
            <select name="annee" class="form-select w-auto">
                <option value="">-- Année --</option>
                <?php
                $annees = $connexion->query("SELECT * FROM annee")->fetchAll();
                foreach ($annees as $a) echo "<option value='{$a['id']}'>{$a['libelle']}</option>";
                ?>
            </select>
            <button class="btn btn-primary" type="submit">Voir</button>
        </form>


        <?php
        if (!empty($_GET['classe']) && !empty($_GET['annee'])) {
            $classe = (int) $_GET['classe'];
            $annee = (int) $_GET['annee'];

            $stmt = $connexion->prepare("
                SELECT e.nom, e.prenoms, ROUND(AVG(n.val_note), 2) AS moyenne
                FROM note n
                INNER JOIN inscription i ON n.inscription_id = i.id
                INNER JOIN etudiant e ON i.etudiant_id = e.id
                WHERE i.classe_id = :classe AND i.annee_id = :annee
                GROUP BY e.id
                ORDER BY moyenne DESC
                LIMIT 3
            ");
            $stmt->execute(['classe' => $classe, 'annee' => $annee]);
            $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($majors) {
                echo "<table class='table table-bordered text-center'>
                        <thead><tr><th>Nom</th><th>Prénoms</th><th>Moyenne</th></tr></thead>
                        <tbody>";
                foreach ($majors as $m) {
                    echo "<tr>
                            <td>{$m['nom']}</td>
                            <td>{$m['prenoms']}</td>
                            <td class='fw-bold text-primary'>{$m['moyenne']}</td>
                        </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-warning'>Aucun étudiant trouvé pour cette classe et cette année.</div>";
            }
        }

        ?>
    </div>
</div>
