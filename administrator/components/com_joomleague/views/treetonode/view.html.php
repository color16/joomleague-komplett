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
class JoomleagueViewTreetonode extends JLGView
{
	function display( $tpl = null )
	{
		if ( $this->getLayout() == 'form' )
		{
			$this->_displayForm( $tpl );
			return;
		}

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
		
		$node =& $this->get('data');
		$match =& $model->getNodeMatch();
		$total =& $this->get('Total');
		$pagination =& $this->get('Pagination');
		$projectws =& $this->get( 'Data', 'projectws' );
		
		$model =& $this->getModel('projectws');
		$mdlTreetonodes = JModel::getInstance("Treetonodes", "JoomleagueModel");
		$team_id[]=JHTML::_('select.option','0',JText::_('JL_GLOBAL_SELECT_TEAM'));
		if($projectteams =& $mdlTreetonodes->getProjectTeamsOptions($model->_id))
		{
			$team_id=array_merge($team_id,$projectteams);
		}
		$lists['team']=$team_id;
		unset($team_id);

		$this->assignRef( 'user',		JFactory::getUser() );
		$this->assignRef( 'projectws',		$projectws );
		$this->assignRef( 'lists',		$lists );
		$this->assignRef( 'division',		$division );
		$this->assignRef( 'division_id',	$division_id );
		$this->assignRef( 'node',		$node );
		$this->assignRef( 'match',		$match );
		$this->assignRef( 'pagination',		$pagination );
		parent::display( $tpl );
	}

}
?>