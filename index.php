<?php 
require_once __DIR__ . '/public/_init.php';
require_once __DIR__ . '/public/_header.php'; 
?>

<style>
  body { background: var(--bg-color); }
  .hero { text-align: center; padding: 60px 0 40px; }
  .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem; letter-spacing: -1px; color: var(--dark-red); }
  .hero p { font-size: 1.25rem; margin-bottom: 3rem; color: var(--text-muted); max-width: 600px; margin-left: auto; margin-right: auto; }
  
  .feature-card { border: 1px solid var(--border-color); border-radius: 20px; overflow: hidden; background: var(--white); transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%; border-bottom: 4px solid transparent; }
  .feature-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px -12px rgba(211, 47, 47, 0.2); border-color: var(--primary-red); border-bottom-color: var(--primary-red); }
  
  .icon-wrapper { width: 80px; height: 80px; display: inline-flex; align-items: center; justify-content: center; border-radius: 24px; font-size: 32px; margin-bottom: 24px; transition: all 0.3s; }
  
  .bg-soft-red { background: var(--light-red); color: var(--primary-red); }
  .bg-soft-dark { background: #f8f9fa; color: #343a40; }
  .bg-soft-danger { background: #fff5f5; color: #dc3545; }
  
  .feature-card:hover .icon-wrapper { background: var(--primary-red); color: white; transform: rotate(-5deg) scale(1.1); }
</style>

<div class="hero animate__animated animate__fadeIn">
  <h1>IBanT Pay</h1>
  <p class="lead">L'excellence dans la gestion académique et financière. Un outil moderne pour un suivi précis de votre établissement.</p>
</div>

<div class="row g-4 justify-content-center mb-5">
  <!-- Card Etudiants -->
  <div class="col-md-4">
    <a href="public/etudiants/etudiants.php" class="feature-card p-4 text-center">
      <div class="icon-wrapper bg-soft-red mx-auto">
        <i class="fa-solid fa-user-graduate"></i>
      </div>
      <h4 class="fw-bold mb-3">Étudiants</h4>
      <p class="text-muted flex-grow-1">Gestion complète du registre : inscriptions, photos de profil et dossiers personnels.</p>
      <div class="btn btn-danger mt-3 fw-bold small">Gérer les inscriptions <i class="fa-solid fa-arrow-right ms-1"></i></div>
    </a>
  </div>
  
  <!-- Card Classes -->
  <div class="col-md-4">
    <a href="public/classes/classes.php" class="feature-card p-4 text-center">
      <div class="icon-wrapper bg-soft-dark mx-auto">
        <i class="fa-solid fa-school"></i>
      </div>
      <h4 class="fw-bold mb-3">Classes</h4>
      <p class="text-muted flex-grow-1">Organisation des filières et niveaux. Suivi des effectifs par classe en temps réel.</p>
      <div class="btn btn-danger mt-3 fw-bold small">Voir les filières <i class="fa-solid fa-arrow-right ms-1"></i></div>
    </a>
  </div>
  
  <!-- Card Paiements -->
  <div class="col-md-4">
    <a href="public/paiement/paiements.php" class="feature-card p-4 text-center">
      <div class="icon-wrapper bg-soft-danger mx-auto">
        <i class="fa-solid fa-receipt"></i>
      </div>
      <h4 class="fw-bold mb-3">Paiements</h4>
      <p class="text-muted flex-grow-1">Dashboard financier : encaissements, statistiques de recettes et historique des reçus.</p>
      <div class="btn btn-danger mt-3 fw-bold small ">Consulter les finances <i class="fa-solid fa-arrow-right ms-1"></i></div>
    </a>
  </div>
</div>

<?php require_once __DIR__ . '/public/_footer.php'; ?>
