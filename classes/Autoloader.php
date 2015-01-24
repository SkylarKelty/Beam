<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

spl_autoload_register(function($class) {
    global $CFG;

    $parts = explode('\\', $class);

    $first = array_shift($parts);
    if ($first !== "Beam") {
    	return;
    }

    require_once($CFG->dirroot . '/classes/' . implode('/', $parts) . '.php');
});

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
