<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . "/../src/repositories/classe_repository.php";
require_once __DIR__ . "/../src/repositories/paiement_repository.php";
require_once __DIR__ . "/../src/repositories/etudiant_repository.php";
require_once __DIR__ . "/../src/repositories/user_repository.php";

require_once __DIR__ . "/../src/services/classe_services.php";
require_once __DIR__ . "/../src/services/paiement_services.php";
require_once __DIR__ . "/../src/services/etudiant_services.php";
require_once __DIR__ . "/../src/services/flash_service.php";
require_once __DIR__ . "/../src/services/csrf_services.php";
require_once __DIR__ . "/../src/services/auth_service.php";

// Auth guard: redirect to login if not authenticated
$currentScript = basename($_SERVER['SCRIPT_NAME']);
if (!auth_check() && $currentScript !== 'login.php') {
    // Determine the path to login.php relative to the project root
    $loginUrl = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    // Navigate up to /public if needed
    $loginUrl = preg_replace('#/public(/.*)?$#', '/public', $loginUrl);
    // If the script is at the root (index.php), point to /public
    if (!str_contains($loginUrl, '/public')) {
        $loginUrl = rtrim($loginUrl, '/') . '/public';
    }
    header('Location: ' . $loginUrl . '/login.php');
    exit;
}
