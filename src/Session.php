<?php
/**
 * App Session wrapper
 * 
 * @category   Session
 * @package    App
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App;

final class Session
{
    public static function start()
    {
        if (isset($_SERVER['HTTP_SESSION_ID'])) {
            session_id($_SERVER['HTTP_SESSION_ID']);
        }
        session_start();
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Clear all session data
     *
     * @author Ricado Soares <ricardo.soares@rentcars.com>
     */
    public static function clear(): void
    {
        $_SESSION = [];
    }

    public static function set(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): ?string
    {
        return $_SESSION[$key] ?? null;
    }
}