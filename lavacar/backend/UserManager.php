<?php

require_once 'BaseManager.php';

class UserManager extends BaseManager
{
    protected array $fillable = [
        'id_empresa', 'name', 'email', 'user_name', 'password', 
        'permiso', 'mfa_habilitado', 'intentos_fallidos', 'bloqueado_hasta',
        'ultimo_login', 'ultimo_ip', 'estado', 'active', 'remember_token'
    ];
    
    private int $currentCompanyId;

    public function __construct(mysqli $conn, string $dbName)
    {
        parent::__construct($conn, $dbName, 'usuarios');
        
        // Get current company ID from user info
        $user = userInfo();
        $this->currentCompanyId = $user['id_empresa'] ?? 1;
    }

    /**
     * Create a new user for the current company
     */
    public function createUser(string $name, string $email, int $permisos = 1): string
    {
        try {
            // Check if email already exists for this company
            $existing = $this->findWhere(['email' => $email, 'id_empresa' => $this->currentCompanyId]);
            if ($existing) {
                return json_encode(['success' => false, 'message' => 'El email ya está registrado en esta empresa']);
            }

            // Generate username from email
            $username = explode('@', $email)[0];
            
            // Check if username exists for this company, add number if needed
            $baseUsername = $username;
            $counter = 1;
            while ($this->findWhere(['user_name' => $username, 'id_empresa' => $this->currentCompanyId])) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            // Generate temporary password
            $tempPassword = $this->generateTempPassword();
            $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

            $data = [
                'id_empresa' => $this->currentCompanyId,
                'name' => $name,
                'email' => $email,
                'user_name' => $username,
                'password' => $hashedPassword,
                'permiso' => $permisos,
                'estado' => 'activo',
                'active' => 1,
                'mfa_habilitado' => 0,
                'intentos_fallidos' => 0,
                'remember_token' => 0
            ];

            $userId = $this->create($data);

            if ($userId) {
                return json_encode([
                    'success' => true, 
                    'message' => 'Usuario creado exitosamente',
                    'temp_password' => $tempPassword,
                    'username' => $username
                ]);
            } else {
                return json_encode(['success' => false, 'message' => 'Error al crear el usuario']);
            }

        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update user
     */
    public function updateUser(int $id, string $name, string $email, int $permisos): string
    {
        try {
            // Verify user belongs to current company
            $user = $this->find($id);
            if (!$user || $user['id_empresa'] != $this->currentCompanyId) {
                return json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            }

            // Check if email exists for other users in this company
            $existing = $this->queryFirst(
                "SELECT id FROM {$this->dbName}.{$this->tableName} 
                 WHERE email = ? AND id != ? AND id_empresa = ?",
                [$email, $id, $this->currentCompanyId]
            );
            
            if ($existing) {
                return json_encode(['success' => false, 'message' => 'El email ya está registrado por otro usuario']);
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'permiso' => $permisos
            ];

            $success = $this->update($id, $data);

            if ($success) {
                return json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
            } else {
                return json_encode(['success' => false, 'message' => 'Error al actualizar el usuario']);
            }

        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete user (soft delete)
     */
    public function deleteUser(int $id): string
    {
        try {
            // Verify user belongs to current company
            $user = $this->find($id);
            if (!$user || $user['id_empresa'] != $this->currentCompanyId) {
                return json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            }

            // Don't allow deleting the last active admin for this company
            $adminCount = $this->count([
                'permiso' => 1, 
                'active' => 1, 
                'id_empresa' => $this->currentCompanyId
            ]);
            
            if ($user['permiso'] == 1 && $adminCount <= 1) {
                return json_encode(['success' => false, 'message' => 'No se puede eliminar el último administrador de la empresa']);
            }

            $success = $this->update($id, ['active' => 0, 'estado' => 'inactivo']);

            if ($success) {
                return json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
            } else {
                return json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
            }

        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get all users for current company
     */
    public function getAllUsers(): array
    {
        return $this->query(
            "SELECT id, name, email, user_name, permiso, estado, active,
                    CASE 
                        WHEN permiso = 1 THEN 'Administrador'
                        WHEN permiso = 2 THEN 'Operador'
                        ELSE 'Usuario'
                    END as rol_nombre,
                    fecha_creacion, ultimo_login
             FROM {$this->dbName}.{$this->tableName}
             WHERE active = 1 AND id_empresa = ?
             ORDER BY name ASC",
            [$this->currentCompanyId]
        )->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Reset user password
     */
    public function resetPassword(int $id): string
    {
        try {
            // Verify user belongs to current company
            $user = $this->find($id);
            if (!$user || $user['id_empresa'] != $this->currentCompanyId) {
                return json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            }

            $tempPassword = $this->generateTempPassword();
            $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

            $success = $this->update($id, [
                'password' => $hashedPassword,
                'intentos_fallidos' => 0,
                'bloqueado_hasta' => null
            ]);

            if ($success) {
                return json_encode([
                    'success' => true, 
                    'message' => 'Contraseña restablecida exitosamente',
                    'temp_password' => $tempPassword
                ]);
            } else {
                return json_encode(['success' => false, 'message' => 'Error al restablecer la contraseña']);
            }

        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate temporary password
     */
    private function generateTempPassword(): string
    {
        return 'temp' . rand(1000, 9999);
    }

    /**
     * Get user statistics for current company
     */
    public function getUserStats(): array
    {
        $total = $this->count(['active' => 1, 'id_empresa' => $this->currentCompanyId]);
        $admins = $this->count(['permiso' => 1, 'active' => 1, 'id_empresa' => $this->currentCompanyId]);
        $operators = $this->count(['permiso' => 2, 'active' => 1, 'id_empresa' => $this->currentCompanyId]);
        
        return [
            'total' => $total,
            'administradores' => $admins,
            'operadores' => $operators,
            'usuarios' => $total - $admins - $operators
        ];
    }

    /**
     * Override buildWhereClause to always include company filter
     */
    protected function buildWhereClause(array $conditions): string
    {
        // Always add company filter
        $conditions['id_empresa'] = $this->currentCompanyId;
        
        if (empty($conditions)) {
            return "WHERE id_empresa = {$this->currentCompanyId}";
        }
        
        $fields = array_keys($conditions);
        $whereConditions = array_map(function($field) {
            return "{$field} = ?";
        }, $fields);
        
        return 'WHERE ' . implode(' AND ', $whereConditions);
    }
}