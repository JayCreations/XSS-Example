<?php
/**
 * Class Presentation on XSS
 *
 * @package		XSS
 * @version		0.1
 * @author		Juan "JayCreations" Hernandez
 * @license		MIT
 * @copyright		2011 Juan J. Hernandez
 * @link
 */

// Set the timezone.
date_default_timezone_set('America/New_York');
// We need some errors.
ini_set('display_errors', 1);
// Report these.
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_STRICT);
// Before we call something else lets define this so the files know where we are coming form.
define('XSS', 1);
require_once(dirname(__FILE__) . '/Database.php');

class Comments
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Do something.
		$start = microtime(true);
		// Start the database.
		$this->db = new Database();
	}

	/**
	 * Gets all the comments.
	 *
	 * @access public
	 * @param int $limit = 0
	 * @return array
	 */
	public function get_comments($limit = 0)
	{
		$this->db->query("
			SELECT id_comment, username, email, comment, time_added
			FROM class_comments");

		$comments = array();
		while ($row = $this->db->assoc())
			$comments[$row['id_comment']] = $row;
		$this->db->free_result();

		return $comments;
	}

	/**
	 * Add a comment.
	 *
	 * @access public
	 * @param array $data
	 * @param bool $escape = true
	 * @return bool
	 */
	public function add_comment($data, $escape = true)
	{
		// This is a check to see if we are escaping the insert.
		if ($escape === true)
		{
			$data['username'] = $this->db->escape(htmlspecialchars($data['username'], ENT_QUOTES));
			$data['email'] = $this->db->escape(htmlspecialchars($data['email'], ENT_QUOTES));
			$data['comment'] = $this->db->escape(htmlspecialchars($data['comment'], ENT_QUOTES));
		}

		$query = $this->db->query("
			INSERT INTO class_comments
				(username, email, comment, time_added)
			VALUES
				('{$data['username']}', '{$data['email']}', '{$data['comment']}', " . time() . ")");

		return $query === false ? false : true;
	}
}