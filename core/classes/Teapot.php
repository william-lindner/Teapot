<?php

namespace Teapot;

use Teapot\Configuration;
use Teapot\Exception;
use Teapot\Session;

final class Teapot
{
    protected static $setup  = false;
    protected static $config = null;

    // holds the keys that cannot be accessed in the config
    protected static $guard = [];

    // holds a list of classes allowed to access protected config keys
    protected static $allowed = [];

    public static $is_local;

    /**
     * Loads the base configuration and configures the Teapot
     * @return instanceof Teapot
     */
    public function __construct()
    {

        if (self::$setup) {
            return new self;
        }

        // Note: not certain about the include path takeover
        set_include_path($_SERVER['DOCUMENT_ROOT'] . '/../app');
        ini_set('include_path', $_SERVER['DOCUMENT_ROOT'] . '/../app');
        Exception::register();
        Session::start();

        self::$config = Configuration::ini();
        self::$guard  = Configuration::guards();

        $ip             = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0;
        self::$is_local = (($ip !== 0 && in_array($ip, ['127.0.0.1', '::1'])) || self::$config['env'] === 'local');

        Route::direct($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        self::$setup = true;
    }

    /**
     * Loads the configuration setting from the protected property.
     * @param string $key
     */
    public static function config($key, $instance = null)
    {

        $access = !in_array($key, self::$guard) && isset(self::$config[$key]);
        $access = true;

        if ($access) {
            return self::$config[$key];
        }

        return false;
    }

}
