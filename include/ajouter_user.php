<?php
session_start();
require_once 'connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérification du rôle : seul l'admin peut ajouter un utilisateur
$role = $_SESSION['role'] ?? 0;
if ($role != 1) {
    header('Location: index.php');
    exit;
}

// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $userRole = (int)($_POST['role'] ?? 0);

    // Vérification simple des champs
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header('Location: index.php?page=admin_dashboard');
        exit;
    }

    // Vérifier si l'email existe déjà
    $stmt = $connexion->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        header('Location: index.php?page=admin_dashboard');
        exit;
    }

    // Hash du mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertion de l'utilisateur
    $stmt = $connexion->prepare("INSERT INTO utilisateur (username, email, password, role, last_activity) VALUES (:username, :email, :password, :role, NOW())");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $passwordHash,
        'role' => $userRole
    ]);

    // Optionnel : log de création
    $userId = $connexion->lastInsertId();
    $logEntry = date('Y-m-d H:i:s') . " - Utilisateur $userId ($username) ajouté par admin {$_SESSION['username']}\n";
    file_put_contents('system.log', $logEntry, FILE_APPEND);

    $_SESSION['success'] = "Utilisateur ajouté avec succès !";
    header('Location: index.php?page=admin_dashboard');
    exit;
}

// Si accès direct sans POST, redirection
header('Location: index.php?page=admin_dashboard');
exit;
