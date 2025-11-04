<?php
//session_start();

// Récupération des infos utilisateur
$username = $_SESSION['username'] ?? 'Invité';
$role = $_SESSION['role'] ?? 0; // 1 = Super admin, 0 = utilisateur simple
$roleText = $role == 1 ? 'Super Admin' : 'Utilisateur';

// 🔹 Récupère le mode en session : "etudiants" (par défaut) ou "notes"
$mode = $_SESSION['mode'] ?? 'etudiants';

// Page actuelle (pour mettre le lien actif)
$currentPage = $_GET['page'] ?? 'accueil';

// 🔹 Fonction pour définir la classe active
function isActive($page, $currentPage) {
    return $page === $currentPage ? 'active text-primary fw-semibold' : '';
}
?>

<header class="fixed-top">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm w-100">
        <div class="container-fluid">
            <!-- Logo + Nom du site -->
            <a class="navbar-brand fw-bold text-primary" href="index.php">
                <i class="bi bi-mortarboard-fill me-2"></i>Gestion Étudiant
            </a>

            <!-- Bouton burger mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu principal -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-3">

                    <?php if ($mode === 'notes'): ?>
                        <!-- 🔹 Mode Gestion des Notes -->
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('actions', $currentPage) ?>" href="index.php?page=actions">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('enregistrer_note', $currentPage) ?>" href="index.php?page=enregistrer_note">Enregistrer une note</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('enregistrer_notes', $currentPage) ?>" href="index.php?page=enregistrer_notes">Enregistrer plusieurs notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('consulter_notes', $currentPage) ?>" href="index.php?page=consulter_notes">Consulter les notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('actions', $currentPage) ?>" href="index.php?page=actions">Actions</a>
                        </li>

                    <?php else: ?>
                        <!-- 🔹 Mode Gestion des Étudiants -->
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('accueil', $currentPage) ?>" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('ajouter', $currentPage) ?>" href="index.php?page=ajouter">Ajouter Étudiant</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('inscrire', $currentPage) ?>" href="index.php?page=inscrire">Inscrire un Étudiant</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('liste', $currentPage) ?>" href="index.php?page=liste">Liste des Étudiants</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('inscriptions', $currentPage) ?>" href="index.php?page=inscriptions">Liste des Inscriptions</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <!-- 🔍 Barre de recherche Étudiant -->
                <?php if ($mode !== 'notes'): ?>
                    <form class="d-flex ms-3" method="GET" action="index.php">
                        <input type="hidden" name="page" value="recherche_etudiant">
                        <input class="form-control me-2" type="search" name="q" placeholder="Rechercher un étudiant..." aria-label="Search" required>
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                <?php endif; ?>

                <!-- Menu utilisateur -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <?= htmlspecialchars($username) ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header text-center">
                                <strong><?= htmlspecialchars($username) ?></strong><br>
                                <small class="text-muted"><?= $roleText ?></small>
                            </li>
                            <li><hr class="dropdown-divider"></li>

                            <?php if ($role == 1): ?>
                                <li><a class="dropdown-item <?= isActive('admin_dashboard', $currentPage) ?>" href="index.php?page=admin_dashboard">Tableau de bord admin</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item <?= isActive('user_dashboard', $currentPage) ?>" href="index.php?page=user_dashboard">Tableau de bord</a></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item <?= isActive('liste', $currentPage) ?>" href="index.php?page=liste">Gestion des étudiants</a></li>
                            <li><a class="dropdown-item <?= isActive('gestion_notes', $currentPage) ?>" href="index.php?page=gestion_notes">Gestion des notes</a></li>

                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 🔹 Indicateur de mode -->
    <div class="text-center bg-light py-1 border-bottom small fw-semibold text-secondary">
        <?php if ($mode === 'notes'): ?>
            📝 Mode actuel : <span class="text-primary">Gestion des Notes</span>
        <?php else: ?>
            🎓 Mode actuel : <span class="text-primary">Gestion des Étudiants</span>
        <?php endif; ?>
    </div>
</header>

<!-- Styles -->
<style>
    body {
        padding-top: 90px; /* espace pour la navbar */
    }

    .navbar-nav .nav-link {
        color: #555;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #0d6efd;
        transform: translateY(-2px);
    }

    .dropdown-item:hover,
    .dropdown-item.active {
        background-color: #f1f1f1;
        color: #0d6efd;
        font-weight: 600;
    }
</style>

<!-- Icônes Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
