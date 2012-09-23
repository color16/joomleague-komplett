<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// Component Helper
jimport('joomla.application.component.helper');

/**
 *
 */
class JoomleagueHelperRoute
{

  function getKunenaForumRoute( $catid )
	{
		$params = array(	"option" => "com_kunena",
					"func" => "showcat",
          "view" => "",
					"catid" => $catid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}
  
	function getTeamInfoRoute( $projectid, $teamid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "teaminfo",
					"p" => $projectid,
					"tid" => $teamid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}
	
	function getRivalsRoute( $projectid, $teamid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "rivals",
					"p" => $projectid,
					"tid" => $teamid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}	

	function getClubInfoRoute( $projectid, $clubid, $task=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "clubinfo",
					"p" => $projectid,
					"cid" => $clubid );

		if ( ! is_null( $task ) ) { $params["layout"] = $task; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( "index.php?" . $query, false );

		return $link;
	}

	function getClubPlanRoute( $projectid, $clubid, $task=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "clubplan",
					"p" => $projectid,
					"cid" => $clubid );

		if ( ! is_null( $task ) ) { $params["task"] = $task; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( "index.php?" . $query, false );

		return $link;
	}

	function getPlaygroundRoute( $projectid, $plagroundid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "playground",
					"p" => $projectid,
					"pgid" => $plagroundid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	/**
	 * 
	 * @param int $projectid
	 * @param int $round
	 * @param int $from
	 * @param int $to
	 * @param int $type
	 */
	function getRankingRoute( $projectid, $round=null, $from=null, $to=null, $type=0, $division=0 )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "ranking",
					"p" => $projectid );

		if ( ! is_null( $type ) ) { $params["type"] = $type; }
		if ( ! is_null( $round ) ) { $params["r"] = $round; }
		if ( ! is_null( $from) ) { $params["from"] = $from; }
		if ( ! is_null( $to ) ) { $params["to"] = $to; }
		if ( ! is_null( $division) ) { $params["division"] = $division; }
		
		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getResultsRoute($projectid, $roundid=null, $divisionid=0, $mode=0, $order=0, $layout=null)
	{
		$params = array(	'option' => 'com_joomleague',
					'view' => 'results',
					'p' => $projectid );
		if ( !is_null( $roundid ) ) { 
			$params['r']=$roundid; 
		}
		if ( !is_null( $divisionid ) ) {
			$params['division']=$divisionid;
		}
		if ( !is_null( $mode) ) {
			$params['mode']=$mode;
		}
		if ( !is_null( $order) ) {
			$params['order']=$order;
		}
		if ( !is_null( $layout) ) {
			$params['layout']=$layout;
		}
		
		$query = JoomleagueHelperRoute::buildQuery($params);
		$link = JRoute::_('index.php?' . $query ,false);

		return $link;
	}

	function getMatrixRoute( $projectid, $division=0, $round=0 )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "matrix",
					"p" => $projectid );

		$params["division"] = $division;
		$params["r"] = $round;
		
		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getResultsRankingRoute( $projectid, $round=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "resultsranking",
					"p" => $projectid );

