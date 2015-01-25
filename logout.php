<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/config.php');

$PAGE->set_title('Logout');
$PAGE->set_url('/logout.php');

$auth = new \Beam\Auth();
$auth->logout('/index.php');
