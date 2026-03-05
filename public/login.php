<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . "/../src/repositories/user_repository.php";
require_once __DIR__ . "/../src/services/auth_service.php";
require_once __DIR__ . "/../src/services/flash_service.php";

// If already logged in, redirect to home
if (auth_check()) {
    header('Location: ../index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (auth_login($username, $password)) {
        header('Location: ../index.php');
        exit;
    } else {
        $error = 'Identifiants incorrects. Veuillez réessayer.';
    }
}

// compute $root for assets
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$root = preg_replace('#/public(/.*)?$#', '/public', $scriptDir);
if (!str_contains($root, '/public')) {
    $root = rtrim($root, '/') . '/public';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IBanT Pay – Connexion</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo $root; ?>/css/style.css">
  <style>
    :root {
      --primary-red: #d32f2f;
      --dark-red: #b71c1c;
      --light-red: #ffebee;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #f8f9fa 0%, #dcdfe2ff 40%, #dcdfe2ff 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      position: relative;
      overflow: hidden;
    }

    /* Animated background blobs */
    body::before, body::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      opacity: 0.15;
      animation: float 8s ease-in-out infinite;
    }
    body::before {
      width: 500px; height: 500px;
      background: radial-gradient(circle, #d32f2f, transparent);
      top: -150px; right: -100px;
      animation-delay: 0s;
    }
    body::after {
      width: 400px; height: 400px;
      background: radial-gradient(circle, #b71c1c, transparent);
      bottom: -100px; left: -100px;
      animation-delay: 4s;
    }
    @keyframes float {
      0%, 100% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(20px, -30px) scale(1.05); }
    }

    .login-card {
      background: rgba(48, 48, 48, 0.05);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 24px;
      padding: 48px 44px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 32px 64px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
      position: relative;
      z-index: 10;
      animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(40px) scale(0.97); }
      to   { opacity: 1; transform: translateY(0)   scale(1);    }
    }

    .brand-logo {
      width: 72px; height: 72px;
      background: linear-gradient(135deg, var(--dark-red), var(--primary-red));
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 24px;
      font-size: 32px; color: #fff;
      box-shadow: 0 12px 28px rgba(211,47,47,0.45);
      position: relative;
    }
    .brand-logo::after {
      content: '';
      position: absolute; inset: -3px;
      border-radius: 23px;
      background: linear-gradient(135deg, rgba(211,47,47,0.5), transparent);
      z-index: -1;
    }

    .login-title {
      font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px;
      color: #fff; text-align: center; margin-bottom: 4px;
    }
    .login-subtitle {
      text-align: center;
      color: rgba(20, 20, 20, 0.5);
      font-size: 0.9rem;
      margin-bottom: 36px;
    }

    .form-label {
      color: rgba(6, 6, 6, 0.75);
      font-size: 0.82rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .input-group-text {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.15);
      border-right: none;
      color: rgba(255,255,255,0.5);
    }

    .form-control {
      background: rgba(255,255,255,0.08) !important;
      border: 1px solid rgba(255,255,255,0.15);
      border-left: none;
      color: #fff !important;
      font-size: 0.97rem;
      transition: all 0.3s;
      padding: 12px 16px;
    }
    .form-control:focus {
      background: rgba(255,255,255,0.12) !important;
      border-color: var(--primary-red) !important;
      box-shadow: none !important;
      color: #fff !important;
    }
    .form-control::placeholder { color: rgba(255,255,255,0.3); }
    .input-group:focus-within .input-group-text {
      border-color: var(--primary-red);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--dark-red), var(--primary-red));
      border: none;
      border-radius: 12px;
      padding: 14px;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: 0.3px;
      color: #fff;
      width: 100%;
      transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
      box-shadow: 0 8px 20px rgba(211,47,47,0.35);
      margin-top: 8px;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 30px rgba(211,47,47,0.5);
      color: #fff;
    }
    .btn-login:active { transform: translateY(0); }

    .alert-custom {
      background: rgba(211,47,47,0.15);
      border: 1px solid rgba(211,47,47,0.4);
      color: #ff8a80;
      border-radius: 12px;
      padding: 12px 16px;
      margin-bottom: 24px;
      font-size: 0.9rem;
      display: flex; align-items: center; gap: 10px;
    }

    .footer-text {
      text-align: center;
      margin-top: 32px;
      color: rgba(255,255,255,0.3);
      font-size: 0.8rem;
    }

    .input-reveal { cursor: pointer; }

    /* Particles */
    .particle {
      position: fixed;
      width: 4px; height: 4px;
      border-radius: 50%;
      background: rgba(211,47,47,0.4);
      animation: rise linear infinite;
      pointer-events: none;
    }
    @keyframes rise {
      0%   { transform: translateY(100vh) scale(0); opacity: 0; }
      10%  { opacity: 1; }
      90%  { opacity: 0.6; }
      100% { transform: translateY(-10vh) scale(1.5); opacity: 0; }
    }
  </style>
</head>
<body>

<!-- Floating particles -->
<?php for ($i = 0; $i < 15; $i++): ?>
  <div class="particle" style="
    left: <?= rand(0, 100) ?>%;
    width: <?= rand(3, 7) ?>px;
    height: <?= rand(3, 7) ?>px;
    animation-duration: <?= rand(8, 20) ?>s;
    animation-delay: <?= rand(0, 10) ?>s;
    opacity: <?= rand(2, 8) / 10 ?>;
  "></div>
<?php endfor; ?>

<div class="login-card">

  <!-- Brand -->
  <div class="brand-logo">
    <i class="fa-solid fa-graduation-cap"></i>
  </div>
  <h1 class="login-title">IBanT Pay</h1>
  <p class="login-subtitle">Portail de gestion académique & financière</p>

  <!-- Error message -->
  <?php if ($error): ?>
    <div class="alert-custom">
      <i class="fa-solid fa-circle-exclamation"></i>
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <!-- Login Form -->
  <form method="POST" action="">
    <div class="mb-4">
      <label for="username" class="form-label">Nom d'utilisateur</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-user fa-sm"></i></span>
        <input
          type="text"
          id="username"
          name="username"
          class="form-control"
          placeholder="Votre identifiant"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
          autocomplete="username"
          required
          autofocus
        >
      </div>
    </div>

    <div class="mb-4">
      <label for="password" class="form-label">Mot de passe</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-lock fa-sm"></i></span>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="Votre mot de passe"
          autocomplete="current-password"
          required
        >
        <span class="input-group-text input-reveal" onclick="togglePassword()" title="Afficher/masquer">
          <i class="fa-solid fa-eye fa-sm" id="eye-icon"></i>
        </span>
      </div>
    </div>

    <button type="submit" class="btn-login">
      <i class="fa-solid fa-right-to-bracket me-2"></i>Se connecter
    </button>
  </form>

  <div class="footer-text">
    &copy; <?= date('Y') ?> IBanT Pay &mdash; Accès sécurisé
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('eye-icon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}
</script>
</body>
</html>
