<?php
/**
 * @copyright	Copyright (C) 2006-2012 JoomLeague.net. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
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
 * @author	Kurt Norgaz
 * @package	JoomLeague
 * @since	1.5
 */
class JoomleagueViewUpdates extends JLGView
{
	function display($tpl=null)
	{
		$option='com_joomleague';
		$mainframe =& JFactory::getApplication();
		$mainframe->setUserState($option.'update_part',0); // 0
		$filter_order		= $mainframe->getUserStateFromRequest($option.'updates_filter_order',		'filter_order',		'dates',	'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'updates_filter_order_Dir',	'filter_order_Dir',	'',			'word');
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('JL_ADMIN_UPDATES_TITLE'),'config');
		JToolBarHelper::help('screen.joomleague',true);
		$db =& JFactory::getDBO();
		$uri =& JFactory::getURI();
		$model =& $this->getModel();
		$versions=$model->getVersions();
		$updateFiles = array();
		$lists=array();
		if($updateFiles=$model->loadUpdateFiles()) {
			for ($i=0, $n=count($updateFiles); $i < $n; $i++)
			{
				foreach ($versions as $version)
				{
					if (strpos($version->version,$updateFiles[$i]['file_name']))
					{
						$updateFiles[$i]['updateTime']=$version->date;
						break;
					}
					else
					{
						$updateFiles[$i]['updateTime']="-";
					}
				}
			}
		}
		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;
		$this->assignRef('updateFiles',$updateFiles);
		$this->assignRef('request_url',$uri->toString());
		$this->assignRef('lists',$lists);
		parent::display($tpl);
	}
}
?>