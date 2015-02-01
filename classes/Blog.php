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
	 * Submit a new post.
	 */
	public function new_entry($title, $content) {
		global $DB, $CACHE, $USER;

		$DB->insert_record('posts', array(
			'title' => $title,
			'contents' => $content,
			'userid' => $USER->id,
			'updated' => time(),
			'created' => time()
		));

		// Regen cache.
		$CACHE->delete('blog_posts');
		$this->get_entries();
	}

	/**
	 * Returns a (cached) list of all blog entries.
	 */
	public function get_entries($limit = 0) {
		global $DB, $CACHE;

		$records = $CACHE->get('blog_posts');
		if ($records === false) {
	        $records = $DB->get_records_sql('
	        	SELECT
	        		be.id, be.title, be.contents, be.updated, be.created,
	        		be.userid as author_id, u.firstname as author_firstname, u.lastname as author_lastname
	        	FROM {posts} be
	        	INNER JOIN {users} u
	        		ON u.id=be.userid
	        	ORDER BY be.created DESC
	        ');
	        $CACHE->set('blog_posts', $records);
		}

		if ($limit > 0) {
			return array_slice($records, 0, $limit);
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
				<h2 class="blog-post-title">{$title}</h2>
				<p class="blog-post-meta">By <a href="{$authorurl}">{$author}</a> {$lastupdated}</p>
				<hr />
				{$contents}
			</div>
HTML5;
	}
}