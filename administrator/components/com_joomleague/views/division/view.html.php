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

jimport( 'joomla.application.component.view' );

/**
 * HTML View class for the Joomleague component
 *
 * @static
 * @package		Joomleague
 * @since 0.1
 */
class JoomleagueViewDivision extends JLGView
{
	function display( $tpl = null )
	{
		if ( $this->getLayout() == 'form' )
		{
			$this->_displayForm( $tpl );
			return;
		}

		//get the division
		$division =& $this->get( 'data' );

		parent::display( $tpl );
	}

	function _displayForm( $tpl )
	{
		$option='com_joomleague';

		$mainframe	=& JFactory::getApplication();
		$project_id = $mainframe->getUserState( 'com_joomleagueproject' );
		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

		$lists = array();
		//get the division
		$division	=& $this->get( 'data' );
		$isNew		= ( $division->id < 1 );

		// fail if checked out not by 'me'
		if ( $model->isCheckedOut( $user->get( 'id' ) ) )
		{
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'JL_DIVISION_THE_DIVISION' ), $division->name );
			$mainframe->redirect( 'index.php?option=' . $option, $msg );
		}

		// Edit or Create?
		if ( !$isNew )
		{
			$model->checkout( $user->get( 'id' ) );
		}
		else
		{
			// initialise new record
			$division->order	= 0;
		}

		// build the html select list for ordering
		$query = '	SELECT	ordering AS value,
							name AS text
					FROM #__joomleague_division
					WHERE project_id = ' . $project_id . '
					ORDER BY ordering';

		$lists['ordering'] = JHTML::_( 'list.specificordering', $division, $division->id, $query, 1 );

		$projectws =& $this->get( 'Data', 'projectws' );

		//build the html select list for parent divisions
		$parents[] = JHTML::_( 'select.option', '0', JText::_( 'JL_GLOBAL_SELECT_PROJECT' ) );
		if ( $res =& $model->getParentsDivisions() )
		{
			$parents = array_merge( $parents, $res );
		}
		$lists['parents'] = JHTML::_(	'select.genericlist', $parents, 'parent_id', 'class="inputbox" size="1"', 'value', 'text',
										$division->parent_id );
		unset( $parents );

		$this->assignRef( 'projectws',	$projectws );
		$this->assignRef( 'lists',		$lists );
		$this->assignRef( 'division',	$division );

		parent::display( $tpl );
	}

}
?>