<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_title('Site Settings');
$PAGE->set_url('/admin/settings.php');
$PAGE->require_role(\Beam\Roles::ROLE_ADMIN);

echo $OUTPUT->header();
echo $OUTPUT->heading();

echo $OUTPUT->footer();
