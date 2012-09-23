<?php
/**
 * @copyright	Copyright (C) 2006-2012 JoomLeague.net. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include library dependencies
jimport('joomla.filter.input');

/**
* Season Table class
*
* @package		Joomleague
* @since 0.1
*/
class TableTeam extends JLTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	var $club_id = null;

	var $name;
	/**
	 * alias for nice sef urls
	 * @var string
	 */
	var $alias;
	var $middle_name;
	var $short_name;

	var $website;
	var $info;
	var $notes;
	var $picture;
	
	var $ordering;
	var $checked_out;
	var $checked_out_time;
	/**
	 * stores extended info
	 *
	 * @var string
	 */
	var $extended;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__joomleague_team', 'id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check()
	{
		if (empty($this->name)) {
			$this->setError(JText::_('NAME REQUIRED'));
			return false;
		}
		
		// add default middle size name
		if (empty($this->middle_name)) {
			$parts = explode(" ", $this->name);
			$this->middle_name = substr($parts[0], 0, 20);
		}
	
		// add default short size name
		if (empty($this->short_name)) {
			$parts = explode(" ", $this->name);
			$this->short_name = substr($parts[0], 0, 2);
		}
	
		// setting alias
		if ( empty( $this->alias ) )
		{
			$this->alias = JFilterOutput::stringURLSafe( $this->name );
		}
		else {
			$this->alias = JFilterOutput::stringURLSafe( $this->alias ); // make sure the user didn't modify it to something illegal...
		}
		
		return true;
	}
}
?>
