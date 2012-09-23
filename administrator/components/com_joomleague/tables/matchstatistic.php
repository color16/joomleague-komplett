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
jimport( 'joomla.filter.input' );

/**
* Match statistic Table class
*
* @package		Joomleague
* @since 1.5
*/
class TableMatchStatistic extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id= NULL;

	/**
	 * match id
	 *
	 * @var int
	 */
	var $match_id;
	
	/**
	 * project team id
	 * it is needed in the case we want to have events assigned to unknown players
	 *
	 * @var int
	 */
	var $projectteam_id;

	/**
	 * player id
	 *
	 * @var int
	 */
	var $teamplayer_id;

	/**
	 * statistic id
	 *
	 * @var int
	 */
	var $statistic_id;

	/**
	 * value
	 *
	 * @var float
	 */
	var $value;

	var $checked_out;
	var $checked_out_time;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( & $db )
	{
		parent::__construct( '#__joomleague_match_statistic', 'id', $db );
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
		if ( ! ( $this->statistic_id && $this->projectteam_id && $this->match_id) )
		{
			$this->setError( JText::_( 'CHECK FAILED' ) );
			return false;
		}
		return true;
	}
}
?>