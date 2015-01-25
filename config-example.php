<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

define("BEAM_INTERNAL", true);

global $CFG;

$CFG = new \stdClass();
$CFG->brand = 'Beam';
$CFG->dirroot = dirname(__FILE__);
$CFG->cssroot = $CFG->dirroot . '/media/css';
$CFG->jsroot = $CFG->dirroot . '/media/js';
$CFG->wwwroot = 'http://localhost:8080';

$CFG->developer_mode = true;
$CFG->profiling_mode = true;

$CFG->database = array(
    'adapter' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'beam_development',
    'username' => 'beam',
    'password' => 'password',
    'prefix' => 'beam_'
);

$CFG->cache = array(
    'servers' => array(
        array('localhost', '11211')
    ),
    'prefix' => 'beam_'
);

$CFG->menu = array(
    'Home' => '/index.php'
);

require_once(dirname(__FILE__) . '/classes/Autoloader.php');

\Beam\Setup::init();
