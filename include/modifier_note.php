<?php
    require_once('connect.php');
    require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- Vérifier si une note est demandée ---
    $note_id = $_GET['id'] ?? null;

    if (!$note_id) {
        echo "<div class='alert alert-danger text-center'>Aucune note spécifiée.</div>";
        exit;
    }

    // --- Charger les infos de la note ---
    $sql = "SELECT n.id, n.val_note, n.inscription_id, n.matiere_id, 
                e.nom, e.prenoms, a.libelle AS annee, c.libelle AS classe, m.libelle AS matiere
            FROM note n
            INNER JOIN inscription i ON i.id = n.inscription_id
            INNER JOIN etudiant e ON e.id = i.etudiant_id
            INNER JOIN annee a ON a.id = i.annee_id
            INNER JOIN classe c ON c.id = i.classe_id
            INNER JOIN matiere m ON m.id = n.matiere_id
            WHERE n.id = :id";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':id' => $note_id]);
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$note) {
        echo "<div class='alert alert-warning text-center'>Note introuvable.</div>";
        exit;
    }

    // --- Si le formulaire est soumis ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $val_note = $_POST['val_note'];

        $update = $connexion->prepare("UPDATE note SET val_note = :val_note WHERE id = :id");
        $ok = $update->execute([
            ':val_note' => $val_note,
            ':id' => $note_id
        ]);

        if ($ok) {
            // 🔹 Log de la modification
            $action = "modifier_note";
            $description = "Note modifiée pour l'étudiant {$note['nom']} {$note['prenoms']} en matière {$note['matiere']}. Nouvelle valeur : {$val_note}, ancienne valeur : {$note['val_note']}";
            logAction($action, $description, $_SESSION['user_id'] ?? null);

            echo "<script>
                alert('✅ Note mise à jour avec succès !');
                window.location.href = 'index.php?page=consulter_notes';
            </script>";
            exit;
        } else {
            echo "<div class='alert alert-danger text-center'>Erreur lors de la mise à jour.</div>";
        }
    }
    ?>
    <form method="POST" class="bg-light shadow-sm border-0 rounded-4 p-4 mx-auto" style="max-width: 500px;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-warning">
                <i class="bi bi-pencil-square me-2"></i>
                Modifier une Note
            </h4>
        </div>

        <div class="mb-3">
            <label class="form-label">Étudiant :</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($note['nom'] . ' ' . $note['prenoms']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Classe :</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($note['classe']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Année scolaire :</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($note['annee']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Matière :</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($note['matiere']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note :</label>
            <select name="val_note" id="note" class="form-select" required>
                <?php for ($n = 0; $n <= 20; $n += 0.25): ?>
                    <option value="<?= number_format($n, 2) ?>" <?= ($note['val_note'] == $n) ? 'selected' : '' ?>>
                        <?= number_format($n, 2) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-warning text-dark px-4">
                <i class="bi bi-save"></i> Enregistrer la modification
            </button>
            <a href="index.php?page=consulter_notes" class="btn btn-secondary ms-2 px-4">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </form>
