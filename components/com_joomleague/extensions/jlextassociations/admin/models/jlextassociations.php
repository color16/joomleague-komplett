<?php
/**
 * @copyright	Copyright (C) 2006-2009 JoomLeague.net. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once (JPATH_COMPONENT.DS.'models'.DS.'list.php');

/**
 * Joomleague Component Seasons Model
 *
 * @package	JoomLeague
 * @since	0.1
 */
class JoomleagueModeljlextassociations extends JoomleagueModelList
{
	var $_identifier = "jlextassociations";
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();

		$query='	SELECT	objassoc.*,
							u.name AS editor
					FROM #__joomleague_associations AS objassoc
					LEFT JOIN #__users AS u ON u.id=objassoc.checked_out ' .
					$where .
					$orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$option='com_joomleague';
		$mainframe =& JFactory::getApplication();
		$filter_order		= $mainframe->getUserStateFromRequest($option.'lassoc_filter_order',		'filter_order',		'objassoc.ordering',	'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'lassoc_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		if ($filter_order == 'objassoc.ordering')
		{
			$orderby=' ORDER BY objassoc.ordering '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY '.$filter_order.' '.$filter_order_Dir.',objassoc.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$option='com_joomleague';
		$mainframe =& JFactory::getApplication();
		$filter_order		= $mainframe->getUserStateFromRequest($option.'lassoc_filter_order',		'filter_order',		'objassoc.ordering',	'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'lassoc_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $mainframe->getUserStateFromRequest($option.'lassoc_search',			'search',			'',				'string');
		$search=JString::strtolower($search);
		$where=array();
		if ($search)
		{
			$where[]='LOWER(objassoc.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		$where=(count($where) ? ' WHERE '.implode(' AND ',$where) : '');
		return $where;
	}

	/**
	 * Method to return a leagues array (id,name)
	 *
	 * @access	public
	 * @return	array seasons
	 * @since	1.5.0a
	 */
	function getLeagues()
	{
		$db =& JFactory::getDBO();
		$query='SELECT id, name FROM #__joomleague_associations ORDER BY name ASC ';
		$db->setQuery($query);
		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return array();
		}
		foreach ($result as $league){
			$league->name = JText::_($league->name); 
		}
		return $result;
	}
}
?>