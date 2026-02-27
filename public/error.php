<?php
$code = intval($_GET['code'] ?? 500);
$message = $_GET['message'] ?? "Une erreur inattendue s'est produite.";

// Inclure le header (même dossier)
require_once __DIR__ . '/header.php';
?>

<div class="container py-5">
    <div class="text-center p-5 shadow-lg rounded bg-white mx-auto" style="max-width: 600px;">
        
        <h1 class="display-3 text-danger fw-bold"><?= $code ?></h1>
        <p class="lead mb-4"><?= htmlspecialchars($message) ?></p>

        <a href="/ibant_gestion_etudiant/./index.php" class="btn btn-primary">
            Retour à l'accueil
        </a>

        <a href="javascript:history.back()" class="btn btn-outline-secondary ms-2">
            Page précédente
        </a>
    </div>
</div>

<?php
// Inclure le footer (même dossier)
require_once __DIR__ . '/footer.php';
?>
