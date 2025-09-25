<?php

namespace Mk4U\Cache\Connections;

use PDO;
use Mk4U\Cache\Exceptions\CacheException;

abstract class DB extends PDO
{
    protected string $table;
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->table = $config['table'] ?? 'cache';
        
        $dsn = $this->buildDsn($config);
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;
        $options = $config['options'] ?? [];

        parent::__construct($dsn, $username, $password, array_merge([
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ], $options));
    }

    /**
     * Construye el DSN para cada driver
     */
    abstract protected function buildDsn(array $config): string;

    /**
     * Método público para inicializar la base de datos y tabla
     * Retorna true si fue exitoso, false si ya existía
     */
    public function initialize(): bool
    {
        return $this->createDatabase() && $this->createTable();
    }

    /**
     * Crea la base de datos si no existe (específico por driver)
     */
    abstract protected function createDatabase(): bool;

    /**
     * Crea la tabla de cache si no existe
     */
    protected function createTable(): bool
    {
        try {
            $sql = $this->getCacheTableSchema();
            $this->exec($sql);
            return true;
        } catch (\PDOException $e) {
            // La tabla probablemente ya existe
            return false;
        }
    }

    abstract protected function getCacheTableSchema(): string;

    // Métodos helpers para el Store (sin cambios)
    public function cacheGet(string $key): ?array
    {
        $stmt = $this->prepare("SELECT value, ttl FROM {$this->table} WHERE key = ?");
        $stmt->execute([$key]);
        return $stmt->fetch() ?: null;
    }

    public function cacheSet(string $key, string $value, ?int $ttl = null): bool
    {
        $stmt = $this->prepare("REPLACE INTO {$this->table} (key, value, ttl) VALUES (?, ?, ?)");
        return $stmt->execute([$key, $value, $ttl]);
    }

    public function cacheDelete(string $key): bool
    {
        $stmt = $this->prepare("DELETE FROM {$this->table} WHERE key = ?");
        return $stmt->execute([$key]);
    }

    public function cacheClear(): bool
    {
        return (bool) $this->exec("DELETE FROM {$this->table}");
    }

    /**
     * Verifica si la base de datos y tabla están inicializadas
     */
    public function isInitialized(): bool
    {
        try {
            $stmt = $this->prepare("SELECT 1 FROM {$this->table} LIMIT 1");
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}