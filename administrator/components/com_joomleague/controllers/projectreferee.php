<?php defined( '_JEXEC' ) or die( 'Restricted access' ); // Check to ensure this file is included in Joomla!
/**
* @copyright	Copyright (C) 2007 Joomteam.de. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

jimport( 'joomla.application.component.controller' );

/**
 * Joomleague Component Controller
 *
 * @author	Kurt Norgaz
 * @package	Joomleague
 * @since	1.5.02a
 */
class JoomleagueControllerProjectReferee extends JoomleagueCommonController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add', 'display' );
		$this->registerTask( 'edit', 'display' );
		$this->registerTask( 'apply', 'save' );
	}

	function display()
	{
		$option='com_joomleague';

		$mainframe	=& JFactory::getApplication();
		$document	=& JFactory::getDocument();
		$model		= $this->getModel ( 'projectreferees' );
		$viewType	= $document->getType();
		$view		= $this->getView  ( 'projectreferees', $viewType );
		$view->setModel( $model, true );  // true is for the default model;

		$projectws	= $this->getModel ( 'project' );
		$projectws->_name = 'projectws';
		$projectws->setId( $mainframe->getUserState( $option . 'project', 0 ) );
		$view->setModel( $projectws );

		switch($this->getTask())
		{
			case 'add'	 :
				{
					JRequest::setVar( 'hidemainmenu', 0 );
					JRequest::setVar( 'layout', 'form' );
					JRequest::setVar( 'view', 'projectreferee' );
					JRequest::setVar( 'edit', false );

					$model = $this->getModel( 'projectreferee' );
					#$model->checkout();
				} break;

			case 'edit'	:
				{
					$model = $this->getModel ( 'projectreferee' );
					$viewType = $document->getType();
					$view = $this->getView  ( 'projectreferee', $viewType );
					$view->setModel( $model, true );  // true is for the default model;

					$projectws = $this->getModel ( 'project' );
					$projectws->_name = 'projectws';
					$projectws->setId( $mainframe->getUserState( $option . 'project', 0 ) );
					$view->setModel( $projectws );

					/*
					$teamws = $this->getModel ( 'projectteam' );
					$teamws->_name = 'teamws';

					$teamws->setId(  $mainframe->getUserState( $option . 'team', 0 ) );
					$view->setModel( $teamws );
					// $playerws = $this->getModel ( 'player' );
					// $playerws->_name = 'playerws';
					// $view->setModel( $playerws );
					*/

					JRequest::setVar( 'hidemainmenu', 0 );
					JRequest::setVar( 'layout', 'form' );
					JRequest::setVar( 'view', 'projectreferee' );
					JRequest::setVar( 'edit', true );

					// Checkout the project
					$model = $this->getModel( 'projectreferee' );
					#$model->checkout();
				} break;

		}
		parent::display();
	}

	function editlist()
	{
		$option='com_joomleague';
		$mainframe	=& JFactory::getApplication();
		$document =& JFactory::getDocument();

		$model = $this->getModel ( 'projectreferees' );
		$viewType = $document->getType();
		$view = $this->getView  ( 'projectreferees', $viewType );
		$view->setModel( $model, true );  // true is for the default model;

		$projectws = $this->getModel ( 'project' );
		$projectws->_name = 'projectws';
		$projectws->setId( $mainframe->getUserState( $option . 'project', 0 ) );
		$view->setModel( $projectws );

		$teamws->setId(  $mainframe->getUserState( $option . 'team', 0 ) );
		$view->setModel( $teamws );

		JRequest::setVar( 'hidemainmenu', 1 );
		JRequest::setVar( 'layout', 'editlist' );
		JRequest::setVar( 'view', 'projectreferees' );
		JRequest::setVar( 'edit', true );

		parent::display();
	}


	function save_projectrefereeslist()
	{
		$post 		= JRequest::get( 'post' );
		$cid 		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$project 	= JRequest::getVar( 'project', 'post' );
		$team_id 	= JRequest::getVar( 'team', 'post' );
		$post['id'] 		= (int) $cid[0];
		$post['project_id']	= (int) $project;
		$post['team_id']   	= (int) $team_id;
		$model = $this->getModel( 'projectreferees' );

		if ( $model->store( $post ) )
		{
			$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_SAVED' );
		}
		else
		{
			$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_ERROR_SAVE' ) . $model->getError();
		}

		// Check the table in so it can be edited.... we are done with it anyway
		//$model->checkin();
		//if ( $this->getTask() == 'save' ) $link = 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees';
		//else $link = 'index.php?option=com_joomleague&controller=projectreferee&task=edit&cid[]='.$post['id'];
		$link = 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees';

		$this->setRedirect( $link, $msg );
	}

	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'JL_GLOBAL_INVALID_TOKEN' );

		$post = JRequest::get( 'post' );
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];

		// decription must be fetched without striping away html code
		$post['notes'] = JRequest::getVar( 'notes', 'none', 'post', 'STRING', JREQUEST_ALLOWHTML );

		$model = $this->getModel( 'projectreferee' );

		#echo '<pre>'; print_r($post); echo '</pre>';

		if ( $model->store( $post ) )
		{
			$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_SAVED' );

		}
		else
		{
			$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_ERROR_SAVE' ) . $model->getError();
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		if ( $this->getTask() == 'save' )
		{
			$link = 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees';
		}
		else
		{
			$link = 'index.php?option=com_joomleague&controller=projectreferee&task=edit&cid[]=' . $post['id'];
		}
		#echo $msg;
		$this->setRedirect( $link, $msg );
	}

	// save the checked rows inside the project teams list
	function saveshort()
	{
		$post = JRequest::get( 'post' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );

		$model = $this->getModel( 'projectreferees' );
		/*
		for ( $x = 0; $x < count( $cid ); $x++ )
		{
			$post['birthday' . $cid[$x]] = JoomleagueHelper::convertDate( $post['birthday' . $cid[$x]], 0 );
		}
		*/
		#echo '<pre>'; print_r($post); echo '</pre>';
		#$model->storeshort( $cid, $post );

		if ( $model->storeshort( $cid, $post ) )
		{
			$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_UPDATED' );
		}
		else
		{
			$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_ERROR_UPDATED' ) . $model->getError();
		}

		$link = 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees';
		$this->setRedirect( $link, $msg );
	}

	function remove()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );

		if ( count( $cid ) < 1 )
		{
			JError::raiseError(500, JText::_( 'JL_GLOBAL_SELECT_TO_DELETE' ) );
		}

		$model = $this->getModel( 'team' );

		if( !$model->delete($cid) )
		{
			echo "<script> alert('" . $model->getError( true ) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_joomleague&view=teams' );
	}

	function publish()
	{
		$this->setRedirect( 'index.php?option=com_joomleague&view=teams' );
	}

	function unpublish()
	{
		$this->setRedirect( 'index.php?option=com_joomleague&view=teams' );
	}

	function cancel()
	{
		// Checkin the project
		$model = $this->getModel( 'projectreferee' );
		//$model->checkin();

		$this->setRedirect( 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees');
	}

	function orderup()
	{
		$model = $this->getModel( 'projectreferee' );
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees');
	}

	function orderdown()
	{
		$model = $this->getModel( 'projectreferee' );
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees');
	}

	function saveorder()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order = JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		JArrayHelper::toInteger( $order );

		$model = $this->getModel( 'projectreferee' );
		$model->saveorder( $cid, $order );

		$msg =  JText::_( 'JL_GLOBAL_NEW_ORDERING_SAVED' );
		$this->setRedirect( 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees', $msg );
	}

	function select()
	{
		$option='com_joomleague';
		$mainframe	=& JFactory::getApplication();

		$mainframe->setUserState( $option . 'team', JRequest::getVar( 'team' ) );
		$this->setRedirect( 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees' );
	}

	function assign()
	{
		//redirect to ProjectReferees page, with a message
		$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_ASSIGN' );
		$this->setRedirect( 'index.php?option=com_joomleague&view=persons&layout=assignplayers&type=2&hidemainmenu=1', $msg );
	}

	function unassign()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		$model = $this->getModel( 'projectreferees' );
		$nDeleted = $model->unassign( $cid );
		if ( !$nDeleted )
		{
			$msg = JText::_( 'JL_ADMIN_P_REFEREE_CTRL_UNASSIGN' );
		}
		else
		{
			$msg = JText::sprintf( 'JL_ADMIN_P_REFEREE_CTRL_UNASSIGNED', $nDeleted );
		}
		#echo '<br />' . $msg;
		//redirect to projectreferee page, with a message
		$this->setRedirect( 'index.php?option=com_joomleague&controller=projectreferee&view=projectreferees', $msg );
	}

}
?>