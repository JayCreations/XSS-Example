<?php
/**
 * Class Presentation on XSS
 *
 * @package		XSS
 * @version		0.1
 * @author		Juan "JayCreations" Hernandez
 * @license		MIT
 * @copyright		2011 Juan J. Hernandez
 * @link		https://github.com/JayCreations/XSS-Example
 */

// No direct access.
if (!defined('XSS'))
	die('Direct access not allowed.');

// Database setting.
require_once('settings.php');

class Database
{
	// Settings.
	private $_user;
	private $_pass;
	private $_name;
	private $_host;
	private $_conn = null;
	private $_persistent = false;
	private $_query;
	private $_request = null;
	private $_mysql_error = null;
	private $_mysql_errno = null;

	/**
	 * This constructor for the database.
	 *
	 * @access public
	 * @param array $db_info
	 * @param boolean $connect = true
	 * @return void
	 */
	public function __construct($db_info = array(), $connect = true)
	{
		global $db_info;

		// Was is initialized with the database value?
		if (!empty($db_info))
		{
			$this->_user = $db_info['user'];
			$this->_pass = $db_info['pass'];
			$this->_name = $db_info['name'];
			$this->_host = $db_info['host'];

			// Setting the persistent connection?
			if (isset($db_info['persistent']))
				$this->_persistent = (bool) $db_info['persistent'];
		}

		// Do we want to connect?
		if ($connect == true && is_null($this->_conn))
			$this->_connect();
	}

	/**
	 * Builds the query.
	 *
	 * @access public
	 * @param string $query
	 * @param array $where = array();
	 * @return void
	 */
	/**
	 *
	 */
	function query($query)
	{
		// The query.
		$this->_query = trim($query);
		$this->_request = mysql_query($this->_query, $this->_conn);
		// Log errors.
		$this->_mysql_error = mysql_error();
		$this->_mysql_errno = mysql_errno();

		// Do we have an error?
		if ($this->_request === false)
		{
			return false;
		}
		else
		{
			$this->_mysql_errno = null;
			$this->_mysql_error = null;
			return true;
		}
	}

	/**
	 * Returns the last database error.
	 *
	 * @access public
	 * @return mixed
	 */
	public function last_error($return = false)
	{
		// This is temporary and should be made nicer.
		if ($return === true)
			return 'There was a database error.  The error was <blockquote>' . $this->_mysql_error . ' (' . $this->_mysql_errno . ') </blockquote> and query <blockquote>' . $this->_query . '</blockquote>';
		else
			die('There was a database error.  The error was <blockquote>' . $this->_mysql_error . ' (' . $this->_mysql_errno . ') </blockquote> and query <blockquote>' . $this->_query . '</blockquote>');
	}

	/**
	 * Returns the last query.
	 *
	 * @access public
	 * @return string
	 */
	public function last_query()
	{
		return $this->_query;
	}

	/**
	 * Returns an associative arrays of the results.
	 *
	 * @access public
	 * @return array
	 */
	public function assoc()
	{
		return mysql_fetch_assoc($this->_request);
	}

	/**
	 * Returns an associative array of the result.
	 *
	 * @access public
	 * @return array
	 */
	public function row()
	{
		return is_resource($this->_request) ? mysql_fetch_row($this->_request) : false;
	}

	/**
	 * Returns the number of rows.
	 *
	 * @access public
	 * @return int
	 */
	public function num_rows()
	{
		return is_resource($this->_request) ? mysql_num_rows($this->_request) : false;
	}

	/**
	 * Frees the results/resources.
	 *
	 * @access public
	 * @return void
	 */
	public function free_result()
	{
		mysql_free_result($this->_request);
	}

	/**
	 * Escapes a string.
	 *
	 * @access public
	 * @param string $value
	 * @return string $value
	 */
	public function escape($value)
	{
		return mysql_real_escape_string($value);
	}

	/**
	 * Connects to the database. Throws an exception if no connection established.
	 *
	 * @access private
	 * @return void
	 */
	private function _connect()
	{
		// Attempt to connect
		try
		{
			// Try to connect.
			$this->_conn = $this->_persistent ?
				mysql_pconnect($this->_host, $this->_user, $this->_pass) :
				mysql_connect($this->_host, $this->_user, $this->_pass);

			// Did it work?
			if ($this->_conn === false)
				throw new Exception('Could not connect to MySQL. Error: ' . mysql_error());
			else
			{
				// Try to select the database.
				$db_selected = mysql_select_db($this->_name);

				// No dice?
				if ($db_selected == false)
					throw new Exception('Could not select database. Error: ' . mysql_error());

				// We using UTF-8
				mysql_query('SET NAMES utf8', $this->_conn);
			}
		}
		// Just error out.
		catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	/**
	 * Destruct/disconnect.
	 *
	 * @access private
	 * @return void
	 */
	public function __destruct()
	{
		// If it's not persistent then close it.
		if ($this->_conn === true && $this->_persistent === false)
		{
			mysql_close($this->_conn);
			$this->_conn = null;
		}
	}
}

?>