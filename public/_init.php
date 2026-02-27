<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . "/../src/repositories/classe_repository.php";
require_once __DIR__ . "/../src/repositories/paiement_repository.php";
require_once __DIR__ . "/../src/repositories/etudiant_repository.php";

require_once __DIR__ . "/../src/services/classe_services.php";
require_once __DIR__ . "/../src/services/paiement_services.php";
require_once __DIR__ . "/../src/services/etudiant_services.php";

require_once __DIR__ . "/../src/services/flash_service.php";
require_once __DIR__ . "/../src/services/csrf_services.php";
?>