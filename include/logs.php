<?php
//session_start();
require_once 'connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔒 Vérifie que seul l’admin a accès
if (($_SESSION['role'] ?? 0) != 1) {
    header('Location: index.php');
    exit;
}

// ✅ Si l’URL est du type index.php?search=..., on redirige proprement
if (!isset($_GET['page']) && isset($_GET['search'])) {
    $search = urlencode($_GET['search']);
    header("Location: index.php?page=logs&search={$search}");
    exit;
}

$search = trim($_GET['search'] ?? '');
$logFile = 'system.log';

// 📂 Lecture du fichier log
$logs = [];
if (file_exists($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // 🔍 Filtrage par mot-clé (texte, date, utilisateur, etc.)
    if ($search !== '') {
        $lines = array_filter($lines, fn($line) => stripos($line, $search) !== false);
    }

    // Tri : plus récents en haut
    $logs = array_reverse($lines);


    // ✨ Fonction pour surligner le mot clé
    function highlightKeyword($text, $keyword) {
        if (empty($keyword)) return htmlspecialchars($text);
        // htmlspecialchars pour éviter les injections HTML, puis remplacement insensible à la casse
        $safeKeyword = preg_quote($keyword, '/');
        return preg_replace(
            "/($safeKeyword)/i",
            '<span class="bg-warning text-dark fw-bold">$1</span>',
            htmlspecialchars($text)
        );
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Journal du système</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-danger"><i class="bi bi-journal-text me-2"></i>Journal du système</h4>
        <a href="index.php?page=admin_dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <!-- 🔍 Barre de recherche -->
    <form method="GET" class="input-group mb-3">
        <!-- On garde le paramètre page -->
        <input type="hidden" name="page" value="logs">
        <input type="text" name="search" class="form-control" placeholder="Rechercher (mot, date, utilisateur...)" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Rechercher</button>
        <?php if ($search): ?>
            <a href="index.php?page=logs" class="btn btn-outline-secondary">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <!-- 📋 Affichage des logs -->
    <?php if (!empty($logs)): ?>
        <div class="bg-white border rounded p-3 shadow-sm" style="max-height:70vh; overflow:auto;">
            <ul class="list-group list-group-flush">
                <?php foreach ($logs as $line): ?>
                    <li class="list-group-item small font-monospace">
                        <?= highlightKeyword($line, $search) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php 
            // ✏️ Log de la consultation
            require_once 'include/log_action.php';
            logAction('consultation_logs', "Consultation des logs système", $_SESSION['user_id'] ?? null);
        ?>
    <?php else: ?>
        <div class="alert alert-warning">
            Aucun log trouvé<?= $search ? ' pour "<strong>' . htmlspecialchars($search) . '</strong>"' : '' ?>.
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
