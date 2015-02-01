<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam;

class Page extends \Rapid\Presentation\Page
{
	/**
	 * Require a specific role for this page.
	 */
	public function require_role($roleid) {
		global $PAGE, $OUTPUT, $USER;

		if (!$USER->loggedin()) {
			$PAGE->redirect("/login.php");
		}

		if (!$USER->has_role($roleid)) {
			$OUTPUT->error_page("You do not have permission to view this page!");
		}
	}
}
