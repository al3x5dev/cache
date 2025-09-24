<?php

use Mk4U\Cache\Connection\DB;
use Psr\SimpleCache\CacheInterface;

/**
 * undocumented class
 */
class Database implements CacheInterface
{
    protected DB $db;
    public function __construct(array $config)
    {
        $this->db = DB::connection($config);
    }

    /**
     * Recupera un valor de la caché por su clave.
     */
    public function get(string $key, mixed $default = null): mixed {}

    /**
     * Almacena un valor en la caché con una clave especificada.
     */
    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool {}

    /**
     * Elimina un valor de la caché por su clave.
     */
    public function delete(string $key): bool {}

    /**
     * Limpia toda la caché.
     */
    public function clear(): bool {}

    /**
     * Recupera múltiples valores de la caché por sus claves.
     *
     * @param iterable $keys Una colección iterable de claves de caché.
     * @param mixed $default El valor por defecto a devolver para las claves que no existen.
     * @return iterable Un array asociativo de claves y sus correspondientes 
     * valores almacenados en caché.
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable {}

    /**
     * Almacena múltiples valores en la caché.
     *
     * @param iterable $values Una colección iterable de pares clave-valor para 
     * almacenar en la caché.
     * @param null|int|\DateInterval $ttl Tiempo de vida opcional para los elementos 
     * de caché.
     * @return bool Verdadero en caso de éxito, falso en caso de fallo.
     */
    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool {}

    /**
     * Elimina múltiples valores de la caché por sus claves.
     *
     * @param iterable $keys Una colección iterable de claves de caché a eliminar.
     * @return bool Verdadero en caso de éxito, falso en caso de fallo.
     */
    public function deleteMultiple(iterable $keys): bool {}

    /**
     * Verifica si un valor existe en la caché por su clave.
     */
    public function has(string $key): bool {}
}
