<?php
/**
* @copyright	Copyright (C) 2007-2012 JoomLeague.net. All rights reserved.
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
 * @author	Kurt Norgaz
 * @package	JoomLeague
 * @since	1.5.01a
 */

class JoomleagueViewPredictionMembers extends JLGView
{

  function display( $tpl = null )
	{


    if ( $this->getLayout() == 'default')
		{
			$this->_display( $tpl );
			return;
		}
		
		if ( $this->getLayout() == 'editlist')
		{
			$this->_editlist( $tpl );
			return;
		}
		
	}

  function _editlist( $tpl = null )
	{
		$mainframe			=& JFactory::getApplication();
		$option				= 'com_joomleague';
    $db					=& JFactory::getDBO();
		$uri				=& JFactory::getURI();
		$document =& JFactory::getDocument();
		//$model				=& $this->getModel();
		
		$document->addScript('/components/com_joomleague/extensions/predictiongame/admin/assets/js/Autocompleter.js');
		$document->addScript('/components/com_joomleague/extensions/predictiongame/admin/assets/js/Autocompleter.Local.js');
		$document->addScript('/components/com_joomleague/extensions/predictiongame/admin/assets/js/Autocompleter.Request.js');
		$document->addScript('/components/com_joomleague/extensions/predictiongame/admin/assets/js/Observer.js');

 		$document->addStylesheet('/components/com_joomleague/extensions/predictiongame/admin/assets/css/Autocompleter.css');
    		
		$prediction_id		= (int) $mainframe->getUserState( $option . 'prediction_id' );
		$prediction_name =& $this->getModel()->getPredictionProjectName($prediction_id);
		$this->assignRef( 'prediction_name',			$prediction_name );
		
    $res_prediction_members =& $this->getModel()->getPredictionMembers($prediction_id);
    
    if ( $res_prediction_members )
    {
    $lists['prediction_members']=JHTML::_(	'select.genericlist',
										$res_prediction_members,
										'prediction_members[]',
										'class="inputbox" multiple="true" onchange="" size="15"',
										'value',
										'text');
    }
    else
    {
    $lists['prediction_members'] = '<select name="prediction_members[]" id="prediction_members" style="" class="inputbox" multiple="true" size="15"></select>';
    }
    
    $res_joomla_members =& $this->getModel()->getJLUsers($prediction_id);
    if ( $res_joomla_members )
    {
    $lists['members']=JHTML::_(	'select.genericlist',
										$res_joomla_members,
										'members[]',
										'class="inputbox" multiple="true" onchange="" size="15"',
										'value',
										'text');
    }
                    																
    $this->assignRef( 'prediction_id',			$prediction_id );
    $this->assignRef( 'lists',			$lists );
    $this->assignRef('request_url',$uri->toString());
    
		parent::display( $tpl );
	}	

	function _display( $tpl = null )
	{
		$mainframe			=& JFactory::getApplication();
		$option				= 'com_joomleague';

		$prediction_id		= (int) $mainframe->getUserState( $option . 'prediction_id' );
//echo '#' . $prediction_id . '#<br />';
		$lists				= array();
		$db					=& JFactory::getDBO();
		$uri				=& JFactory::getURI();
		$items				=& $this->get( 'Data' );
		$total				=& $this->get( 'Total' );
		$pagination			=& $this->get( 'Pagination' );
		//$model				=& $this->getModel();
		$filter_state		= $mainframe->getUserStateFromRequest( $option . 'tmb_filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $mainframe->getUserStateFromRequest( $option . 'tmb_filter_order',		'filter_order',		'u.username',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option . 'tmb_filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $option . 'tmb_search',				'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		// state filter
		$lists['state']		= JHTML::_( 'grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search'] = $search;

		//build the html select list for prediction games
		$predictions[] = JHTML::_( 'select.option', '0', '- ' . JText::_( 'JL_GLOBAL_SELECT_PRED_GAME' ) . ' -', 'value', 'text' );
		if ( $res =& $this->getModel()->getPredictionGames() ) { $predictions = array_merge( $predictions, $res ); }
		$lists['predictions'] = JHTML::_(	'select.genericlist',
											$predictions,
											'prediction_id',
											'class="inputbox" onChange="this.form.submit();" ',
											'value',
											'text',
											$prediction_id
										);
		unset( $res );

		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'JL_ADMIN_PMEMBERS_TITLE' ), 'generic.png' );

		JToolBarHelper::custom( 'reminder', 'send.png', 'send_f2.png', JText::_( 'JL_ADMIN_PMEMBERS_SEND_REMINDER' ), true );
		JToolBarHelper::divider();
		
		if ( $prediction_id )
		{
    JToolBarHelper::custom('editlist','upload.png','upload_f2.png',JText::_('JL_ADMIN_PMEMBERS_BUTTON_ASSIGN'),false);
 		JToolBarHelper::divider();
 		}
		JToolBarHelper::publishList( 'publish', JText::_( 'JL_ADMIN_PMEMBERS_APPROVE' ) );
		JToolBarHelper::unpublishList( 'unpublish', JText::_( 'JL_ADMIN_PMEMBERS_REJECT' ) );
		JToolBarHelper::divider();

		//JToolBarHelper::addNewX();
		//JToolBarHelper::divider();

		//JToolBarHelper::editListX();
		JToolBarHelper::deleteList( JText::_( 'JL_ADMIN_PMEMBERS_DELETE' ) );
		JToolBarHelper::divider();

		JToolBarHelper::help( 'screen.joomleague', true );

		$this->assignRef( 'user',			JFactory::getUser() );
		$this->assignRef( 'lists',			$lists );
		
		if ( $prediction_id )
		{
		$this->assignRef( 'items',			$items );
		}
		
		$this->assignRef( 'pagination',		$pagination );

		parent::display( $tpl );
	}

}
?>