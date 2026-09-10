<?php
declare(strict_types=1);

function current_user_id(): ?int
{
    return isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
}

function is_logged_in(): bool
{
    return current_user_id() !== null;
}

function require_login(string $redirectTo = 'loginpage.php'): void
{
    if (!is_logged_in()) {
        header("Location: $redirectTo");
        exit;
    }
}

function login_user(int $id, string $nome, string $email): void
{
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = $id;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email'] = $email;
    unset($_SESSION['login_attempts'], $_SESSION['login_blocked_until']);
}

const LOGIN_MAX_ATTEMPTS = 5;
const LOGIN_BLOCK_SECONDS = 60;

function login_is_blocked(): bool
{
    $until = $_SESSION['login_blocked_until'] ?? 0;
    return $until > time();
}

function login_seconds_remaining(): int
{
    $until = $_SESSION['login_blocked_until'] ?? 0;
    return max(0, $until - time());
}

function register_failed_login(): void
{
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    if ($_SESSION['login_attempts'] >= LOGIN_MAX_ATTEMPTS) {
        $_SESSION['login_blocked_until'] = time() + LOGIN_BLOCK_SECONDS;
        $_SESSION['login_attempts'] = 0;
    }
}
