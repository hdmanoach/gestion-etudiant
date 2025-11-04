<?php
require_once 'include/connect.php';
require_once 'include/log_action.php';
session_start();

if (!isset($_SESSION['pending_user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['pending_user'];
$message = '';
$expiration = 60; // 5 minutes

// Vérification de l'expiration côté serveur
if (isset($_SESSION['verification_time']) && time() - $_SESSION['verification_time'] > $expiration) {
    unset($_SESSION['pending_user'], $_SESSION['verification_code'], $_SESSION['verification_time'], $_SESSION['step']);
    logAction('code_expire', "Code expiré pour {$user['username']}");
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $enteredCode = trim($_POST['code']);

    if ($enteredCode === $_SESSION['verification_code']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        unset($_SESSION['pending_user'], $_SESSION['verification_code'], $_SESSION['verification_time'], $_SESSION['step']);
        logAction('verification_reussie', "Connexion réussie pour {$user['username']}");

        header("Location: index.php?page=" . ($user['role'] == 1 ? "admin_dashboard" : "user_dashboard"));
        exit;
    } else {
        $message = "Code incorrect. Réessayez.";
        logAction('code_incorrect', "Tentative avec code erroné par {$user['username']}");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Vérification Email</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card shadow p-4" style="width: 420px;">
<h4 class="text-center mb-3 text-primary">Double authentification</h4>

<?php if ($message): ?>
<div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="code" class="form-label">Entrez le code reçu par email :</label>
        <input type="text" name="code" id="code" maxlength="6" class="form-control text-center" required>
    </div>
    <div class="text-center text-danger fw-bold mb-2">
        Code expirera dans : <span id="timer">60</span> secondes
    </div>
    <button type="submit" class="btn btn-success w-100">Vérifier le code</button>
</form>
</div>

<script>
let expiration = 60 - (<?php echo time() - $_SESSION['verification_time']; ?>);
if(expiration < 0) expiration = 0;

const timerDisplay = document.getElementById('timer');
const interval = setInterval(() => {
    expiration--;
    timerDisplay.textContent = expiration;
    if (expiration <= 0) {
        clearInterval(interval);
        timerDisplay.textContent = 'Expiré';
        document.querySelector('button[type="submit"]').disabled = true;
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 1000);
    }
}, 1000);
</script>
</body>
</html>
