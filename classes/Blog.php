<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam;

class Blog
{
	/**
	 * Returns a (cached) list of all blog entries.
	 */
	public function get_entries() {
		global $DB, $CACHE;

		$records = $CACHE->get('blog_entries');
		if ($records === false) {
	        $records = $DB->get_records_sql('
	        	SELECT
	        		be.id, be.title, be.contents, be.updated, be.created,
	        		be.userid as author_id, u.firstname as author_firstname, u.lastname as author_lastname
	        	FROM {blog_entry} be
	        	INNER JOIN {users} u
	        		ON u.id=be.userid
	        ');
	        $CACHE->set('blog_entries', $records);
		}

		return $records;
	}

	/**
	 * Print a blog post as a list item (not the full article).
	 */
	public function print_entry_list($entry) {
		global $OUTPUT;

		$title = $OUTPUT->escape_string($entry->title);
		$contents = $OUTPUT->escape_string($entry->contents);
		if (strlen($contents) > 255) {
			$contents = substr($contents, 0, 252) . '...';
		}
		$author = $OUTPUT->escape_string($entry->author_firstname . ' ' . $entry->author_lastname);
		$authorurl = new \Rapid\URL('/user.php', array(
			'id' => $entry->author_id
		));

		$lastupdated = $OUTPUT->render_contextual_time($entry->created);
		if ($entry->updated != $entry->created) {
			$lastupdated .= '<span class="time-edited">(edited ' . $OUTPUT->render_contextual_time($entry->updated) . ')</span>';
		}

		echo <<<HTML5
			<div class="blog-post">
				<div class="blog-post-heading">
					<h3 class="blog-post-title">{$title}</h3>
				</div>
				<div class="blog-post-body">
					{$contents}
				</div>
				<div class="blog-post-footer">By <a href="{$authorurl}">{$author}</a> {$lastupdated}</div>
			</div>
HTML5;
	}
}