<?php
class AuthManager
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function login(string $username, string $password, bool $rememberMe): array
    {
        $user = ObtenerPrimerRegistro(
            $this->db,
            "
            SELECT u.id, u.password, u.mfa_habilitado, u.id_empresa,
                   u.intentos_fallidos, u.bloqueado_hasta, u.name,
                   e.nombre as empresa_nombre, e.nombre_base_datos
            FROM usuarios u
            LEFT JOIN empresas e ON e.id_empresa = u.id_empresa
            WHERE (u.user_name = ? OR u.email = ?)
              AND u.estado = 'activo'
            LIMIT 1
            ",
            [$username, $username]
        );

        if (!$user) {
            return ['success' => false, 'message' => 'Credenciales incorrectas'];
        }

        if (!empty($user['bloqueado_hasta']) && strtotime($user['bloqueado_hasta']) > time()) {
            return [
                'success' => false,
                'message' => 'Cuenta temporalmente bloqueada',
                'blocked_until' => $user['bloqueado_hasta']
            ];
        }

        if (!password_verify($password, $user['password'])) {
            $this->handleFailedAttempt($user);
            return ['success' => false, 'message' => 'Credenciales incorrectas'];
        }

        // Check and create tenant database if needed
        $dbCheckResult = $this->ensureTenantDatabase($user);
        if (!$dbCheckResult['success']) {
            return [
                'success' => false,
                'message' => 'Error al preparar el sistema: ' . $dbCheckResult['message']
            ];
        }

        $this->handleSuccessfulLogin($user['id']);

        completeLogin((int)$user['id'], $rememberMe);

        if ((int)$user['mfa_habilitado'] === 1) {
            return [
                'success' => true,
                'requires_mfa' => true,
                'redirect' => 'mfa-verify.php'
            ];
        }

        return [
            'success' => true,
            'redirect' => 'lavacar/dashboard.php',
            'database_created' => $dbCheckResult['created'] ?? false
        ];
    }

    private function handleFailedAttempt(array $user): void
    {
        $newAttempts = (int)$user['intentos_fallidos'] + 1;

        if ($newAttempts >= 5) {
            EjecutarSQL(
                $this->db,
                "
                UPDATE usuarios
                SET intentos_fallidos = ?, bloqueado_hasta = ?
                WHERE id = ?
                ",
                [$newAttempts, date('Y-m-d H:i:s', strtotime('+15 minutes')), $user['id']]
            );
        } else {
            EjecutarSQL(
                $this->db,
                "
                UPDATE usuarios
                SET intentos_fallidos = ?
                WHERE id = ?
                ",
                [$newAttempts, $user['id']]
            );
        }
    }

    private function handleSuccessfulLogin(int $userId): void
    {
        EjecutarSQL(
            $this->db,
            "
            UPDATE usuarios
            SET intentos_fallidos = 0,
                bloqueado_hasta = NULL,
                ultimo_login = NOW(),
                ultimo_ip = ?
            WHERE id = ?
            ",
            [$_SERVER['REMOTE_ADDR'] ?? null, $userId]
        );
    }

    /**
     * Ensure tenant database exists and create if needed
     */
    private function ensureTenantDatabase(array $user): array
    {
        try {
            // If user doesn't have a company, skip database check
            if (empty($user['id_empresa'])) {
                return ['success' => true, 'created' => false];
            }

            $companyId = (int)$user['id_empresa'];
            $expectedDbName = 'froshlav_' . $companyId;
            
            // Check if database name is already set in company record
            if (!empty($user['nombre_base_datos'])) {
                $expectedDbName = $user['nombre_base_datos'];
            }

            // Create TenantDatabaseManager instance
            require_once __DIR__ . '/TenantDatabaseManager.php';
            $tenantManager = new TenantDatabaseManager($this->db);

            // Check if database exists
            if ($tenantManager->databaseExists($expectedDbName)) {
                return ['success' => true, 'created' => false];
            }

            // Database doesn't exist, create it
            $result = $tenantManager->createTenantDatabase($companyId, $user['empresa_nombre'] ?? 'Company');
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'created' => true,
                    'database' => $result['database'],
                    'message' => 'Base de datos creada automáticamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error al crear la base de datos: ' . $result['message']
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al verificar la base de datos: ' . $e->getMessage()
            ];
        }
    }
}
