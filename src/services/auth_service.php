<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/user_repository.php';

/**
 * Attempt to log in a user. Returns true on success, false on failure.
 */
function auth_login(string $username, string $password): bool
{
    $user = user_find_by_username($username);
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['auth_user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'nom'      => $user['nom'],
            'role'     => $user['role'],
        ];
        return true;
    }
    return false;
}

/**
 * Destroy the user session and log out.
 */
function auth_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

/**
 * Check if a user is currently authenticated.
 */
function auth_check(): bool
{
    return isset($_SESSION['auth_user']);
}

/**
 * Get the currently authenticated user info, or null if not logged in.
 */
function auth_user(): ?array
{
    return $_SESSION['auth_user'] ?? null;
}
