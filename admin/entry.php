<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_title('New Post');
$PAGE->set_url('/admin/entry.php');

$form = new \Beam\Forms\Post('/admin/entry.php');

echo $OUTPUT->header();
echo $OUTPUT->heading();

echo '<div class="row">';
echo "<div class=\"col-md-5\">{$form}</div>";
echo '</div>';

echo $OUTPUT->footer();
