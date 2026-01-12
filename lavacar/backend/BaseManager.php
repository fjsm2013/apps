<?php

/**
 * Base Manager Class
 * Provides common database operations for all managers
 */
abstract class BaseManager
{
    protected mysqli $db;
    protected string $dbName;
    protected string $tableName;
    protected array $fillable = [];
    protected string $primaryKey = 'ID';

    public function __construct(mysqli $conn, string $dbName, string $tableName = '')
    {
        $this->db = $conn;
        $this->dbName = $dbName;
        $this->tableName = $tableName ?: $this->getTableName();
    }

    /**
     * Get table name from class name if not provided
     */
    protected function getTableName(): string
    {
        $className = get_class($this);
        $tableName = str_replace('Manager', '', $className);
        return strtolower($tableName);
    }

    /**
     * Get all records
     */
    public function all(array $conditions = [], string $orderBy = ''): array
    {
        $whereClause = $this->buildWhereClause($conditions);
        $orderClause = $orderBy ? "ORDER BY {$orderBy}" : '';
        
        return CrearConsulta(
            $this->db,
            "SELECT * FROM {$this->dbName}.{$this->tableName} {$whereClause} {$orderClause}",
            array_values($conditions)
        )->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Find record by ID
     */
    public function find(int $id): ?array
    {
        return ObtenerPrimerRegistro(
            $this->db,
            "SELECT * FROM {$this->dbName}.{$this->tableName} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Find record by conditions
     */
    public function findWhere(array $conditions): ?array
    {
        $whereClause = $this->buildWhereClause($conditions);
        
        return ObtenerPrimerRegistro(
            $this->db,
            "SELECT * FROM {$this->dbName}.{$this->tableName} {$whereClause} LIMIT 1",
            array_values($conditions)
        );
    }

    /**
     * Create new record
     */
    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        $fields = array_keys($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        return EjecutarSQL(
            $this->db,
            "INSERT INTO {$this->dbName}.{$this->tableName} (" . implode(',', $fields) . ") VALUES ({$placeholders})",
            array_values($data)
        );
    }

    /**
     * Update record
     */
    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        $fields = array_keys($data);
        $setClause = implode(' = ?, ', $fields) . ' = ?';
        
        $params = array_values($data);
        $params[] = $id;
        
        return EjecutarSQL(
            $this->db,
            "UPDATE {$this->dbName}.{$this->tableName} SET {$setClause} WHERE {$this->primaryKey} = ?",
            $params
        );
    }

    /**
     * Delete record
     */
    public function delete(int $id): bool
    {
        return EjecutarSQL(
            $this->db,
            "DELETE FROM {$this->dbName}.{$this->tableName} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Toggle active status
     */
    public function toggle(int $id, string $field = 'active'): bool
    {
        return EjecutarSQL(
            $this->db,
            "UPDATE {$this->dbName}.{$this->tableName} SET {$field} = IF({$field} = 1, 0, 1) WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Count records
     */
    public function count(array $conditions = []): int
    {
        $whereClause = $this->buildWhereClause($conditions);
        
        return (int) ObtenerValor(
            $this->db,
            "SELECT COUNT(*) FROM {$this->dbName}.{$this->tableName} {$whereClause}",
            array_values($conditions)
        );
    }

    /**
     * Paginate records
     */
    public function paginate(int $page = 1, int $perPage = 10, array $conditions = [], string $orderBy = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $whereClause = $this->buildWhereClause($conditions);
        $orderClause = $orderBy ? "ORDER BY {$orderBy}" : '';
        
        $data = CrearConsulta(
            $this->db,
            "SELECT * FROM {$this->dbName}.{$this->tableName} {$whereClause} {$orderClause} LIMIT {$perPage} OFFSET {$offset}",
            array_values($conditions)
        )->fetch_all(MYSQLI_ASSOC);
        
        $total = $this->count($conditions);
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    /**
     * Search records
     */
    public function search(string $query, array $searchFields = [], array $conditions = []): array
    {
        if (empty($searchFields)) {
            return $this->all($conditions);
        }
        
        $searchConditions = [];
        $searchParams = [];
        
        foreach ($searchFields as $field) {
            $searchConditions[] = "{$field} LIKE ?";
            $searchParams[] = "%{$query}%";
        }
        
        $searchClause = '(' . implode(' OR ', $searchConditions) . ')';
        $whereClause = $this->buildWhereClause($conditions);
        
        if ($whereClause) {
            $whereClause .= " AND {$searchClause}";
        } else {
            $whereClause = "WHERE {$searchClause}";
        }
        
        $params = array_merge(array_values($conditions), $searchParams);
        
        return CrearConsulta(
            $this->db,
            "SELECT * FROM {$this->dbName}.{$this->tableName} {$whereClause}",
            $params
        )->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Build WHERE clause from conditions array
     */
    protected function buildWhereClause(array $conditions): string
    {
        if (empty($conditions)) {
            return '';
        }
        
        $fields = array_keys($conditions);
        $whereConditions = array_map(function($field) {
            return "{$field} = ?";
        }, $fields);
        
        return 'WHERE ' . implode(' AND ', $whereConditions);
    }

    /**
     * Filter data to only include fillable fields
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Get statistics for the table
     */
    public function getBasicStats(): array
    {
        $total = $this->count();
        $active = $this->count(['active' => 1]);
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active
        ];
    }

    /**
     * Execute raw query
     */
    protected function query(string $sql, array $params = []): mysqli_result
    {
        return CrearConsulta($this->db, $sql, $params);
    }

    /**
     * Execute raw query and get first result
     */
    protected function queryFirst(string $sql, array $params = []): ?array
    {
        return ObtenerPrimerRegistro($this->db, $sql, $params);
    }

    /**
     * Execute raw query and get single value
     */
    protected function queryValue(string $sql, array $params = [])
    {
        return ObtenerValor($this->db, $sql, $params);
    }

    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->db->begin_transaction();
    }

    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->db->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->db->rollback();
    }
}