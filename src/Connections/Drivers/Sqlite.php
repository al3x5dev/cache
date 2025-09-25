<?php

namespace Mk4U\Cache\Connections;

use Mk4U\Cache\Exceptions\CacheException;

class SQLiteDB extends DB
{
    protected function buildDsn(array $config): string
    {
        
        return "sqlite:{$config['database']}";
    }

    protected function createDatabase(): bool
    {
        $database = $this->config['database'] ?? sys_get_temp_dir() . '/cache.sqlite';
        
        // Crear directorio si no existe
        $dir = dirname($database);
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new CacheException("Cannot create directory: {$dir}");
        }

        // Para SQLite, el archivo se crea automáticamente al conectar
        // pero verificamos si el archivo ya existía
        $alreadyExists = file_exists($database);
        
        // Si el archivo no existía, tocamos para crearlo
        if (!$alreadyExists) {
            touch($database);
            return true; // Se creó nueva
        }
        
        return false; // Ya existía
    }

    protected function getCacheTableSchema(): string
    {
        return "
            CREATE TABLE IF NOT EXISTS {$this->table} (
                key VARCHAR(255) PRIMARY KEY,
                value TEXT NOT NULL,
                ttl INTEGER,
                created_at INTEGER DEFAULT (strftime('%s','now'))
            );
            CREATE INDEX IF NOT EXISTS idx_ttl ON {$this->table}(ttl);
        ";
    }

    protected static function hasDatabase() : void
    {
        if (empty($config['database'])) {
            throw new CacheException("Database name required in configuration ('database').");
        }
    }
}