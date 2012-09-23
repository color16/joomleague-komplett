<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

require_once( JPATH_COMPONENT.DS . 'helpers' . DS . 'ranking.php' );
require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

class JoomleagueModelTeamInfo extends JoomleagueModelProject
{
	var $project = null;
	var $projectid = 0;
	var $teamid = 0;
	var $team = null;
	var $club = null;

	function __construct( )
	{
		$this->projectid = JRequest::getInt( "p", 0 );
		$this->teamid = JRequest::getInt( "tid", 0 );

		parent::__construct( );
	}

  function getTrainingdata()
	{
  //$projectteam =& $this->getprojectteam();
  $query = "SELECT td.* FROM #__joomleague_team_trainingdata as td
  inner join #__joomleague_project_team as pt
  on td.project_team_id = pt.id 
  WHERE td.project_id = ".$this->_db->Quote($this->projectid)." 
  and pt.team_id =  ".$this->_db->Quote($this->teamid)." ORDER BY td.dayofweek ASC ";
		//echo $query;
		$this->_db->setQuery($query);
		if (!$result = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $result;
          
  }
  
	/**
	 * get team info
	 * @return object
	 */
	function getTeamByProject()
	{
		if (is_null($this->team))
		{
			if ($this->teamid > 0)
			{
				$query = ' SELECT t.*,t.name AS tname, t.website AS team_website, pt.*, pt.notes AS notes, pt.info AS info, 
							t.extended AS teamextended, t.picture AS team_picture, pt.picture AS projectteam_picture, c.*,
							CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS slug 
							FROM #__joomleague_team t 
							LEFT JOIN #__joomleague_club c ON t.club_id=c.id
							INNER JOIN #__joomleague_project_team pt ON pt.team_id = t.id 
							WHERE pt.project_id = '. $this->_db->Quote($this->projectid) .'
							AND pt.team_id = '. $this->_db->Quote($this->teamid);

				$this->_db->setQuery($query);
				$this->team  = $this->_db->loadObject();
			}
		}
		return $this->team;
	}

	/**
	 * get club info
	 * @return object
	 */
	function getClub()
	{
		if ( is_null( $this->club ) )
		{
			$team = &$this->getTeamByProject();
			if ( $team->club_id > 0 )
			{
				$query = ' SELECT *, '
				       . ' CASE WHEN CHAR_LENGTH( alias ) THEN CONCAT_WS( \':\', id, alias ) ELSE id END AS slug '
				       . ' FROM #__joomleague_club WHERE id = '. $this->_db->Quote($team->club_id);
				$this->_db->setQuery($query);
				$this->club  = $this->_db->loadObject();
			}
		}
		return $this->club;
	}

	/**
	 * get history of team in differents projects
	 * @param object config
	 * @return array
	 */
	function getSeasons( $config )
	{
	    $seasons = array();
	    if ( $config['ordering_teams_seasons'] == "1")
	    {
	    	$season_ordering = "DESC";
	    }
	    else
	    {
	    	$season_ordering = "ASC";
	    }

	    // now get all Leagues and Seasons which joined the team so far
	    $query = ' SELECT pt.id as ptid, pt.team_id, pt.picture, pt.info, pt.project_id AS projectid, '
				. ' p.name as projectname, pt.division_id, '
				. ' s.name as season, '
				. ' l.name as league, t.extended as teamextended, '
				. ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS project_slug, '
				. ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS team_slug, '
				. ' CASE WHEN CHAR_LENGTH( d.alias ) THEN CONCAT_WS( \':\', d.id, d.alias ) ELSE d.id END AS division_slug, '
				. ' d.name AS division_name, '
				. ' d.shortname AS division_short_name '
				. ' FROM #__joomleague_project_team AS pt '
				. ' INNER JOIN #__joomleague_team AS t ON t.id = pt.team_id '
				. ' LEFT JOIN #__joomleague_division AS d ON d.id = pt.division_id '
				. ' INNER JOIN #__joomleague_project AS p ON p.id = pt.project_id '
				. ' INNER JOIN #__joomleague_season AS s ON s.id = p.season_id '
				. ' INNER JOIN #__joomleague_league AS l ON l.id = p.league_id '
				. ' WHERE pt.team_id = '.(int)$this->teamid
				. ' AND p.published = 1 '
				. ' ORDER BY s.ordering '.$season_ordering;

	    $this->_db->setQuery( $query );
	    $seasons = $this->_db->loadObjectList();

	    foreach ($seasons as $k => $season)
	    {
	    	$ranking = $this->getTeamRanking($season->projectid, $season->division_id);

	    	$seasons[$k]->rank       = $ranking['rank'];
	    	$seasons[$k]->leaguename = $this->getLeague($season->projectid);
	    	$seasons[$k]->games      = $ranking['games'];
	    	$seasons[$k]->points     = $ranking['points'];
	    	$seasons[$k]->series     = $ranking['series'];
	    	$seasons[$k]->goals      = $ranking['goals'];
	    	$seasons[$k]->playercnt  = $this->getPlayerCount($season->projectid, $this->teamid);
	    }
    	return $seasons;
	}

	/**
	 * get ranking of current team in a project
	 * @param int projectid
	 * @param int division_id
	 * @return array
	 */
	function getTeamRanking($projectid, $division_id)
	{
		$rank = array();
		$model = &JLGModel::getInstance('Project', 'JoomleagueModel');
		$model->setProjectID($projectid);
		$project = $model->getProject();
		$tableconfig = $model->getTemplateConfig( "ranking" );
		$ranking = &JLGRanking::getInstance($project);
		$ranking->setProjectId( $project->id );
		$this->ranking = $ranking->getRanking(
								0,
								$model->getCurrentRound(),
								$division_id);
		foreach ($this->ranking as $ptid => $value)
		{
			if ($value->getTeamId() == $this->teamid)
			{
				$rank['rank']   = $value->rank;
				$rank['games']  = $value->cnt_matches;
				$rank['points'] = $value->getPoints();
				$rank['series'] = $value->cnt_won . "/" . $value->cnt_draw . "/" . $value->cnt_lost;
				$rank['goals']  = $value->sum_team1_result . ":" . $value->sum_team2_result;
				break;
			}
		}
		return $rank;
	}

	/**
	 * gets name of league associated to project
	 * @param int $projectid
	 * @return string
	 */
	function getLeague($projectid)
	{
		$query = 'SELECT l.name AS league FROM #__joomleague_project AS p, #__joomleague_league AS l WHERE p.id=' . $projectid . ' AND l.id=p.league_id ';
	    $this->_db->setQuery($query, 0, 1);
    	$league = $this->_db->loadResult();
		return $league;
	}

	/**
	 * Get total number of players assigned to a team
	 * @param int projectid
	 * @param int teamid
	 * @return int
	 */
	function getPlayerCount($projectid, $teamid)
	{
		$player = array();
		$query = " SELECT COUNT(*) AS playercnt "
		       . " FROM #__joomleague_person AS ps "
		       . " INNER JOIN #__joomleague_team_player AS tp ON tp.person_id = ps.id "
		       . " INNER JOIN #__joomleague_project_team AS pt ON tp.projectteam_id = pt.id "
		       . " WHERE pt.project_id=" . $projectid
		       . " AND pt.team_id=" . $teamid
		       . " AND tp.published = 1 " 
		       . " AND ps.published = 1 ";
		       $this->_db->setQuery($query);
		$player = $this->_db->loadResult();
		return $player;
	}
}
?>