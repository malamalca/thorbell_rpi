<?php
/**
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

// Use composer to load the autoloader.
require ROOT . DS . 'vendor' . DS . 'autoload.php';

use App\Configure;

$config = require(dirname(__FILE__) . DS . 'app.php');
Configure::getInstance($config);

/**
 * Helper debug funtion
 *
 * @return void
 */
function dd()
{
    echo '<pre>';
    var_dump(func_get_args());
    echo '</pre>';
    die;
}

/**
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('UTC');

/**
 * Configure the mbstring extension to use the correct encoding.
 */
//mb_internal_encoding($config['App']['encoding']);

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', 'sl_SI');

session_start();
