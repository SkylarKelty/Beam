<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/config.php');

$PAGE->set_title('Login');
$PAGE->set_url('/login.php');

$AUTH->login_page_hook('/index.php');

$form = new \Rapid\Presentation\Form('/login.php');
$form->add_element('username', \Rapid\Presentation\Form::TYPE_STRING);
$form->add_element('password', \Rapid\Presentation\Form::TYPE_PASSWORD);

$authvalid = null;
if (($data = $form->get_data())) {
	$authvalid = $AUTH->login($data['username'], $data['password'], '/index.php');
}

echo $OUTPUT->header();
echo $OUTPUT->heading();

if ($authvalid === false) {
	echo '<div class="alert alert-warning" role="alert">That username or password was not recognised! Please try again.</div>';
}

echo '<div class="row">';
echo "<div class=\"col-md-4\">{$form}</div>";
echo '</div>';

echo $OUTPUT->footer();
