<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

define('CLI_SCRIPT', true);
define('INSTALLING', true);

require_once(dirname(__FILE__) . '/../config.php');

$migrate = new \Beam\DB\Migrate();
$migrate->run();
