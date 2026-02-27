<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/_init.php";
$f = flash_get();

// compute project root URL (ensure it points to the /public directory)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if (str_ends_with($scriptDir, '/public') || str_contains($scriptDir, '/public/')) {
    // Si on est déjà dans public ou un sous-dossier, $root est le chemin vers public
    $root = preg_replace('#/public(/.*)?$#', '/public', $scriptDir);
} else {
    // Si on est à la racine (index.php), $root doit être /le-dossier-du-projet/public
    $root = rtrim($scriptDir, '/') . '/public';
}
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $root; ?>/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo $root; ?>/css/style.css">
  <!-- icon  -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $root; ?>/images/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $root; ?>/images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $root; ?>/images/favicon-16x16.png">
  <link rel="manifest" href="<?php echo $root; ?>/images/site.webmanifest">
  <title>IBanT Pay</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-5" href="<?php echo rtrim($root, '/'); ?>/../index.php"><i class="fa-solid fa-graduation-cap me-2"></i>IBanT  Pay</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link fw-bold small" href="<?php echo rtrim($root, '/'); ?>/../index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link fw-bold small" href="<?php echo $root; ?>/classes/classes.php">Classes</a></li>
          <li class="nav-item"><a class="nav-link fw-bold small" href="<?php echo $root; ?>/etudiants/etudiants.php">Etudiants</a></li>
          <li class="nav-item"><a class="nav-link fw-bold small" href="<?php echo $root; ?>/paiement/paiements.php">Paiements</a></li>

        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
    <?php if ($f): ?>
      <div class="alert alert-<?php echo $f['type'] === 'danger' ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($f['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>