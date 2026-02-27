<?php

declare(strict_types=1);

function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}
function csrf_check(?string $token = null): bool
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) return false;
    if ($token === null) {
        $token = $_POST['_csrf'] ?? $_POST['_csrf_token'] ?? null;
    }
    if ($token === null) return false;
    return hash_equals($_SESSION['csrf_token'], (string)$token);
}

function csrf_token_field(): string
{
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return "<input type=\"hidden\" name=\"_csrf\" value=\"$t\">";
}
