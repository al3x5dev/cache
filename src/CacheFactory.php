<?php

namespace Mk4U\Cache;

use Mk4U\Cache\Exceptions\InvalidArgumentException;
use Mk4U\Cache\Stores\Apcu;
use Mk4U\Cache\Stores\Database;
use Mk4U\Cache\Stores\File;

/**
 * Cache Factory
 */
class CacheFactory
{
    public static function create(string $store = 'file', array $config = [])
    {
        return match ($store) {
            'apcu' => new Apcu($config),
            'file' => new File($config),
            'database' => new Database($config),
            default => throw new InvalidArgumentException("Unsupported store: $store")
        };
    }
}
