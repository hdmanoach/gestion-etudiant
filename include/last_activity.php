<?php
require_once 'connect.php';
if (isset($_SESSION['user_id'])) {
    $stmt = $connexion->prepare("UPDATE utilisateur SET last_activity = NOW() WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}
