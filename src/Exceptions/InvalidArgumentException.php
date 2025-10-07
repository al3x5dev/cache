<?php

namespace Mk4U\Cache\Exceptions;

use Psr\SimpleCache\InvalidArgumentException as SimpleCacheInvalidArgumentException;

/**
 * InvalidArgumentException class
 */
class InvalidArgumentException extends CacheException implements SimpleCacheInvalidArgumentException
{
    
}
