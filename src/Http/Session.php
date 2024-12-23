<?php

namespace Jarvis\Framework\Http;

class Session
{
    private static ?Session $instance = null;

    private function __construct(array $options = [])
    {
        session_start($options);
    }

    /**
     * @param array $options
     * @return Session
     */
    public static function start(array $options = []): Session
    {
        if (self::$instance === null) {
            self::$instance = new self($options);
        }
        return self::$instance;
    }

    /**
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get(string $key = '', $default = null)
    {
        if ($key === '') {
            return $_SESSION;
        }

        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return session_id();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return session_name();
    }

    /**
     * @return bool
     */
    public function abort(): bool
    {
        return session_abort();
    }

    /**
     * @return bool
     */
    public function reset(): bool
    {
        return session_reset();
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return session_status();
    }

    public function unset(): bool
    {
        return session_unset();
    }

    public function remove(string $key)
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
}
