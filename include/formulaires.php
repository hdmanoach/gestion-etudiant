<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f6fa;
        }
        .card {
            max-width: 700px;
            margin: 50px auto;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            Formulaire d'inscription
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" id="nom" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="prenom" id="prenom" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="sexe" class="form-label">Sexe</label>
                        <select name="sexe" id="sexe" class="form-select">
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>
                    <div class="col-md-4">
                        <label for="lieu" class="form-label">Lieu de naissance</label>
                        <input type="text" class="form-control" name="lieu" id="lieu" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="tel" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" name="tel" id="tel" placeholder="+229 90 00 00 00" required>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea name="adresse" id="adresse" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="pays" class="form-label">Pays</label>
                    <select name="pays" id="pays" class="form-select">
                        <option value="Benin">Bénin</option>
                        <option value="Togo">Togo</option>
                        <option value="Niger">Niger</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Mali">Mali</option>
                        <option value="Cote d'Ivoire">Côte d'Ivoire</option>
                        <option value="Senegal">Sénégal</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Nigeria">Nigeria</option>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="acceptcondition" name="acceptcondition" required>
                    <label class="form-check-label" for="acceptcondition">
                        J'accepte les conditions d'utilisation
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
