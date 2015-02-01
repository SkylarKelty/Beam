<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam\Auth;

class User extends \Rapid\Auth\User
{
	const ROLE_VIEWER = 1;
	const ROLE_EDITOR = 2;
	const ROLE_ADMIN  = 4;

	/**
	 * Login hook.
	 */
	public function on_login() {
		global $USER;

		$USER->roles = $this->get_roles($USER->id);
	}

	/**
	 * Get roles for a user.
	 */
	public function get_roles($userid) {
		global $DB;

		return $DB->get_fieldset('roles', 'roleid', array(
			'userid' => $userid
		));
	}

	/**
	 * Add a role to a user.
	 */
	public function add_role($userid, $roleid) {
		global $DB, $USER;

		$roles = $this->get_roles($userid);

		if (!in_array($roleid, $role)) {
			$DB->insert_record('roles', array(
				'userid' => $userid,
				'roleid' => $roleid
			));
		}

		if ($USER->id == $userid) {
			$USER->roles = $this->get_roles($USER->id);
		}
	}

	/**
	 * Remove a role from a user.
	 */
	public function remove_role($userid, $roleid) {
		global $DB, $USER;

		$DB->delete_records('roles', array(
			'userid' => $userid,
			'roleid' => $roleid
		));

		if ($USER->id == $userid) {
			$USER->roles = $this->get_roles($USER->id);
		}
	}
}
