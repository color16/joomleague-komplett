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

/*
geaendert nach thread im forum
http://forum.joomleague.net/viewtopic.php?f=108&t=15320

http://forum.joomleague.net/viewtopic.php?f=108&t=15325

http://forum.joomleague.net/viewtopic.php?f=108&t=15281

*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.filesystem.file');
jimport('joomla.utilities.array');
jimport('joomla.utilities.arrayhelper') ;
jimport( 'joomla.utilities.utility' );


//require_once('project.php');
//require_once('predictionusers.php');
require_once('prediction.php');

/**
 * Joomleague Component prediction Ranking Model
 *
 * @author	Kurt Norgaz
 * @package	JoomLeague
 * @since	1.5.100627
 */
class JoomleagueModelPredictionRanking extends JoomleagueModelPrediction
{
	var $_roundNames = null;
    var $predictionGameID = 0;
    
   /**
   * Items total
   * @var integer
   */
  var $_total = null;
 
  /**
   * Pagination object
   * @var object
   */
  var $_pagination = null;
  
  
	function __construct()
	{
		parent::__construct();
        
    $mainframe = JFactory::getApplication();
    $this->predictionGameID	= JRequest::getInt('prediction_id',0);
    
	// Get pagination request variables
	$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
	// In case limit has been changed, adjust it
	$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
	$this->setState('limit', $limit);
	$this->setState('limitstart', $limitstart);
    
	}

function _buildQuery()
{
	$query=	"	SELECT	pm.id AS pmID,
				pm.user_id AS user_id,
				pm.picture AS avatar,
				pm.show_profile AS show_profile,
				pm.champ_tipp AS champ_tipp,
                pm.aliasName as aliasName,
				u.name AS name
				FROM #__joomleague_prediction_member AS pm
				INNER JOIN #__users AS u ON u.id = pm.user_id
				WHERE pm.prediction_id = $this->predictionGameID
				ORDER BY pm.id ASC";
                
return $query;                            
}

function getData() 
  {
 	// if data hasn't already been obtained, load it
 	if (empty($this->_data)) 
     {
 	    $query = $this->_buildQuery();
        //$query = $this->getPredictionMember();
 	    $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));	
 	}
 	return $this->_data;
  }
  
function getTotal()
  {
 	// Load the content if it doesn't already exist
 	if (empty($this->_total)) 
     {
 	    $query = $this->_buildQuery();
        //$query = $this->getPredictionMember();
 	    $this->_total = $this->_getListCount($query);	
 	}
 	return $this->_total;
  }

function getPagination()
  {
 	// Load the content if it doesn't already exist
 	if (empty($this->_pagination)) 
     {
 	    jimport('joomla.html.pagination');
 	    $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
 	}
 	return $this->_pagination;
  }    



  function getDebugInfo()
  {
  $show_debug_info = JComponentHelper::getParams('com_joomleague')->get('show_debug_info',0);
  if ( $show_debug_info )
  {
  return true;
  }
  else
  {
  return false;
  }
  
  }
  
	function getMatches($roundID,$project_id)
	{
		if ($roundID==0){$roundID=1;}
		$query = 	"	SELECT	m.id AS mID,
								m.match_date,
								m.team1_result AS homeResult,
								m.team2_result AS awayResult,
								m.team1_result_decision AS homeDecision,
								m.team2_result_decision AS awayDecision,
								t1.name AS homeName,
								t2.name AS awayName,
								c1.logo_small AS homeLogo,
								c2.logo_small AS awayLogo

						FROM #__joomleague_match AS m

						INNER JOIN #__joomleague_round AS r ON	r.id=m.round_id AND
																r.project_id=$project_id AND
																r.id=$roundID
						LEFT JOIN #__joomleague_project_team AS pt1 ON pt1.id=m.projectteam1_id
						LEFT JOIN #__joomleague_project_team AS pt2 ON pt2.id=m.projectteam2_id
						LEFT JOIN #__joomleague_team AS t1 ON t1.id=pt1.team_id
						LEFT JOIN #__joomleague_team AS t2 ON t2.id=pt2.team_id
						LEFT JOIN #__joomleague_club AS c1 ON c1.id=t1.club_id
						LEFT JOIN #__joomleague_club AS c2 ON c2.id=t2.club_id
						WHERE (m.cancel IS NULL OR m.cancel = 0)
						ORDER BY m.match_date, m.id ASC";
		$this->_db->setQuery( $query );
		//echo($this->_db->getQuery( ));
		$results = $this->_db->loadObjectList();
		return $results;
	}

	function createFromMatchdayList($project_id)
	{
		$from_matchday=array();
		$from_matchday[]= JHTML::_('select.option','0',JText::_('JL_RANKING_FROM_MATCHDAY'));
		$from_matchday=array_merge($from_matchday,$this->getRoundNames($project_id));
		return $from_matchday;
	}

	function createToMatchdayList($project_id)
	{
		$to_matchday=array();
		$to_matchday[]=JHTML::_('select.option','0',JText::_('JL_RANKING_TO_MATCHDAY'));
		$to_matchday=array_merge($to_matchday,$this->getRoundNames($project_id));
		return $to_matchday;
	}
	


}
?>