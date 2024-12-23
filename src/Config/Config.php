<?php

namespace Jarvis\Framework\Config;

use Jarvis\Framework\Common\Configurable;
use Jarvis\Framework\Logger\Logger;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Exception;

class Config
{
    protected Container $container;

    protected array $values = [];

    /**
     * Constructor
     * @param Container $container
     * @param Logger $logger
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container, Logger $logger)
    {
        $this->container = $container;
        $path = $container->get('config.path');
        try {
            $dotenv = Dotenv::createUnsafeImmutable($path);
            $dotenv->load();
            $this->values = getenv();
        } catch (InvalidPathException $ex) {
            $logger->warning('Cannot find env file');
        }
    }

    /**
     * Get variable by key
     *
     * Return value if it exists otherwise return default value
     * @param string $key variable name
     * @param mixed $default default value
     *
     * @return mixed
     */
    public function get(string $key = '', $default = null)
    {
        if ($key == '') {
            return $this->values;
        }

        $key = strtoupper($key);

        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        return $default;
    }

    /**
     * Get value by key or throw exception when not found
     *
     * @param string $key variable name
     *
     * @return mixed
     * @throws Exception
     */
    public function getOrThrow(string $key)
    {
        $key = strtoupper($key);

        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        throw new Exception('Cannot find value in config');
    }

    /**
     * Check value if exists
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * @param Configurable|string $object
     * @return Configurable|object
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function forObject($object)
    {
        if (is_string($object) && class_exists($object)) {
            $object = $this->container->get($object);
        }

        if ($object instanceof Configurable) {
            $object->configure($this);
        }

        return $object;
    }
}
