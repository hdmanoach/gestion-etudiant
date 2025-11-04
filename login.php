<?php
require_once 'include/connect.php';
require_once 'include/log_action.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM utilisateur WHERE username = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            // Stocker l'utilisateur temporairement
            $_SESSION['pending_user'] = $user;

            // Générer un code de vérification à 6 chiffres
            $code = rand(100000, 999999);
            $_SESSION['verification_code'] = (string)$code;
            $_SESSION['verification_time'] = time();
            $_SESSION['step'] = 'code_sent';

            // Envoi mail via PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['SMTP_USER'];
                $mail->Password = $_ENV['SMTP_PASS'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($_ENV['SMTP_USER'], 'Gestion Etudiant');
                $mail->addAddress($user['email'], $user['username']);
                $mail->isHTML(true);
                $mail->Subject = 'Code de verification';
                $mail->Body = "<p>Bonjour <strong>{$user['username']}</strong>,</p>
                               <p>Voici votre code :</p>
                               <h2 style='text-align:center;color:#007bff;'>$code</h2>
                               <p>Ce code est valable 60 secondes.</p>";
                $mail->send();
                logAction('envoi_code_verification', "Code envoyé à {$user['email']} pour {$user['username']}");

            } catch (Exception $e) {
                $message = "Erreur d'envoi : {$mail->ErrorInfo}";
                logAction('erreur_envoi_code', "Erreur lors de l’envoi à {$user['email']} : {$mail->ErrorInfo}");
            }

            // Rediriger vers la vérification
            header("Location: verify_code.php");
            exit;

        } else {
            $message = "Nom d'utilisateur ou mot de passe incorrect.";
            logAction('login_failed', "Échec de connexion pour {$username}");
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - Gestion Étudiant</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow-sm p-4" style="width: 380px;">
    <h4 class="text-center mb-4 text-primary fw-bold">Connexion</h4>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
</div>

</body>
</html>
