<?php

declare(strict_types=1);

/**
 * Auth.php
 *
 * Authentication state & identity helpers.
 *
 * Responsibilities:
 * - Session lifecycle
 * - Remember-me
 * - User context (identity + role + permissions)
 * - Company context (tenant + subscription)
 *
 * DB is source of truth.
 * Session is a cache.
 */

/* =====================================================
   LOGIN STATE
===================================================== */

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
}

/* =====================================================
   COMPLETE LOGIN
===================================================== */

function completeLogin(int $userId, bool $rememberMe = false): void
{
    session_regenerate_id(true);

    $_SESSION['user_id']    = $userId;
    $_SESSION['logged_in']  = true;
    $_SESSION['login_time'] = time();

    // Reset cached contexts
    unset($_SESSION['user_context'], $_SESSION['company_context']);

    if ($rememberMe) {
        createRememberMeToken($userId);
    }
}

/* =====================================================
   LOGOUT
===================================================== */

function logout(): void
{
    destroyRememberMeToken();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

/* =====================================================
   REMEMBER ME
===================================================== */

function createRememberMeToken(int $userId): void
{
    $token = bin2hex(random_bytes(32));
    $hash  = password_hash($token, PASSWORD_DEFAULT);

    EjecutarSQL(
        $GLOBALS['conn'],
        "UPDATE usuarios SET remember_token = ? WHERE id = ?",
        [$hash, $userId]
    );

    setcookie(
        'remember_me',
        $userId . ':' . $token,
        [
            'expires'  => time() + (86400 * 30),
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );
}

/* =====================================================
   AUTO LOGIN VIA COOKIE
===================================================== */

function autoLoginFromCookie(): bool
{
    if (isLoggedIn() || empty($_COOKIE['remember_me'])) {
        return false;
    }

    [$userId, $token] = explode(':', $_COOKIE['remember_me'], 2);

    if (!$userId || !$token) {
        return false;
    }

    $hash = ObtenerValor(
        $GLOBALS['conn'],
        "SELECT remember_token FROM usuarios WHERE id = ?",
        [(int)$userId]
    );

    if (!$hash || !password_verify($token, $hash)) {
        destroyRememberMeToken();
        return false;
    }

    completeLogin((int)$userId, true);
    return true;
}

/* =====================================================
   USER CONTEXT (IDENTITY + ROLE + PERMISSIONS)
===================================================== */

function currentUser(bool $forceRefresh = false): ?array
{
    if (!$forceRefresh && !empty($_SESSION['user_context'])) {
        return $_SESSION['user_context'];
    }

    return loadUserContext();
}

function loadUserContext(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    global $conn;

    $user = ObtenerPrimerRegistro(
        $conn,
        "
        SELECT
            u.id,
            u.name,
            u.email,
            u.user_name,
            u.id_empresa,
            u.estado,

            -- Rol / permisos
            u.permiso     AS role_id,
            r.Descripcion AS role_name,
            r.Reportes,
            r.Usuarios,
            r.Clientes,
            r.Ordenes,
            r.Actividad,
            r.Financiero

        FROM usuarios u
        LEFT JOIN roles r ON r.ID = u.permiso
        WHERE u.id = ?
        LIMIT 1
        ",
        [(int)$_SESSION['user_id']]
    );

    if (!$user) {
        return null;
    }

    $_SESSION['user_context'] = $user;
    return $user;
}

/* =====================================================
   COMPANY CONTEXT (TENANT + SUBSCRIPTION)
===================================================== */

function currentCompany(bool $forceRefresh = false): ?array
{
    if (!$forceRefresh && !empty($_SESSION['company_context'])) {
        return $_SESSION['company_context'];
    }

    $user = currentUser();
    if (!$user) {
        return null;
    }

    global $conn;

    $company = ObtenerPrimerRegistro(
        $conn,
        "
        SELECT
            e.id_empresa         AS id,
            e.nombre             AS name,
            e.nombre_base_datos  AS db,
            e.estado             AS estado
        FROM empresas e
        WHERE e.id_empresa = ?
        LIMIT 1
        ",
        [(int)$user['id_empresa']]
    );

    if (!$company) {
        return null;
    }

    $subscription = getActiveSubscriptionByEmpresa((int)$company['id']);

    $company['plan']   = $subscription['id_plan'] ?? null;
    $company['active'] = $subscription !== null;

    $_SESSION['company_context'] = $company;
    return $company;
}

/* =====================================================
   SUBSCRIPTION
===================================================== */

function getActiveSubscriptionByEmpresa(int $empresaId): ?array
{
    global $conn;

    return ObtenerPrimerRegistro(
        $conn,
        "
        SELECT
            id_suscripcion,
            id_plan,
            ciclo_facturacion,
            fecha_inicio,
            fecha_fin,
            fecha_proximo_pago,
            estado,
            renovacion_automatica,
            precio_actual
        FROM suscripciones
        WHERE id_empresa = ?
          AND estado = 'activa'
          AND fecha_inicio <= CURDATE()
          AND fecha_fin >= CURDATE()
        ORDER BY fecha_fin DESC
        LIMIT 1
        ",
        [$empresaId]
    ) ?: null;
}

/* =====================================================
   CONTEXT INVALIDATION
===================================================== */

function refreshAuthContext(): void
{
    unset($_SESSION['user_context'], $_SESSION['company_context']);
}

/* =====================================================
   REMEMBER TOKEN CLEANUP
===================================================== */

function destroyRememberMeToken(): void
{
    if (empty($_COOKIE['remember_me'])) {
        return;
    }

    [$userId] = explode(':', $_COOKIE['remember_me'], 2);

    if ($userId) {
        EjecutarSQL(
            $GLOBALS['conn'],
            "UPDATE usuarios SET remember_token = NULL WHERE id = ?",
            [(int)$userId]
        );
    }

    setcookie('remember_me', '', time() - 3600, '/');
}

function userInfo(): ?array
{
    $user = currentUser();
    $company = currentCompany();

    if (!$user) {
        return null;
    }

    // Attach company as a sub-object
    $user['company'] = $company;

    return $user;
}
