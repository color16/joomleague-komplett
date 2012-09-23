<?php defined('_JEXEC') or die('Restricted access');
/*
@copyright	Copyright (C) 2005-2012 JoomLeague.net. All rights reserved.
@license		GNU/GPL, see LICENSE.php
Joomla! is free software. This version may have been modified pursuant
to the GNU General Public License, and as distributed it includes or
is derivative of works licensed under the GNU General Public License or
other free or open source software licenses.
See COPYRIGHT.php for copyright notices and details.
*/

/*
Model class for the Joomleague component

@author		JoomLeague Team <www.joomleague.net>
@package	JoomLeague
@since		0.1
*/

jimport('joomla.application.component.model');

require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

class JoomleagueModelAbout extends JoomleagueModelProject
{
	function getAbout()
	{
		$about = new stdClass();

		//author
		$about->author = 'Joomleague-Team';

		//page
		$about->page = 'http://www.joomleague.net';

		//e-mail
		$about->email = 'http://www.JoomLeague.net/forum/index.php?action=contact';

		//forum
		$about->forum = 'http://forum.joomleague.net';
		
		//bugtracker
		$about->bugs = 'http://bugtracker.joomleague.net';
		
		//wiki
		$about->wiki = 'http://wiki.joomleague.net';
		
		//date
		$about->date = '2012-01-04';

		//developer
		$about->developer = '<a href="http://stats.joomleague.net/authors.html" target="_blank">JoomLeague-Team</a>';

		//designer
		$about->designer = 'Kasi';
		$about->designer .= ', <a href="http://www.cg-design.net" target="_blank">cg design</a>&nbsp;(Carsten Grob) ';

		//icons
		$about->icons = '<a href="http://www.hollandsevelden.nl/iconset/" target="_blank">Jersey Icons</a> (Hollandsevelden.nl)';
		$about->icons .= ', <a href="http://www.famfamfam.com/lab/icons/silk/" target="_blank">Silk / Flags Icons</a> (Mark James)';
		$about->icons .= ', Panel images (Kasi)';

		//flash
		$about->flash = '<a href="http://teethgrinder.co.uk/open-flash-chart-2/" target="_blank">Open Flash Chart 2.x</a>';

		//graphoc library
		$about->graphic_library = '<a href="http://www.walterzorn.com" target="_blank">www.walterzorn.com</a>';
		
		//phpthumb class
		$about->phpthumb = '<a href="http://phpthumb.gxdlabs.com/" target="_blank">phpthumb.gxdlabs.com</a>';


		$this->_about = $about;

		return $this->_about;
	}

}
?>