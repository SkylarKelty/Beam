<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam;

abstract class Config
{
	/**
	 * Initialize DB config.
	 */
	public static function init() {
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

	/**
	 * Set DB config.
	 */
	public static function set($name, $value) {
	    global $CACHE, $CFG, $DB;

	    // Invalidate cache.
	    $CACHE->delete('dbconfig');

	    $CFG->$name = $value;

	    return $DB->update_or_insert('config', array('name' => $name), array(
	        'name' => $name,
	        'value' => $value
	    ));
	}

	/**
	 * Get DB config.
	 */
	public static function get($name) {
	    global $CFG, $DB;

	    if (!isset($CFG->$name)) {
	        $value = $DB->get_field('config', 'value', array(
	            'name' => $name
	        ));

	        if ($value !== null) {
	        	// This was valid, cache must be invalid.
	    		$CACHE->delete('dbconfig');

	        	$CFG->$name = $value;
	    		return $CFG->$name;
	        }

	        return null;
	    }

	    return $CFG->$name;
	}
}
