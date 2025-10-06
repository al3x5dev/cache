<?php

namespace Mk4U\Cache;

use Mk4U\Cache\Exceptions\InvalidArgumentException;
use Mk4U\Cache\Stores\Apcu;
use Mk4U\Cache\Stores\Database;
use Mk4U\Cache\Stores\File;
use Psr\SimpleCache\CacheInterface;

/**
 * Cache Factory
 */
class CacheFactory
{
    private static $instance=null;

    public static function create(string $store = 'file', array $config = [])
    {
        if (!self::$instance instanceof CacheInterface) {
            self::$instance = match ($store) {
                'file' => new File($config),
                'apcu' => new Apcu($config),
                'database' => new Database($config),
                default => throw new InvalidArgumentException("Unsupported store: {$store}")
            };
        }

        return self::$instance;
    }
}
