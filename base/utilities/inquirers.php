<?php

use Express\Configuration;

/**
 * Loads the configuration option based on an identifier passed.
 *
 * @param string
 * @return mixed
 */
function config(string $identifier, $default = null)
{
    return strtolower(Configuration::get($identifier) ?: $default);
}

/**
 * Access information about the user of the site.
 *
 */
function user(string $key = null)
{
    if (!$key) {
        return $_SESSION['user'];
    }
    return $_SESSION['user'][$key] ?? null;
}
