<form action="index.php?page=ajout" method="POST" enctype="multipart/form-data" 
    class="bg-light shadow-sm border-0 rounded-4 p-4 mx-auto mt-4" style="max-width: 800px;">

    <div class="text-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-person-plus-fill me-2"></i>Inscription d’un Étudiant
        </h4>
        <p class="text-muted mb-0">Veuillez remplir soigneusement les informations ci-dessous.</p>
    </div>

    <div class="row g-3">
        <!-- Nom -->
        <div class="col-md-6">
            <label for="nom" class="form-label fw-semibold">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom" required <?php  if(isset($_REQUEST['x'])){echo "value=$nom";} ?>>
        </div>

        <!-- Prénoms -->
        <div class="col-md-6">
            <label for="prenoms" class="form-label fw-semibold">Prénoms</label>
            <input type="text" class="form-control" id="prenoms" name="prenoms" placeholder="Entrez les prénoms" required <?php  if(isset($_REQUEST['x'])){echo "value=$prenoms";} ?>>
        </div>

        <!-- Sexe -->
        <div class="col-md-6">
            <label for="sexe" class="form-label fw-semibold">Sexe</label>
            <select class="form-select" id="sexe" name="sexe" required>
                <option value="" selected disabled>-- Sélectionnez --</option>
                <option value="M" <?php  if(isset($_REQUEST['x']) && $sexe == "M"){echo "selected";} ?>>Masculin</option>
                <option value="F" <?php  if(isset($_REQUEST['x']) && $sexe == "F"){echo "selected";} ?>>Féminin</option>
            </select>
        </div>

        <!-- Date de naissance -->
        <div class="col-md-6">
            <label for="date_naissance" class="form-label fw-semibold">Date de Naissance</label>
            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required <?php  if(isset($_REQUEST['x'])){echo "value=$date_naissance";} ?>>
        </div>

        <!-- Lieu de naissance -->
        <div class="col-md-6">
            <label for="lieu_naissance" class="form-label fw-semibold">Lieu de Naissance</label>
            <input type="text" class="form-control" id="lieu_naissance" name="lieu_naissance" placeholder="Ville, pays..." required <?php  if(isset($_REQUEST['x'])){echo "value=$lieu_naissance";} ?>>
        </div>

        <!-- Téléphone -->
        <div class="col-md-6">
            <label for="telephone" class="form-label fw-semibold">Téléphone</label>
            <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Ex : +225 0700000000" required <?php  if(isset($_REQUEST['x'])){echo "value=$telephone";} ?>>
        </div>

        <!-- Adresse -->
        <div class="col-12">
            <label for="adresse" class="form-label fw-semibold">Adresse</label>
            <textarea class="form-control" id="adresse" name="adresse" rows="2" placeholder="Adresse complète" required  <?php  if(isset($_REQUEST['x'])){echo "value=$adresse";} ?>></textarea>
        </div>

        <!-- Photo -->
        <div class="col-12">
            <label for="photo" class="form-label fw-semibold">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required <?php  if(isset($_REQUEST['x'])){echo "value=$photo";} ?>>
        </div>
    </div>

    <div class="text-center mt-4">
        <!-- Bouton principal (ajout ou modification) -->
        <button type="submit" 
                name="submit"
                class="btn btn-primary px-4 py-2">
            <i class="bi bi-check-circle me-2"></i>
            <?php
                if (isset($_REQUEST['x'])) {
                    echo "Modifier";
                } else {
                    echo "Ajout Étudiant";
                }
            ?>
        </button>

        <!-- Bouton d'annulation -->
        <button type="reset" class="btn btn-outline-secondary px-4 py-2 ms-2">
            <i class="bi bi-x-circle me-2"></i>Annuler
        </button>

        <!-- Champ caché si on est en mode modification -->
        <?php
            if (isset($_REQUEST['x'])) {
                echo "<input type='hidden' name='cache' value='" . htmlspecialchars($_REQUEST['x']) . "'>";
            }
        ?>
    </div>

</form>

<!-- Icônes Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Styles personnalisés -->
<style>
    body {
        background-color: #eef2f6; /* cohérent avec header et footer */
    }

    form {
        background-color: #fff;
        border-radius: 1rem;
    }

    .form-label {
        color: #333;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.3);
    }

    button.btn {
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    button.btn:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        form {
            padding: 20px;
            margin: 10px;
        }
    }
</style>
