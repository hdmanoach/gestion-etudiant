<?php
//session_start();
require_once 'connect.php';

// Vérifie que seul l’admin y a accès
if (($_SESSION['role'] ?? 0) != 1) {
    header('Location: index.php');
    exit;
}

// Récupération de l’ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = intval($_GET['id']);

// Récupération des infos de l’utilisateur
$stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

// --- Traitement du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = intval($_POST['role']);
    $password = $_POST['password'] ?? '';

    // Si un nouveau mot de passe est saisi, on le met à jour
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE utilisateur SET username=:username, email=:email, role=:role, password=:password WHERE id=:id";
        $params = [
            ':username' => $username,
            ':email' => $email,
            ':role' => $role,
            ':password' => $hashedPassword,
            ':id' => $id
        ];
    } else {
        $sql = "UPDATE utilisateur SET username=:username, email=:email, role=:role WHERE id=:id";
        $params = [
            ':username' => $username,
            ':email' => $email,
            ':role' => $role,
            ':id' => $id
        ];
    }

    $stmt = $connexion->prepare($sql);
    $stmt->execute($params);

    // ✅ Ajout au log
    require_once 'include/log_action.php';
    logAction('modifier_utilisateur', "Modification de l'utilisateur $username", $_SESSION['user_id'] ?? null);

    header("Location: index.php?page=admin_dashboard&success=Utilisateur modifié avec succès");
    exit;
}
?>

<div class="container mt-5">
    <h3 class="fw-bold text-warning mb-4">
        <i class="bi bi-pencil-square me-2"></i>Modifier un utilisateur
    </h3>

    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="col-md-4">
            <label class="form-label">Rôle</label>
            <select name="role" class="form-select">
                <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Utilisateur</option>
                <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-warning me-2">
                <i class="bi bi-check-circle"></i> Mettre à jour
            </button>
            <a href="index.php?page=admin_dashboard" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </form>
</div>
