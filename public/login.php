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
    <!-- icon  -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $root; ?>/images/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $root; ?>/images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $root; ?>/images/favicon-16x16.png">
  <link rel="manifest" href="<?php echo $root; ?>/images/site.webmanifest">
  <style>
    body {
      background-color: var(--bg-color);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      overflow: hidden;
      position: relative;
    }

    /* Subtle background animation matching the app's clean look */
    .bg-decoration {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      z-index: -1;
      overflow: hidden;
    }
    .blob {
      position: absolute;
      width: 500px; height: 500px;
      background: var(--light-red);
      filter: blur(80px);
      border-radius: 50%;
      opacity: 0.5;
      animation: float 20s infinite alternate;
    }
    .blob-1 { top: -100px; right: -100px; }
    .blob-2 { bottom: -100px; left: -100px; animation-delay: -10s; }

    @keyframes float {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(30px, 50px) scale(1.1); }
    }

    .login-card {
      background: var(--white);
      border: 1px solid var(--border-color);
      border-radius: 24px;
      padding: 3rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.05);
      z-index: 10;
      animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInScale {
      from { opacity: 0; transform: scale(0.95) translateY(10px); }
      to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .brand-logo {
      width: 64px; height: 64px;
      background: linear-gradient(135deg, var(--dark-red), var(--primary-red));
      border-radius: 18px;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 28px; color: #fff;
      box-shadow: 0 10px 20px rgba(211,47,47,0.25);
    }

    .login-title {
      font-size: 1.75rem; font-weight: 800; color: var(--text-main);
      text-align: center; margin-bottom: 0.5rem; letter-spacing: -0.5px;
    }

    .login-subtitle {
      text-align: center; color: var(--text-muted);
      font-size: 0.95rem; margin-bottom: 2.5rem;
    }

    .form-label {
      font-weight: 600; color: var(--text-main); font-size: 0.85rem;
      margin-bottom: 0.5rem; display: block;
    }

    .input-group-text {
      background-color: #f8f9fa;
      border-color: var(--border-color);
      color: var(--text-muted);
      border-right: none;
    }

    .form-control {
      border-color: var(--border-color);
      padding: 0.75rem 1rem;
      font-size: 1rem;
      border-left: none;
    }

    .form-control:focus {
      border-color: var(--primary-red);
      box-shadow: 0 0 0 4px rgba(211,47,47,0.1);
    }

    .input-group:focus-within .input-group-text {
      border-color: var(--primary-red);
      color: var(--primary-red);
    }

    .btn-login {
      background: var(--primary-red);
      border: none;
      border-radius: 12px;
      padding: 0.85rem;
      font-weight: 700;
      color: #fff;
      width: 100%;
      margin-top: 1rem;
      transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
      box-shadow: 0 8px 15px rgba(211,47,47,0.2);
    }

    .btn-login:hover {
      background: var(--dark-red);
      transform: translateY(-2px);
      box-shadow: 0 12px 20px rgba(211,47,47,0.3);
    }

    .alert-custom {
      background: #fff5f5;
      border: 1px solid #feb2b2;
      color: #c53030;
      border-radius: 12px;
      padding: 0.75rem 1rem;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      display: flex; align-items: center; gap: 0.5rem;
    }

    .footer-text {
      text-align: center; margin-top: 2rem;
      color: var(--text-muted); font-size: 0.8rem;
    }
  </style>
</head>
<body>

<div class="bg-decoration">
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
</div>

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
