<?php defined('_JEXEC') or die('Restricted access'); // Check to ensure this file is included in Joomla!
/**
* @copyright	Copyright (C) 2005-2012 JoomLeague.net. All rights reserved.
* @license		GNU/GPL,see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License,and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Joomleague Component Update/Samples Controller
 *
 * @author		Kurt Norgaz
 * @package		JoomLeague
 * @since		1.5
 */
class JoomleagueControllerUpdate extends JoomleagueCommonController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('samples','display');
		$this->registerTask('updates','display');
	}

	function display()
	{
		switch ($this->getTask())
		{
			case 'updates'		:	JRequest::setVar('layout','updates');
									break;
			default				:	JRequest::setVar('layout','updates');
		}

		parent::display();
	}

	function save()
	{
		JToolBarHelper::back(JText::_('JL_BACK_UPDATELIST'),JRoute::_('index.php?option=com_joomleague&view=updates&controller=update'));
		$post=JRequest::get('post');
		$file_name=JRequest::getVar('file_name');
		$path=explode('/',$file_name);
		if (count($path) > 1)
		{
			$filepath=JPATH_COMPONENT_SITE.DS.'extensions'.DS.$path[0].DS.'admin'.DS.'install'.DS.$path[1];
		}
		else
		{
			$filepath=JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'updates'.DS.$path[0];
		}
		$model=$this->getModel('updates');
		echo JText::sprintf('Updating from file [%s]','<b>'.$filepath.'</b>');
		if (JFile::exists($filepath))
		{
			$model->loadUpdateFile($filepath,$file_name);
		}
		else
		{
			echo JText::_('Update file not found!');
		}
	}
}
?>