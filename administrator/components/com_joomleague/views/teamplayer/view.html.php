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

require_once ( JPATH_COMPONENT . DS . 'helpers' . DS . 'imageselect.php' );

/**
 * HTML View class for the Joomleague component
 *
 * @static
 * @package	JoomLeague
 * @since	0.1
 */
class JoomleagueViewTeamPlayer extends JLGView
{

	function display( $tpl = null )
	{
		$mainframe	=& JFactory::getApplication();

		if ( $this->getLayout() == 'form' )
		{
			$this->_displayForm( $tpl );
			return;
		}

		//get the division
		$project_team =& $this->get( 'data' );

		parent::display( $tpl );
	}

	function _displayForm( $tpl )
	{
		$mainframe	=& JFactory::getApplication();
		$db	 		=& JFactory::getDBO();
		$uri		=& JFactory::getURI();
		$user		=& JFactory::getUser();
		$model		=& $this->getModel();
		$lists		= array();
		$projectws		=& $this->get( 'Data', 'projectws' );
		$teamws	 		=& $this->get( 'Data', 'teamws' );
		
		//get the project_player data of the project_team
		$project_player	=& $this->get( 'data' );
		$isNew			= ( $project_player->id < 1 );

		// fail if checked out not by 'me'
		if ( $model->isCheckedOut( $user->get( 'id' ) ) )
		{
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'JL_ADMIN_TEAMPLAYER_THEPLAYER' ), $project_player->name );
			$mainframe->redirect( 'index.php?option=com_joomleague', $msg );
		}

		// Edit or Create?
		if ( $isNew ) { $project_player->order = 0; }

		//build the html select list for positions
		#$selectedvalue = ( $project_player->position_id ) ? $project_player->position_id : $default_person->position_id;
		$selectedvalue = $project_player->project_position_id;
		$projectpositions = array();
		$projectpositions[] = JHTML::_('select.option',	'0', JText::_( 'JL_GLOBAL_SELECT_POSITION' ) );
		if ( $res =& $model->getProjectPositions() )
		{
			$projectpositions = array_merge( $projectpositions, $res );
		}
		$lists['projectpositions'] = JHTML::_(	'select.genericlist',
												$projectpositions,
												'project_position_id',
												'class="inputbox" size="1"',
												'value',
												'text', $selectedvalue );
		unset($projectpositions);

		$matchdays[] = JHTML::_( 'select.option',	'0', JText::_( 'JL_GLOBAL_SELECT_ROUND' ) );
		if ( $res =& JoomleagueHelper::getRoundsOptions($projectws->id) )
		{
			$matchdays = array_merge( $matchdays, $res );
		}

		// injury details
		$myoptions = array();
		$myoptions[]		= JHTML::_( 'select.option', '0', JText::_( 'JL_GLOBAL_NO' ) );
		$myoptions[]		= JHTML::_( 'select.option', '1', JText::_( 'JL_GLOBAL_YES' ) );
		$lists['injury']	= JHTML::_( 'select.radiolist',
										$myoptions,
										'injury',
										'class="inputbox" size="1"',
										'value',
										'text',
										$project_player->injury );
		unset($myoptions);

		$lists['injury_date']	 = JHTML::_( 'select.genericlist',
											$matchdays,
											'injury_date',
											'class="inputbox" size="1"',
											'value',
											'text',
											$project_player->injury_date );
		$lists['injury_end']	= JHTML::_( 'select.genericlist',
											$matchdays,
											'injury_end',
											'class="inputbox" size="1"',
											'value',
											'text',
											$project_player->injury_end );

		// suspension details
		$myoptions		= array();
		$myoptions[]	= JHTML::_('select.option', '0', JText::_( 'JL_GLOBAL_NO' ) );
		$myoptions[]	= JHTML::_('select.option', '1', JText::_( 'JL_GLOBAL_YES' ));
		$lists['suspension']		= JHTML::_( 'select.radiolist',
												$myoptions,
												'suspension',
												'class="inputbox" size="1"',
												'value',
												'text',
												$project_player->suspension );
		unset($myoptions);

		$lists['suspension_date']	 = JHTML::_( 'select.genericlist',
												$matchdays,
												'suspension_date',
												'class="inputbox" size="1"',
												'value',
												'text',
												$project_player->suspension_date );
		$lists['suspension_end']	= JHTML::_( 'select.genericlist',
												$matchdays,
												'suspension_end',
												'class="inputbox" size="1"',
												'value',
												'text',
												$project_player->suspension_end );

		// away details
		$myoptions		= array();
		$myoptions[]	= JHTML::_( 'select.option', '0', JText::_( 'JL_GLOBAL_NO' ) );
		$myoptions[]	= JHTML::_( 'select.option', '1', JText::_( 'JL_GLOBAL_YES' ) );
		$lists['away']	= JHTML::_( 'select.radiolist',
									$myoptions,
									'away',
									'class="inputbox" size="1"',
									'value',
									'text',
									$project_player->away );
		unset($myoptions);

		$lists['away_date'] = JHTML::_( 'select.genericlist',
										$matchdays,
										'away_date',
										'class="inputbox" size="1"',
										'value',
										'text',
										$project_player->away_date );
		$lists['away_end']	= JHTML::_( 'select.genericlist',
										$matchdays,
										'away_end',
										'class="inputbox" size="1"',
										'value',
										'text',
										$project_player->away_end );

        // image selector
		$default = JoomleagueHelper::getDefaultPlaceholder("player");
    
    /*
    echo 'getDefaultPlaceholder<br>';
    echo '<pre>'.print_r($default,true).'</pre><br>';
    echo 'project_player->picture<br>';
    echo '<pre>'.print_r($project_player->picture,true).'</pre><br>';
    */
    
		if (empty($project_player->picture)){$project_player->picture=$default;}		
		$imageselect	= ImageSelect::getSelector('picture','picture_preview','persons',$project_player->picture,$default);
		
		
		/*
		 * extended data
		 */
		$paramsdata = $project_player->extended;
		$paramsdefs = JPATH_COMPONENT . DS . 'assets' . DS . 'extended' . DS .'teamplayer.xml';
		$extended = new JLGExtraParams( $paramsdata, $paramsdefs );

		$this->assignRef( 'extended',		$extended );
		#$this->assignRef( 'default_person',	$default_person );
		$this->assignRef( 'imageselect',	$imageselect);
		$this->assignRef( 'projectws',		$projectws );
		$this->assignRef( 'teamws',			$teamws );
		$this->assignRef( 'lists',			$lists );
		$this->assignRef( 'project_player',	$project_player );

		parent::display( $tpl );
	}

}
?>