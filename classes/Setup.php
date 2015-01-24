<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam;

abstract class Setup
{
	/**
	 * Initialize Beam.
	 */
	public static function init() {
		global $CACHE, $CFG, $DB, $PAGE, $OUTPUT, $SESSION, $USER;

		if (!defined('CLI_SCRIPT')) {
		    define('CLI_SCRIPT', false);
		} else {
			if (CLI_SCRIPT) {
				if (isset($_SERVER['REMOTE_ADDR']) || php_sapi_name() != 'cli') {
					die("Must be run from CLI.");
				}
			}
		}

		if (isset($CFG->_init_called)) {
			die("Init has already been called.");
		}

		$CFG->_init_called = microtime(true);

		// DB connection.
		$DB = new \Rapid\Data\PDO(
		    $CFG->database['adapter'],
		    $CFG->database['host'],
		    $CFG->database['port'],
		    $CFG->database['database'],
		    $CFG->database['username'],
		    $CFG->database['password'],
		    $CFG->database['prefix']
		);

		// Cache connection.
		$CACHE = new \Rapid\Data\Memcached($CFG->cache['servers'], $CFG->cache['prefix']);

	    // Start a session.
	    $SESSION = new \Rapid\Auth\Session();

	    // Setup a guest user by default.
	    $USER = new \Rapid\Auth\User();

	    if (!CLI_SCRIPT) {
		    // Output library.
		    $OUTPUT = new \Rapid\Presentation\Output();

		    // Page library.
		    $PAGE = new \Rapid\Presentation\Page();
		    $PAGE->require_css("//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css");
		    $PAGE->require_js("//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js");

		    // Set a default page title.
		    $PAGE->set_title($CFG->brand);

		    // Setup navigation.
		    $PAGE->menu($CFG->menu);
		}
	}

	/**
	 * Init DB config.
	 */
	private static function init_cfg() {
		global $CACHE, $CFG, $DB;

		// Load config.
		$dbconfig = $CACHE->get('dbconfig');
		if (!$dbconfig) {
		    try {
		        $dbconfig = $DB->get_records('config');
		        $CACHE->set('dbconfig', $dbconfig);
		    } catch (\Exception $e) {
		        if (!defined('INSTALLING') || !INSTALLING) {
		            die("Database tables are not present. Please run migrate.php");
		        }
		    }
		}

		if ($dbconfig) {
		    foreach ($dbconfig as $record) {
		        $name = $record->name;
		        if (isset($CFG->$name)) {
		            continue;
		        }
		        $CFG->$name = $record->value;
		    }
		}
	}
}