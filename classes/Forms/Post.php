<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam\Forms;

use \Rapid\Presentation\Form as RapidForm;

class Post extends RapidForm
{
	/**
	 * Simple Constructor.
	 */
	public function __construct($action) {
		parent::__construct($action);

		$this->set_button_text("Post");

		$this->add_element('title', RapidForm::TYPE_STRING);
		$this->add_element('content', RapidForm::TYPE_TEXT);

		$this->add_rule('title', RapidForm::RULE_REQUIRED);
		$this->add_rule('content', RapidForm::RULE_REQUIRED);

		$this->check_submission();
	}

	/**
	 * Check the form for a submission.
	 */
	private function check_submission() {
		global $PAGE;

		$data = $this->get_data();
		if (!$data || $this->has_errors()) {
			return;
		}

		$blog = new \Beam\Blog();
		$blog->new_entry($data['title'], $data['content']);

		$PAGE->redirect('/index.php');
	}
}
