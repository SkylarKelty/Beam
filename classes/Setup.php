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
		global $AUTH, $CFG, $PAGE, $SESSION, $USER;

		if (!defined('INSTALLING')) {
		    define('INSTALLING', false);
		}

		\Rapid\Core::init();

		// Return early if we are installing.
        if (defined('INSTALLING') && INSTALLING) {
        	return;
        }

		// Init DB config.
		Config::init();

	    // Start a session.
	    $SESSION = new \Rapid\Auth\Session();

	    // Setup a guest user by default.
	    $USER = new \Beam\Auth\User();

	    // Setup auth plugin.
	    $auth = $CFG->auth;
	    $AUTH = new $auth();

	    if (!CLI_SCRIPT) {
		    // Page library.
		    $PAGE = new \Rapid\Presentation\Page();
		    $PAGE->require_css("//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css");
		    $PAGE->require_js("//code.jquery.com/jquery-1.11.2.min.js");
		    $PAGE->require_js("//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js");

		    // Set a default page title.
		    $PAGE->set_title($CFG->brand);

		    // Setup navigation.
		    $PAGE->add_menu_item('Home', '/index.php');
		    if ($USER->loggedin()) {
		    	// Build admin navbar.
		    	if ($USER->has_role(\Beam\Roles::ROLE_ADMIN)) {
			    	$PAGE->add_menu_item('New Post', '/admin/entry.php');
			    	$PAGE->add_menu_item('Site Settings', '/admin/settings.php');
		    	}

		    	$PAGE->add_menu_item('Logout', '/logout.php');
		    } else {
		    	$PAGE->add_menu_item('Login', '/login.php');
		    }
		}
	}
}