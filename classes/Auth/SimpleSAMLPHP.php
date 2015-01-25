<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam\Auth;

class SimpleSAMLPHP extends \Rapid\Auth\SimpleSAMLPHP
{
	/**
	 * Setup the user.
	 */
	protected function setup_user($attrs) {
		global $DB, $USER;

		parent::setup_user($attrs);

		// Login or register.
		$record = $DB->get_record('users', array(
			'username' => $USER->username
		));

		if ($record) {
			$USER->id = $record->id;
		} else {
			$auth = new \Beam\Auth\DB();
			$record = $auth->register($USER->username, $this->random_password(), $USER->username, $USER->firstname, $USER->lastname, $USER->email);
			$USER->id = $record->id;
		}
	}

	/**
	 * Generate a random password.
	 */
	private function random_password() {
		$alphanum = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789();:{}[].,<>?!^*";
		$maxlen = strlen($alphanum) - 1;

		$password = array();
		for ($i = 0; $i < 16; $i++) {
			$password[] = $alphanum[rand(0, $maxlen)];
		}

		return implode($password);
	}
}