		if ( ! is_null( $round ) ) { $params["r"] = $round; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getResultsMatrixRoute( $projectid, $round=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "resultsmatrix",
					"p" => $projectid );

		if ( ! is_null( $round ) ) { $params["r"] = $round; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getRankingMatrixRoute( $projectid, $round=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "rankingmatrix",
					"p" => $projectid );

		if ( ! is_null( $round ) ) { $params["r"] = $round; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getResultsRankingMatrixRoute( $projectid, $round=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "resultsrankingmatrix",
					"p" => $projectid );

		if ( ! is_null( $round ) ) { $params["r"] = $round; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getTeamPlanRoute( $projectid, $teamid, $division=0, $mode=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "teamplan",
					"p" => $projectid,
					"tid" => $teamid,
					"division" => $division );

		if ( ! is_null( $mode ) ) { $params["mode"] = $mode; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( "index.php?" . $query, false );

		return $link;
	}

	function getMatchReportRoute( $projectid, $matchid = null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "matchreport",
					"p" => $projectid );

		if ( ! is_null( $matchid ) ) { $params["mid"] = $matchid; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	/**
	 * return links to a team player
	 * @param int projectid
	 * @param int teamid
	 * @param int personid
	 * @return url
	 */
	function getPlayerRoute( $projectid, $teamid, $personid, $layout=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "player",
					"p" => $projectid,
					"tid" => $teamid,
					"pid" => $personid );

		if ( ! is_null( $layout ) ) { $params["layout"] = $layout; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( "index.php?" . $query, false );

		return $link;
	}

	/**
	 * return links to a team staff
	 * @param int projectid
	 * @param int teamid
	 * @param int personid
	 * @return url
	 */
	function getStaffRoute( $projectid, $teamid, $personid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "staff",
					"p" => $projectid,
					"tid" => $teamid,
					"pid" => $personid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	/**
	 * returns url to a person
	 * @param int project id
	 * @param int person id
	 * @param int Type: 1 for player, 2 for staff, 3 for referee
	 * @deprecated since 1.5
	 * @return url
	 */
	function getPersonRoute( $projectid, $personid, $showType )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "person",
					"p" => $projectid,
					"pid" => $personid,
					"pt" => $showType );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getPlayersRoute( $projectid, $teamid, $task=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "roster",
					"p" => $projectid,
					"tid" => $teamid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getDivisionsRoute( $projectid, $divisionid, $task=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "treeone",
					"p" => $projectid,
					"did" => $divisionid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getFavPlayersRoute( $projectid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "players",
					"task" => "favplayers",
					"p" => $projectid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getRefereeRoute( $projectid, $personid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "referee",
					"p" => $projectid,
					"pid" => $personid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getRefereesRoute( $projectid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "referees",
					"p" => $projectid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getEventsRankingRoute( $projectid, $divisionid=0, $teamid=0, $eventid=0, $matchid=0)
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "eventsranking",
					"p" => $projectid );

		$params["division"] = $divisionid;
		$params["tid"] = $teamid;
		$params["evid"] = $eventid;
		$params["mid"] = $matchid;
		
		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getCurveRoute($projectid, $teamid1=0, $teamid2=0, $division=0)
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "curve",
					"p" => $projectid );

		$params["tid1"] = $teamid1;
		$params["tid2"] = $teamid2;
		$params["division"] = $division;

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getStatsChartDataRoute( $projectid, $divisionid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "stats",
					"layout" => "chartdata",
					"p" => $projectid );

		if ($divisionid != 0) {  $params["division"] = $divisionid; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getTeamStatsChartDataRoute( $projectid, $teamid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "teamstats",
					"layout" => "chartdata",
					"p" => $projectid,
					"tid" => $teamid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getStatsRoute( $projectid, $divisionid = null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "stats",
					"p" => $projectid );

		if ( isset( $divisionid ) ) { $params["division"] = $divisionid; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getBracketsRoute( $projectid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "treetonode",
					"p" => $projectid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getStatsRankingRoute( $projectid, $divisionid = null, $teamid = null, $statid = 0, $order = null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "statsranking",
					"p" => $projectid );

		if ( isset( $divisionid ) ) { $params["division"] = $divisionid; }
		if ( isset( $teamid ) ) { $params["tid"] = $teamid; }
		if ($statid) { $params['sid'] = $statid; }
		if (strcasecmp($order, 'asc') === 0 || strcasecmp($order, 'desc') === 0) { $params['order'] = strtolower($order); }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getClubsRoute( $projectid, $divisionid = null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "clubs",
					"p" => $projectid );

		if ( isset( $divisionid ) ) { $params["division"] = $divisionid; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getTeamsRoute( $projectid, $divisionid = null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "teams",
					"p" => $projectid );

		if ( isset( $divisionid ) ) { $params["division"] = $divisionid; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getTeamStatsRoute( $projectid, $teamid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "teamstats",
					"p" => $projectid,
					"tid" => $teamid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( "index.php?" . $query, false );

		return $link;
	}

	/* old one
	 function getTeamStaffRoute( $projectid, $playerid)
	 {
		$query = JoomleagueHelperRoute::buildQuery(  array(  "option" => "com_joomleague",
		"view" => "teamstaff",
		"p" => $projectid,
		"pid" => $playerid) );

		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
		}
		*/

	function getTeamStaffRoute( $projectid, $playerid, $showType )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "person",
					"p" => $projectid,
					"pid" => $playerid,
					"pt" => $showType );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getNextMatchRoute( $projectid, $matchid )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "nextmatch",
					"p" => $projectid,
					"mid" => $matchid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getEditEventsRoute( $projectid, $matchid, $task = null, $team = null, $projectTeam = null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "editevents",
					//"no_html" => 1,
					"p" => $projectid,
					"mid" => $matchid );

		if ( ! is_null( $task ) ) { $params['layout'] = $task; }
		if ( ! is_null( $team ) ) { $params['team'] = $team; }
		if ( ! is_null( $projectTeam ) ) { $params['pteam'] = $projectTeam; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query . '&tmpl=component', false );

		return $link;
	}

	function getEditEventsRouteNew( $projectid, $matchid, $team1 = null, $projectTeam1 = null, $team2 = null, $projectTeam2 = null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "editevents",
					"p" => $projectid,
					"mid" => $matchid );

		if ( ! is_null( $team1 ) ) { $params['t1'] = $team1; }
		if ( ! is_null( $team2 ) ) { $params['t2'] = $team2; }
		if ( ! is_null( $projectTeam1 ) ) { $params['pt1'] = $projectTeam1; }
		if ( ! is_null( $projectTeam2 ) ) { $params['pt2'] = $projectTeam2; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query . '&tmpl=component', false );

		return $link;
	}

	function getEditMatchRoute($projectid, $matchid)
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "editmatch",
					"p" => $projectid,
					"mid" => $matchid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query . '&tmpl=component', false );

		return $link;
	}

	function getContactRoute( $contactid )
	{
		/* Old Route to JOOMLA built in contact id
		 $query = JoomleagueHelperRoute::buildQuery(
		 array(
		 "option" => "com_contact",
		 "task" => "view",
		 "contact_id" => $contactid ) );
		 */
		// New Route to JOOMLA built in contact id
		$params = array(	"option" => "com_contact",
					"view" => "contact",
					"id" => $contactid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getUserProfileRouteCBE( $u_id, $p_id, $pl_id )
	{
		// Old Route to Community Builder User Page with support for CBE-JoomLeague Tab
		// index.php?option=com_cbe&view=userProfile&user=JOOMLA_USER_ID&jlp=PROJECT_ID&jlpid=JOOMLEAGUE_PLAYER_ID
		$params = array(	"option" => "com_cbe",
					"view" => "userProfile",
					"user" => $u_id,
					"jlp" => $p_id,
					"jlpid" => $pl_id );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getUserProfileRoute( $userid )
	{
		$params = array(	"option" => "com_comprofiler",
					"task" => "userProfile",
					"user" => $userid );

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( 'index.php?' . $query, false );

		return $link;
	}

	function getIcalRoute( $projectid, $teamid=null, $pgid=null )
	{
		$params = array(	"option" => "com_joomleague",
					"view" => "ical",
					"p" => $projectid );

		if ( !is_null( $pgid ) ) { $params["pgid"] = $pgid; }
		if ( !is_null( $teamid ) ) { $params["teamid"] = $teamid; }

		$query = JoomleagueHelperRoute::buildQuery( $params );
		$link = JRoute::_( "index.php?" . $query, false );

		return $link;
	}
	
	function buildQuery($parts)
	{
		if ($item = JoomleagueHelperRoute::_findItem($parts))
		{
			$parts['Itemid'] = $item->id;
		}
		else {
			$params = JComponentHelper::getParams('com_joomleague');
			if ($params->get('default_itemid')) {
				$parts['Itemid'] = intval($params->get('default_itemid'));				
			}
		}

		return JURI::buildQuery( $parts );
	}

	/**
	 * Determines the Itemid
	 *
	 * searches if a menuitem for this item exists
	 * if not the first match will be returned
	 *
	 * @param array The id and view
	 * @since 0.9
	 *
	 * @return int Itemid
	 */
	function _findItem($query)
	{
		$component =& JComponentHelper::getComponent('com_joomleague');
		$menus	= & JSite::getMenu();
		$items	= $menus->getItems('componentid', $component->id);
		$user 	= & JFactory::getUser();
		$access = (int)$user->get('aid');

		if ($items) {
			foreach($items as $item)
			{
				if ((@$item->query['view'] == $query['view']) && ($item->published == 1) && ($item->access <= $access)) {

					switch ($query['view'])
					{
						case 'teaminfo':
						case 'roster':
						case 'teamplan':
						case 'teamstats':
							if ((int)@$item->query['p'] == (int) $query['p'] && (int)@$item->query['tid'] == (int) $query['tid']) {
								return $item;
							}
							break;
						case 'clubinfo':
						case 'clubplan':
							if ((int)@$item->query['p'] == (int) $query['p'] && (int)@$item->query['cid'] == (int) $query['cid']) {
								return $item;
							}
							break;
						case 'playground':
							if ((int)@$item->query['p'] == (int) $query['p'] && (int)@$item->query['pgid'] == (int) $query['pgid']) {
								return $item;
							}
							break;
						case 'ranking':
						case 'results':
						case 'resultsranking':
						case 'matrix':
						case 'resultsmatrix':
						case 'stats':
							if ((int)@$item->query['p'] == (int) $query['p']) {
								return $item;
							}
							break;
						case 'statsranking':
							if ((int)@$item->query['p'] == (int) $query['p']) {
								return $item;
							}
							break;
						case 'player':
						case 'staff':
							if (    (int)@$item->query['p'] == (int) $query['p']
							&& (int)@$item->query['tid'] == (int) $query['tid']
							&& (int)@$item->query['pid'] == (int) $query['pid']) {
								return $item;
							}
							break;
						case 'referee':
							if (    (int)@$item->query['p'] == (int) $query['p']
							&& (int)@$item->query['pid'] == (int) $query['pid']) {
								return $item;
							}
							break;
						case 'tree':
							if ((int)@$item->query['p'] == (int) $query['p'] && (int)@$item->query['did'] == (int) $query['did']) {
								return $item;
							}
							break;
					}
				}
			}
		}

		return false;
	}
}
?>
