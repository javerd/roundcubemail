<?php

/*
 +-----------------------------------------------------------------------+
 | program/include/iniset.php                                            |
 |                                                                       |
 | This file is part of the Roundcube Webmail client                     |
 | Copyright (C) 2008-2012, The Roundcube Dev Team                       |
 |                                                                       |
 | Licensed under the GNU General Public License version 3 or            |
 | any later version with exceptions for skins & plugins.                |
 | See the README file for a full license statement.                     |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Setup the application environment required to process               |
 |   any request.                                                        |
 +-----------------------------------------------------------------------+
 | Author: Till Klampaeckel <till@php.net>                               |
 |         Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+
*/

// application constants
define('RCMAIL_VERSION', '0.9-git');
define('RCMAIL_START', microtime(true));

$config = array(
    // Some users are not using Installer, so we'll check some
    // critical PHP settings here. Only these, which doesn't provide
    // an error/warning in the logs later. See (#1486307).
    'suhosin.session.encrypt' => 0,
    'session.auto_start'      => 0,
    'file_uploads'            => 1,
);
foreach ($config as $optname => $optval) {
    if ($optval != ini_get($optname) && @ini_set($optname, $optval) === false) {
        die("ERROR: Wrong '$optname' option value and it wasn't possible to set it to required value ($optval).\n"
            ."Check your PHP configuration (including php_admin_flag).");
    }
}

if (!defined('INSTALL_PATH')) {
    define('INSTALL_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
}

if (!defined('RCMAIL_CONFIG_DIR')) {
    define('RCMAIL_CONFIG_DIR', INSTALL_PATH . 'config');
}

if (!defined('RCUBE_LOCALIZATION_DIR')) {
    define('RCUBE_LOCALIZATION_DIR', INSTALL_PATH . 'program/localization/');
}

define('RCUBE_INSTALL_PATH', INSTALL_PATH);
define('RCUBE_CONFIG_DIR',  RCMAIL_CONFIG_DIR.'/');


// RC include folders MUST be included FIRST to avoid other
// possible not compatible libraries (i.e PEAR) to be included
// instead the ones provided by RC
$include_path = INSTALL_PATH . 'program/lib' . PATH_SEPARATOR;
$include_path.= ini_get('include_path');

if (set_include_path($include_path) === false) {
    die("Fatal error: ini_set/set_include_path does not work.");
}

// increase maximum execution time for php scripts
// (does not work in safe mode)
@set_time_limit(120);

// include Roundcube Framework
require_once 'Roundcube/bootstrap.php';

// backward compatybility (to be removed)
require_once INSTALL_PATH . 'program/include/rcmail.php';

// backward compatybility (to be removed)
require_once INSTALL_PATH . 'program/include/bc.php';
