<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/databas.php';

function user_find_by_username(string $username): ?array
{
    $stmt = db()->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    return $row ?: null;
}
