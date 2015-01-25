<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam\Auth;

class DB extends \Rapid\Auth\AuthPlugin
{
	/**
	 * Send us off to the SP.
	 */
	public function register($username, $password, $firstname, $lastname, $email) {
		global $DB;

		$user = $DB->get_record('users', array(
			'username' => $username
		));

		if ($user) {
			// Nope!
			return false;
		}

		$user = array(
			'username' => $username,
			'password' => password_hash($password, PASSWORD_BCRYPT),
			'email' => $email,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'updated' => time(),
			'created' =>  time()
		);

		$user['id'] = $DB->insert_Record('users', $user);

		if (empty($user['id'])) {
			return false;
		}

		return (object)$user;
	}

	/**
	 * Send us off to the SP.
	 */
	public function login($username, $password, $redirect = false) {
		global $DB, $PAGE, $USER;

		$record = $DB->get_record('users', array(
			'username' => $username
		));

		if (!$record) {
			return false;
		}

		$valid = password_verify($password, $record->password);
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
}
