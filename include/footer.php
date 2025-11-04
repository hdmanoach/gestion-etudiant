<footer class="bg-light text-center text-muted py-3 border-top shadow-sm fixed-bottom w-100">
    <div class="container-fluid">
        <p class="mb-1">
            &copy; <?= date('Y'); ?> <span class="text-primary fw-semibold">Gestion Étudiant</span>. Tous droits réservés.
        </p>
        <small>
            Développé avec <i class="bi bi-heart-fill text-danger"></i> par <strong>Manoach HOSSOU DODO</strong>
        </small>
    </div>
</footer>

<!-- Icônes Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 1050;
        border-radius: 0;
        margin: 0;
        transition: all 0.3s ease;
    }

    /* Ajoute une marge en bas pour éviter que le contenu soit caché par le footer */
    body {
        padding-bottom: 70px; /* ajuster selon la hauteur du footer */
    }

    footer p, footer small {
        font-size: 0.9rem;
    }

    footer a {
        color: #0d6efd;
        text-decoration: none;
    }

    footer a:hover {
        text-decoration: underline;
    }
</style>
