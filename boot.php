<?php
/**
 * OpenTHC CloudPRNT Server Bootstrap
 *
 * SPDX-License-Identifier: MIT
 */

define('APP_ROOT', __DIR__);
define('APP_BUILD', '420.22.270');

error_reporting(E_ALL & ~ E_NOTICE);

openlog('openthc-cps', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

require_once(APP_ROOT . '/vendor/autoload.php');
