<?php

namespace Mk4U\Cache\Connection;

use PDO;

/**
 * Database Connection Class
 */
class DB extends PDO
{
    private static ?self $init = null;
    private ?PDO $db = null;

    /**
     * Inicializa connexion
     */
    public static function connection(array $config): self
    {
        if (is_null(self::$init)) {
            self::$init = new self($config);
        }
        return self::$init;
    }

    /**
     * Constructor de la clase
     */
    private function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $$key = $value;
        }

        $this->db = parent::__construct(
            "sqlite:$database",
            $username ?? '',
            $password ?? '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public static function sqlite(string $database): bool
    {
        if (empty($database)) {
            throw new \InvalidArgumentException('Error creating .sqlite file. Empty name');
        }

        if (file_exists($database)) {
            return false;
        }

        if (touch($database)) {
            $db = self::connection(['database' => $database]);

            $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS cache (
                key VARCHAR(255) UNIQUE NOT NULL,
                value TEXT NOT NULL,
                expire INTEGER
                );
            SQL;

            if (is_int($db->exec($sql))) {
                return true;
            }
        }
        return false;
    }
}
