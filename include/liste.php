<?php
require_once 'include/connect.php';
require_once('include/log_action.php'); // 🔹 Inclure le helper pour loguer les actions

// Récupération des étudiants avec classe et année d'inscription
$req = "
    SELECT e.*, c.libelle AS classe, a.libelle AS annee
    FROM etudiant e
    LEFT JOIN inscription i ON i.etudiant_id = e.id
    LEFT JOIN classe c ON i.classe_id = c.id
    LEFT JOIN annee a ON i.annee_id = a.id
    ORDER BY e.nom, e.prenoms
";
$result = $connexion->query($req);
$etudiants = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="text-center mt-4 text-primary fw-bold">
    <i class="bi bi-people-fill me-2"></i>Liste des Étudiants
</h2>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success text-center mt-4">Étudiant enregistré avec succès !</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center mt-4">Erreur lors de l’enregistrement.</div>
<?php endif; ?>

<div class="table-responsive mt-4">
<?php if (count($etudiants) > 0): ?>
    <table class="table table-hover align-middle table-bordered mx-auto shadow-sm" style="max-width: 1000px;">
        <thead class="table-dark text-center">
            <tr>
                <th>Nom</th>
                <th>Prénoms</th>
                <th>Sexe</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Classe</th>
                <th>Année</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach ($etudiants as $etudiant): ?>
                <tr class="student-row" data-bs-toggle="modal" data-bs-target="#studentModal-<?php echo $etudiant['id']; ?>">
                    <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['prenoms']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['sexe']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['adresse']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['classe'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['annee'] ?? '—'); ?></td>
                    <td>
                        <?php if ($etudiant['photo']): ?>
                            <img src="<?php echo htmlspecialchars($etudiant['photo']); ?>" width="45" height="45" class="rounded-circle shadow-sm">
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?page=ajouter&x=<?php echo $etudiant['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal-<?php echo $etudiant['id']; ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>

                <!-- === MODALE ÉTUDIANT === -->
                <div class="modal fade" id="studentModal-<?php echo $etudiant['id']; ?>" tabindex="-1" aria-labelledby="studentModalLabel-<?php echo $etudiant['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header bg-primary text-white rounded-top-4">
                                <h5 class="modal-title fw-semibold" id="studentModalLabel-<?php echo $etudiant['id']; ?>">
                                    Détails de l'Étudiant
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row align-items-start g-4">
                                    <!-- Photo à gauche -->
                                    <div class="col-md-4 text-center">
                                        <div class="border rounded-3 shadow-sm p-2 bg-light">
                                            <img src="<?php echo htmlspecialchars($etudiant['photo'] ?: 'uploads/default.png'); ?>" 
                                                alt="Photo de <?php echo htmlspecialchars($etudiant['nom']); ?>" 
                                                class="img-fluid rounded mb-2" style="max-height: 180px; object-fit: cover;">
                                        </div>

                                        <div class="mt-3 text-start">
                                            <h6 class="fw-bold text-uppercase text-primary mb-1">Nom & Prénoms :</h6>
                                            <p class="mb-2"><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenoms']); ?></p>

                                            <h6 class="fw-bold text-uppercase text-primary mb-1">Date de Naissance :</h6>
                                            <p class="mb-2"><?php echo htmlspecialchars($etudiant['date_naissance'] ?? '—'); ?></p>
                                        </div>
                                    </div>

                                    <!-- Informations à droite -->
                                    <div class="col-md-8">
                                        <div class="p-3 rounded bg-light border h-100">
                                            <h5 class="fw-bold text-secondary mb-3">
                                                <i class="bi bi-info-circle-fill me-2"></i>Informations Générales
                                            </h5>

                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <strong>Classe :</strong><br>
                                                    <?php echo htmlspecialchars($etudiant['classe'] ?? '—'); ?>
                                                </div>
                                                <div class="col-6">
                                                    <strong>Année :</strong><br>
                                                    <?php echo htmlspecialchars($etudiant['annee'] ?? '—'); ?>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <strong>Sexe :</strong><br>
                                                    <?php echo htmlspecialchars($etudiant['sexe']); ?>
                                                </div>
                                                <div class="col-6">
                                                    <strong>Téléphone :</strong><br>
                                                    <?php echo htmlspecialchars($etudiant['telephone']); ?>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <strong>Adresse :</strong><br>
                                                <?php echo nl2br(htmlspecialchars($etudiant['adresse'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- === MODALE CONFIRMATION DE SUPPRESSION === -->
                <div class="modal fade" id="deleteModal-<?php echo $etudiant['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel-<?php echo $etudiant['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header bg-danger text-white rounded-top-4">
                                <h5 class="modal-title" id="deleteModalLabel-<?php echo $etudiant['id']; ?>">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmation de suppression
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="<?php echo htmlspecialchars($etudiant['photo'] ?: 'uploads/default.png'); ?>"
                                    alt="Photo"
                                    class="rounded-circle mb-3 shadow-sm"
                                    width="80" height="80" style="object-fit: cover;">
                                <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenoms']); ?></h5>
                                <p class="text-muted">Souhaitez-vous vraiment supprimer cet étudiant ?<br>
                                Cette action est <strong>irréversible</strong>.</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <form action="index.php?page=supprimer" method="post">
                                    <input type="hidden" name="id" value="<?php echo $etudiant['id']; ?>">
                                    <button type="submit" class="btn btn-danger px-4">
                                        <i class="bi bi-trash me-2"></i>Supprimer
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-2"></i>Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-warning text-center mt-4 shadow-sm" style="max-width: 600px; margin: auto;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Aucun étudiant n’a été trouvé dans la base de données.
    </div>
<?php endif; ?>
</div>

<style>
    .student-row:hover {
        background-color: #f0f8ff;
        cursor: pointer;
        transition: 0.2s ease-in-out;
    }
    .modal-content {
        border-radius: 15px;
    }
</style>
