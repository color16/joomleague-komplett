<?php
/**
* @copyright	Copyright (C) 2005-2012 JoomLeague.net. All rights reserved.
* @license		GNU/GPL,see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License,and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
require_once (JPATH_COMPONENT.DS.'controllers'.DS.'joomleague.php');

/**
 * Joomleague Component League Controller
 *
 * @package	JoomLeague
 * @since	0.1
 */
class JoomleagueControllerLeague extends JoomleagueCommonController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('add','display');
		$this->registerTask('edit','display');
		$this->registerTask('apply','save');
	}

	function display()
	{
		switch ($this->getTask())
		{
			case 'add'	 :
			{
				JRequest::setVar('hidemainmenu',0);
				JRequest::setVar('layout','form');
				JRequest::setVar('view','league');
				JRequest::setVar('edit',false);
				// Checkout the project
				$model=$this->getModel('league');
				$model->checkout();
			} break;
			case 'edit'	:
			{
				JRequest::setVar('hidemainmenu',0);
				JRequest::setVar('layout','form');
				JRequest::setVar('view','league');
				JRequest::setVar('edit',true);
				// Checkout the project
				$model=$this->getModel('league');
				$model->checkout();
			} break;
		}
		parent::display();
	}

  function saveleaguesshort()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('JL_GLOBAL_INVALID_TOKEN');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(0),'post','array');
		JArrayHelper::toInteger($cid);
		for ($x=0; $x < count($cid); $x++)
		{
		$league_id = $cid[$x];
		$table = 'league';
    $rowleague =& JTable::getInstance( $table, 'Table' );
    $rowleague->load( $league_id );
    $rowleague->league_level = $post['league_level'][$league_id];
    $rowleague->ordering = $post['order'][$x];
    if ( !$rowleague->store() )
    {
    }
		
		
		}
		$msg=JText::_('JL_ADMIN_LEAGUE_CTRL_SAVED');
		$link='index.php?option=com_joomleague&view=leagues';
		$this->setRedirect($link,$msg);
	}
  	
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('JL_GLOBAL_INVALID_TOKEN');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(0),'post','array');
		$post['id']=(int) $cid[0];
		
    
    if (isset($post['promotion_to']))
		{
			if (count($post['promotion_to']) > 0)
			{
				$temp=implode(",",$post['promotion_to']);
			}
			else
			{
				$temp='';
			}
			$post['promotion_to']=$temp;
		}
		else
		{
			$post['promotion_to']='';
		}
		
		if (isset($post['relegation_to']))
		{
			if (count($post['relegation_to']) > 0)
			{
				$temp=implode(",",$post['relegation_to']);
			}
			else
			{
				$temp='';
			}
			$post['relegation_to']=$temp;
		}
		else
		{
			$post['relegation_to']='';
		}
		
		
    $model=$this->getModel('league');
		if ($model->store($post))
		{
			$msg=JText::_('JL_ADMIN_LEAGUE_CTRL_SAVED');
		}
		else
		{
			$msg=JText::_('JL_ADMIN_LEAGUE_CTRL_ERROR_SAVE').$model->getError();
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		if ($this->getTask()=='save')
		{
			$link='index.php?option=com_joomleague&view=leagues';
		}
		else
		{
			$link='index.php?option=com_joomleague&controller=league&task=edit&cid[]='.$post['id'];
		}
		$this->setRedirect($link,$msg);
	}

	function remove()
	{
		JRequest::checkToken() or die('JL_GLOBAL_INVALID_TOKEN');
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1){JError::raiseError(500,JText::_('JL_GLOBAL_SELECT_TO_DELETE'));}
		$model=$this->getModel('league');
		if (!$model->delete($cid))
		{
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
			return;
		}
		else
		{
			$msg=JText::_('JL_ADMIN_LEAGUE_CTRL_DELETED');
		}
		$this->setRedirect('index.php?option=com_joomleague&view=leagues',$msg);
	}

	function cancel()
	{
		// Checkin the project
		$model=$this->getModel('league');
		$model->checkin();
		$this->setRedirect('index.php?option=com_joomleague&view=leagues');
	}

	function orderup()
	{
		$model=$this->getModel('league');
		$model->move(-1);
		$this->setRedirect('index.php?option=com_joomleague&view=leagues');
	}

	function orderdown()
	{
		$model=$this->getModel('league');
		$model->move(1);
		$this->setRedirect('index.php?option=com_joomleague&view=leagues');
	}

	function saveorder()
	{
		JRequest::checkToken() or die('JL_GLOBAL_INVALID_TOKEN');
		$cid=JRequest::getVar('cid',array(),'post','array');
		$order=JRequest::getVar('order',array(),'post','array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		$model=$this->getModel('league');
		$model->saveorder($cid,$order);
		$msg=JText::_('JL_GLOBAL_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_joomleague&view=leagues',$msg);
	}

	function import()
	{
		JRequest::setVar('view','import');
		JRequest::setVar('table','league');
		parent::display();
	}
	
	function export()
	{
		JRequest::checkToken() or die('JL_GLOBAL_INVALID_TOKEN');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1){JError::raiseError(500,JText::_('JL_GLOBAL_SELECT_TO_EXPORT'));}
		$model = $this->getModel("league");
		$model->export($cid, "league", "League");
	}
}
?>