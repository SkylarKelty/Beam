<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/config.php');

$PAGE->set_title('Register');
$PAGE->set_url('/register.php');

$form = new \Rapid\Presentation\Form('/register.php');
$form->add_element('username', \Rapid\Presentation\Form::TYPE_STRING);
$form->add_element('password', \Rapid\Presentation\Form::TYPE_PASSWORD);
$form->add_element('password2', \Rapid\Presentation\Form::TYPE_PASSWORD, '', 'Password (verify)');
$form->add_element('firstname', \Rapid\Presentation\Form::TYPE_STRING);
$form->add_element('lastname', \Rapid\Presentation\Form::TYPE_STRING);
$form->add_element('email', \Rapid\Presentation\Form::TYPE_EMAIL, '', 'Email Address');

$form->add_rule('username', \Rapid\Presentation\Form::RULE_REQUIRED);
$form->add_rule('password', \Rapid\Presentation\Form::RULE_REQUIRED);
$form->add_rule('password', \Rapid\Presentation\Form::RULE_MIN_LENGTH, 8);
$form->add_rule('firstname', \Rapid\Presentation\Form::RULE_REQUIRED);
$form->add_rule('lastname', \Rapid\Presentation\Form::RULE_REQUIRED);
$form->add_rule('email', \Rapid\Presentation\Form::RULE_REQUIRED);

if (($data = $form->get_data())) {
	if ($data['password'] != $data['password2']) {
		$form->add_error('password2', 'must match password!');
	}
	
	if (!$form->has_errors()) {
		$user = $AUTH->register($data['username'], $data['password'], $data['firstname'], $data['lastname'], $data['email']);
		if ($user) {
			$PAGE->redirect('/login.php');
		}
	}
}

echo $OUTPUT->header();
echo $OUTPUT->heading();

echo '<div class="row">';
echo "<div class=\"col-md-4\">{$form}</div>";
echo '</div>';

echo $OUTPUT->footer();
