<?php

namespace Mk4U\Cache\Connections;

use Mk4U\Cache\Exceptions\CacheException;
use PDO;
use PDOException;

abstract class DB extends PDO
{
    protected const OPTIONS = [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION];

    public function __construct(string $dsn, string $user = '', string $pass = '', array $options = [])
    {
        try {
            parent::__construct($dsn, $user, $pass, array_merge(self::OPTIONS, $options));
        } catch (PDOException $e) {
            throw new CacheException($e->getMessage());
        }
    }

    abstract public function makeTable(): int;
    abstract public function set(string $key, string $value, int $expire): bool;
    abstract public function get(string $key): mixed;
    abstract public function delete(string $key): bool;
    abstract public function clear(): bool;
    abstract public function exists(string $key): bool;
}
