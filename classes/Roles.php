<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam;

abstract class Roles
{
	const ROLE_VIEWER = 1;
	const ROLE_EDITOR = 2;
	const ROLE_ADMIN  = 4;

	/**
	 * Get roles for a user.
	 */
	public static function get_roles($userid) {
		global $DB;

		return $DB->get_fieldset('roles', 'roleid', array(
			'userid' => $userid
		));
	}

	/**
	 * Does this user have a specific role?
	 */
	public static function has_role($userid, $roleid) {
		$roles = static::get_roles($userid);
		return in_array($roleid, $roles);
	}

	/**
	 * Add a role to a user.
	 */
	public static function add_role($userid, $roleid) {
		global $DB, $USER;

		if (!static::has_role($userid, $roleid)) {
			$DB->insert_record('roles', array(
				'userid' => $userid,
				'roleid' => $roleid
			));
		}

		if ($USER->id == $userid) {
			$USER->roles = static::get_roles($USER->id);
		}
	}

	/**
	 * Remove a role from a user.
	 */
	public static function remove_role($userid, $roleid) {
		global $DB, $USER;

		if (static::has_role($userid, $roleid)) {
			$DB->delete_records('roles', array(
				'userid' => $userid,
				'roleid' => $roleid
			));
		}

		if ($USER->id == $userid) {
			$USER->roles = static::get_roles($USER->id);
		}
	}
}
