<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('connect.php');
require_once('include/log_action.php');
$currentUserId = $_SESSION['user_id'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // --- MODE MODIFICATION ---
    if (isset($_GET['x'])) {
        $id = intval($_GET['x']);
        $stmt = $connexion->prepare("SELECT * FROM etudiant WHERE id = ?");
        $stmt->execute([$id]);
        $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($etudiant) {
            $nom = $etudiant['nom'];
            $prenoms = $etudiant['prenoms'];
            $sexe = $etudiant['sexe'];
            $date_naissance = $etudiant['date_naissance'];
            $lieu_naissance = $etudiant['lieu_naissance'];
            $telephone = $etudiant['telephone'];
            $adresse = $etudiant['adresse'];
            $photo = $etudiant['photo'];
        }
    }
?>

<!-- === FORMULAIRE D’AJOUT / MODIFICATION === -->
<form action="index.php?page=ajouter" method="post" enctype="multipart/form-data" 
    class="bg-light shadow-sm border-0 rounded-4 p-4 mx-auto mt-4" style="max-width: 800px;">

    <div class="text-center mb-4">
        <h4 class="fw-bold <?php echo isset($_GET['x']) ? 'text-warning' : 'text-primary'; ?>">
            <i class="bi <?php echo isset($_GET['x']) ? 'bi-pencil-square' : 'bi-person-plus-fill'; ?> me-2"></i>
            <?php echo isset($_GET['x']) ? "Modifier un Étudiant" : "Ajout d’un Étudiant"; ?>
        </h4>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="nom" class="form-label fw-semibold">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" 
                value="<?php echo $nom ?? ''; ?>" required>
        </div>

        <div class="col-md-6">
            <label for="prenoms" class="form-label fw-semibold">Prénoms</label>
            <input type="text" class="form-control" id="prenoms" name="prenoms" 
                value="<?php echo $prenoms ?? ''; ?>" required>
        </div>

        <div class="col-md-6">
            <label for="sexe" class="form-label fw-semibold">Sexe</label>
            <select class="form-select" id="sexe" name="sexe" required>
                <option value="">-- Sélectionnez --</option>
                <option value="M" <?php if (($sexe ?? '') === 'M') echo 'selected'; ?>>Masculin</option>
                <option value="F" <?php if (($sexe ?? '') === 'F') echo 'selected'; ?>>Féminin</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="date_naissance" class="form-label fw-semibold">Date de Naissance</label>
            <input type="date" class="form-control" id="date_naissance" name="date_naissance" 
                   value="<?php echo $date_naissance ?? ''; ?>" required>
        </div>

        <div class="col-md-6">
            <label for="lieu_naissance" class="form-label fw-semibold">Lieu de Naissance</label>
            <input type="text" class="form-control" id="lieu_naissance" name="lieu_naissance" 
                value="<?php echo $lieu_naissance ?? ''; ?>" required>
        </div>

        <div class="col-md-6">
            <label for="telephone" class="form-label fw-semibold">Téléphone</label>
            <input type="tel" class="form-control" id="telephone" name="telephone" 
                value="<?php echo $telephone ?? ''; ?>" required>
        </div>

        <div class="col-12">
            <label for="adresse" class="form-label fw-semibold">Adresse</label>
            <textarea class="form-control" id="adresse" name="adresse" rows="2" required><?php echo $adresse ?? ''; ?></textarea>
        </div>

        <div class="col-12">
            <label for="photo" class="form-label fw-semibold">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            <?php if (!empty($photo)): ?>
                <div class="mt-2">
                    <img src="<?php echo $photo; ?>" alt="Photo actuelle" width="100" class="rounded shadow-sm">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <button type="submit" name="submit" 
                class="btn <?php echo isset($_GET['x']) ? 'btn-warning text-dark' : 'btn-primary'; ?> px-4 py-2">
            <i class="bi <?php echo isset($_GET['x']) ? 'bi-pencil' : 'bi-check-circle'; ?> me-2"></i>
            <?php echo isset($_GET['x']) ? 'Modifier' : 'Ajout Étudiant'; ?>
        </button>
        <?php if (isset($_GET['x'])): ?>
            <a href="index.php?page=liste" class="btn btn-outline-secondary px-4 py-2 ms-2">
                <i class="bi bi-x-circle me-2"></i>Annuler
            </a>
            <input type="hidden" name="cache" value="<?php echo htmlspecialchars($_GET['x']); ?>">
        <?php else: ?>
            <button type="reset" class="btn btn-outline-secondary px-4 py-2 ms-2">
                <i class="bi bi-x-circle me-2"></i>Annuler
            </button>
        <?php endif; ?>
    </div>
</form>

<?php
} else {
    // --- TRAITEMENT DU FORMULAIRE ---
    require_once('connect.php');

    $nom = $_POST['nom'];
    $prenoms = $_POST['prenoms'];
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $lieu_naissance = $_POST['lieu_naissance'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $photo_path = null;

    // --- Si une nouvelle photo a été téléversée ---
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $photo_path = $upload_dir . uniqid('photo_', true) . '.' . $ext;// Nom de fichier unique
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
        }
    }

    // --- Si c’est une modification ---
    if (!empty($_POST['cache'])) {
        $id = intval($_POST['cache']);

        // 🔸 Si aucune nouvelle photo, on garde l’ancienne
        if (empty($photo_path)) {
            $stmt = $connexion->prepare("SELECT photo FROM etudiant WHERE id = ?");
            $stmt->execute([$id]);
            $photo_path = $stmt->fetchColumn();
        }

        $sql = "UPDATE etudiant 
                SET nom = ?, prenoms = ?, sexe = ?, date_naissance = ?, lieu_naissance = ?, 
                    telephone = ?, adresse = ?, photo = ?, created_by = ?
                WHERE id = ?";
        $stmt = $connexion->prepare($sql);
        $ok = $stmt->execute([$nom, $prenoms, $sexe, $date_naissance, $lieu_naissance, $telephone, $adresse, $photo_path,$currentUserId , $id]);
    } else {
        // --- Sinon c’est un ajout ---
        if (empty($photo_path)) $photo_path = 'uploads/default.png'; // image par défaut
        $sql = "INSERT INTO etudiant (nom, prenoms, sexe, date_naissance, lieu_naissance, telephone, adresse, photo,created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $ok = $stmt->execute([$nom, $prenoms, $sexe, $date_naissance, $lieu_naissance, $telephone, $adresse, $photo_path,$currentUserId] );
    }

    // Récupération de l'utilisateur courant
    $currentUserId = $_SESSION['user_id'] ?? null;

    // Après ajout ou modification réussi
    if ($ok) {
        if (!empty($_POST['cache'])) {
            // Modification
            logAction(
                "modifier_etudiant",
                "Étudiant modifié : {$nom} {$prenoms}, ID : $id",
                $currentUserId
            );
        } else {
            // Ajout
            $lastId = $connexion->lastInsertId();
            logAction(
                "ajouter_etudiant",
                "Nouvel étudiant : {$nom} {$prenoms}, ID : $lastId",
                $currentUserId
            );
        }

        // Redirection
        header("Location: index.php?page=liste&success=1");
        exit;
    }


}
?>
