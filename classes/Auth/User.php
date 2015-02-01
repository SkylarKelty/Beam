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

		parent::on_login();

		$USER->roles = \Beam\Roles::get_roles($USER->id);
	}

	/**
	 * Does this user have a specific role?
	 */
	public function has_role($roleid) {
		global $USER;

		return in_array($roleid, $USER->roles);
	}
}
