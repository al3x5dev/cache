<?php

namespace Mk4U\Cache\Connections\Drivers;

use Mk4U\Cache\Connections\DB;

/**
 * Mysql class
 */
class Mysql extends DB
{
    public function __construct(string $host, string $port, string $database, string $user, string $pass,)
    {
        parent::__construct("mysql:dbname=$database;host=$host;port=$port", $user, $pass);
    }

    public function makeTable(): int
    {
        return $this->exec("CREATE TABLE IF NOT EXISTS `cache` (
                `key` VARCHAR(255) PRIMARY KEY NOT NULL,
                `value` TEXT NOT NULL,
                `expire` INT NOT NULL
            );
        ");
    }

    public function set(string $key, string $value, $expire): bool
    {
        try {
            $q = $this->prepare("INSERT INTO `cache`(`key`, `value`, `expire`) VALUES(?, ?, ?)");
            $q->execute([$key, $value, $expire]);
        } catch (\PDOException $e) {
            if ($e->getCode() == '23000') {
                $q = $this->prepare('UPDATE `cache` SET `value` = ?, `expire` = ? WHERE `key` = ?');
                $q->execute([$value, $expire, $key]);
            } else {
                throw $e;
            }
        }

        return true;
    }

    public function get(string $key): mixed
    {
        $q = $this->prepare("SELECT * FROM `cache` WHERE `key` = ?");
        $q->execute([$key]);
        return $q->fetchAll(parent::FETCH_OBJ);
    }

    public function delete(string $key): bool
    {
        $q = $this->prepare("DELETE FROM `cache` WHERE `key` = ?");
        return $q->execute([$key]);
    }

    public function clear(): bool
    {
        $r = $this->exec("DELETE FROM `cache`");
        return (is_int($r) && $r > 0)
            ? true
            : false;
    }

    public function exists(string $key): bool
    {
        $q = $this->prepare("SELECT 1 `key` FROM `cache` WHERE `key` = ?");
        $q->bindValue(1, $key, parent::PARAM_STR);
        $q->execute();
        return !empty($q->fetch()) ? true : false;
    }
}
