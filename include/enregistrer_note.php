<?php
require_once('connect.php');
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer toutes les années disponibles depuis la table 'annee'
$annees = $connexion->query("SELECT id, libelle FROM annee ORDER BY libelle DESC")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les matières
$matieres = $connexion->query("SELECT id, libelle FROM matiere ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
?>
<form method="POST" action="index.php?page=traiter_note"
    class="bg-light shadow-sm border-0 rounded-4 p-4 mx-auto mt-4" style="max-width: 800px;">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-journal-text me-2"></i>
            Enregistrer une Note
        </h4>
    </div>

    <!-- Année scolaire -->
    <div class="mb-3">
        <label for="annee" class="form-label">Année scolaire :</label>
        <select name="annee_id" id="annee" class="form-select" required>
            <option value="">-- Sélectionner une année --</option>
            <?php foreach ($annees as $a): ?>
                <option value="<?= $a['id'] ?>">
                    <?= htmlspecialchars($a['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Étudiant (chargé dynamiquement via JS/Ajax) -->
    <div class="mb-3">
        <label for="etudiant" class="form-label">Étudiant :</label>
        <select name="inscription_id" id="etudiant" class="form-select" required>
            <option value="">-- Choisissez d'abord une année --</option>
        </select>
    </div>

    <!-- Matière -->
    <div class="mb-3">
        <label for="matiere" class="form-label">Matière :</label>
        <select name="matiere_id" id="matiere" class="form-select" required>
            <option value="">-- Sélectionner une matière --</option>
            <?php foreach ($matieres as $m): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Note -->
    <div class="mb-3">
        <label for="note" class="form-label">Note :</label>
        <select name="val_note" id="note" class="form-select" required>
            <?php for ($n = 0; $n <= 20; $n += 0.25): ?>
                <option value="<?= number_format($n, 2) ?>"><?= number_format($n, 2) ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary px-4"> Enregistrer</button>
    </div>
</form>

<!-- Script pour charger dynamiquement les étudiants selon l'année sélectionnée -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#annee').on('change', function() {
        const anneeId = $(this).val(); // Récupère l'ID de l'année sélectionnée
        if (!anneeId) {
            $('#etudiant').html('<option value="">-- Choisissez d\'abord une année --</option>');
            return;
        }

        // Appel AJAX
        $.ajax({
            url: 'include/load_etudiants.php',   // Le fichier à exécuter côté serveur
            method: 'GET',               // Méthode utilisée
            data: { annee_id: anneeId }, // Donnée envoyée au fichier PHP
            success: function(data) {    // Fonction exécutée à la réception des données
                $('#etudiant').html(data); // Injection du HTML reçu dans la liste des étudiants
            }
        });
    });

</script>
