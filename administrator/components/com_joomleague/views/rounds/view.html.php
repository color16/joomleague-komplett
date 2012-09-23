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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the Joomleague component
 *
 * @author	Marco Vaninetti <martizva@tiscali.it>
 * @package	JoomLeague
 * @since	0.1
 */
class JoomleagueViewRounds extends JLGView
{

	function display($tpl=null)
	{
		$mainframe =& JFactory::getApplication();
		if ($this->getLayout()=='default')
		{
			$this->_displayDefault($tpl);
			return;
		}
		else if ($this->getLayout()=='populate')
		{
			$this->_displayPopulate($tpl);
			return;
		}
		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$option='com_joomleague';
		$mainframe =& JFactory::getApplication();
		$db =& JFactory::getDBO();
		$uri =& JFactory::getURI();
		$matchday =& $this->get('Data');
		$total =& $this->get('Total');
		$pagination =& $this->get('Pagination');
		$model =& $this->getModel();
		$projectws =& $this->get('Data','projectws');
		
		$state = $this->get('state');
		
		$filter_order	    = $state->get('filter_order');
		$filter_order_Dir = $state->get('filter_order_Dir');

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order']	    = $filter_order;
                
		$this->assignRef('lists',$lists);
		$this->assignRef('matchday',$matchday);
		$this->assignRef('projectws',$projectws);
		$this->assignRef('pagination',$pagination);
		$this->assignRef('request_url',$uri->toString());

		parent::display($tpl);
	}

	function _displayPopulate($tpl)
	{
		$app      =& JFactory::getApplication();
		$document = &Jfactory::getDocument();
		$uri      =& JFactory::getURI();
		
		$model =& $this->getModel();
		$projectws =& $this->get('Data','projectws');
		
		$document->setTitle(JText::_('JL_ADMIN_ROUNDS_POPULATE_TITLE'));
		$version = urlencode(JoomleagueHelper::getVersion());
		$document->addScript('components/com_joomleague/assets/js/populate.js?v='.$version);
		
		JToolBarHelper::title(JText::_('JL_ADMIN_ROUNDS_POPULATE_TITLE'),'Matchdays');
		JToolBarHelper::apply('startpopulate');
		JToolBarHelper::back();
		
		$lists = array();
		
		$options = array( JHTML::_('select.option', 0, Jtext::_('JL_ADMIN_ROUNDS_POPULATE_TYPE_SINGLE_ROUND_ROBIN')),
		                  JHTML::_('select.option', 1, Jtext::_('JL_ADMIN_ROUNDS_POPULATE_TYPE_DOUBLE_ROUND_ROBIN')) 
		                  );
		$lists['scheduling'] = JHTML::_('select.genericlist', $options, 'scheduling', '', 'value', 'text');

		$teams = $this->get('projectteams');
		$options = array();
		foreach ($teams as $t) {
			$options[] = JHTML::_('select.option', $t->projectteam_id, $t->text);
		}
		$lists['teamsorder'] = JHTML::_('select.genericlist', $options, 'teamsorder[]', 'multiple="multiple" size="20"');
		
		$this->assignRef('projectws',        $projectws);
		$this->assignRef('request_url',      $uri->toString());
		$this->assignRef('lists',            $lists);
		
		parent::display($tpl);
	}
}
?>