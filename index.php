<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/config.php');

$PAGE->set_title('Beam');
$PAGE->set_url('/index.php');

echo $OUTPUT->header();

$blog = new \Beam\Blog();
$entries = $blog->get_entries(3);
foreach ($entries as $entry) {
	$blog->print_entry_list($entry);
}

echo $OUTPUT->footer();
