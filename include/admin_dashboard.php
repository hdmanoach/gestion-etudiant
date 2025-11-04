<?php
//session_start();
require_once 'connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$role = $_SESSION['role'] ?? 0;
if ($role != 1) { header('Location: index.php'); exit; }

$now = new DateTime();

// Stats générales
$totalUsers = $connexion->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
$totalEtudiants = $connexion->query("SELECT COUNT(*) FROM etudiant")->fetchColumn();
$totalNotes = $connexion->query("SELECT COUNT(*) FROM note")->fetchColumn();
$totalInscriptions = $connexion->query("SELECT COUNT(*) FROM inscription")->fetchColumn();

// Liste des utilisateurs avec leurs activités
$utilisateurs = $connexion->query("SELECT * FROM utilisateur ORDER BY role DESC")->fetchAll();
?>

<div class="container mt-4">
    <h3 class="mb-4 fw-bold text-danger">
        <i class="bi bi-speedometer me-2"></i>Tableau de bord Administrateur
    </h3>

    <!-- 📊 Statistiques globales -->
    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Utilisateurs</h6>
                    <h2 class="fw-bold text-primary"><?= $totalUsers ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Étudiants</h6>
                    <h2 class="fw-bold text-success"><?= $totalEtudiants ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Inscriptions</h6>
                    <h2 class="fw-bold text-warning"><?= $totalInscriptions ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-danger border-4 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Notes</h6>
                    <h2 class="fw-bold text-danger"><?= $totalNotes ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- 👥 Liste détaillée des utilisateurs -->
    <h5 class="fw-semibold mb-3">👥 Activités par utilisateur</h5>

    <table class="table table-striped table-hover align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Étudiants créés</th>
                <th>Modifiés</th>
                <th>Supprimés</th>
                <th>Notes créées</th>
                <th>Modifiées</th>
                <th>Supprimées</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($utilisateurs as $u): ?>
            <?php
                $user_id = $u['id'];

                // Récupération des stats spécifiques
                $nbEtudiantsCrees = $connexion->query("SELECT COUNT(*) FROM etudiant WHERE created_by = $user_id")->fetchColumn();
                $nbEtudiantsModifies = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'modifier_etudiant' AND user_id = $user_id")->fetchColumn();
                $nbEtudiantsSupprimes = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'supprimer_etudiant' AND user_id = $user_id")->fetchColumn();

                $nbNotesCrees = $connexion->query("SELECT COUNT(*) FROM note WHERE created_by = $user_id")->fetchColumn();
                $nbNotesModifiees = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'modifier_note' AND user_id = $user_id")->fetchColumn();
                $nbNotesSupprimees = $connexion->query("SELECT COUNT(*) FROM log WHERE action = 'supprimer_note' AND user_id = $user_id")->fetchColumn();

                // Statut actif/inactif
                $lastActivity = new DateTime($u['last_activity'] ?? '2000-01-01 00:00:00'); 
                $isActive = ($now->getTimestamp() - $lastActivity->getTimestamp()) <= 5 * 60;
            ?>
            <tr>
                <td class="fw-semibold"><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] == 1 ? '<span class="badge bg-danger">Admin</span>' : 'Utilisateur' ?></td>
                <td>
                    <span class="badge bg-<?= $isActive ? 'success' : 'secondary' ?>">
                        <?= $isActive ? 'Actif' : 'Inactif' ?>
                    </span>
                </td>
                <td><span class="text-success fw-bold"><?= $nbEtudiantsCrees ?></span></td>
                <td><span class="text-warning fw-bold"><?= $nbEtudiantsModifies ?></span></td>
                <td><span class="text-danger fw-bold"><?= $nbEtudiantsSupprimes ?></span></td>
                <td><span class="text-success fw-bold"><?= $nbNotesCrees ?></span></td>
                <td><span class="text-warning fw-bold"><?= $nbNotesModifiees ?></span></td>
                <td><span class="text-danger fw-bold"><?= $nbNotesSupprimees ?></span></td>
                <td>
                    <a href="index.php?page=modifier_user&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="index.php?page=supprimer_user&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- ➕ Création d'un nouvel utilisateur -->
    <h5 class="fw-semibold mt-5 mb-3">➕ Créer un nouvel utilisateur</h5>
    <form action="index.php?page=ajouter_user" method="POST" class="row g-3">
        <div class="col-md-4">
            <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
        </div>
        <div class="col-md-4">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="col-md-4">
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="0">Utilisateur</option>
                <option value="1">Admin</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Ajouter
            </button>
        </div>
    </form>

    <!-- 🧾 Logs système -->
    <h5 class="fw-semibold mt-5 mb-3">
        📜 Fichier de logs du système
        <a href="index.php?page=logs" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-search"></i> Voir plus
        </a>
    </h5>
    <pre class="bg-light p-3 border rounded" style="max-height:300px;overflow:auto;">
        <?= htmlspecialchars(@file_get_contents('system.log') ?: 'Aucun log disponible') ?>
    </pre>
</div>
