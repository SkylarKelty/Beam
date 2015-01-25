<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam;

class Auth
{
	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Send us off to the SP.
	 */
	public function register($user) {
		global $DB;

		$user = $DB->get_record('users', array(
			'username' => $user->username
		));

		if ($user) {
			// Nope!
			return false;
		}

		$user = array(
			'username' => $user->username,
			'password' => $user->password_hash($user->password, PASSWORD_BCRYPT),
			'email' => $user->email,
			'firstname' => $user->firstname,
			'lastname' => $user->lastname,
			'updated' => time(),
			'created' =>  time()
		);

		$user['id'] = $DB->insert_Record('users', $user);

		return (object)$user;
	}

	/**
	 * Send us off to the SP.
	 */
	public function login($redirect = false) {
		global $DB, $USER;

		$record = $DB->get_record('users', array(
			'username' => $_POST['username']
		));

		$valid = password_verify($_POST['password'], $record->password);
		if ($valid) {
			$USER->id = $record->id;
			$USER->username = $record->username;
			$USER->email = $record->email;
			$USER->firstname = $record->firstname;
			$USER->lastname = $record->lastname;

	        if ($redirect) {
	        	$PAGE->redirect($redirect);
	        }
		}

        return $valid;
	}

	/**
	 * Logout.
	 */
	public function logout($redirect = false) {
		global $USER;

		$USER->reset();

        if ($redirect) {
        	$PAGE->redirect($redirect);
        }
	}

	/**
	 * Checks to see if we are logged in.
	 */
	public function logged_in() {
		global $USER;
		return $USER->loggedin();
	}
}
