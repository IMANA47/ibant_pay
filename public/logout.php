<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/../src/services/auth_service.php";
auth_logout();
header('Location: login.php');
exit;
