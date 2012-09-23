<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

class JoomleagueModelRoster extends JoomleagueModelProject
{
	var $projectid=0;
	var $projectteamid=0;
	var $projectteam=null;
	var $team=null;

	/**
	 * caching for team in out stats
	 * @var array
	 */
	var $_teaminout=null;

	/**
	 * caching players
	 * @var array
	 */
	var $_players=null;

	function __construct()
	{
		parent::__construct();

		$this->projectid=JRequest::getInt('p',0);
		$this->teamid=JRequest::getInt('tid',0);
		$this->projectteamid=JRequest::getInt('ttid',0);
		$this->getProjectTeam();
	}

  function getTrainingdata()
	{
  $projectteam =& $this->getprojectteam();
  $query = "SELECT * FROM #__joomleague_team_trainingdata WHERE project_team_id=".$this->_db->Quote($projectteam->id)." ORDER BY dayofweek ASC ";
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
	 * returns project team info
	 * @return object
	 */
	function getProjectTeam()
	{
		if (is_null($this->projectteam))
		{
			if ($this->projectteamid)
			{
				$query='	SELECT	pt.*
							FROM #__joomleague_project_team AS pt
							WHERE pt.id='.$this->_db->Quote($this->projectteamid);
			}
			else
			{
				if (!$this->teamid)
				{
					$this->setError(JText::_('Missing team id'));
					return false;
				}
				if (!$this->projectid)
				{
					$this->setError(JText::_('Missing project id'));
					return false;
				}
				$query='	SELECT	pt.*
							FROM #__joomleague_project_team AS pt
							WHERE pt.team_id='.$this->_db->Quote($this->teamid).' 
							  AND pt.project_id='.$this->_db->Quote($this->projectid);
			}
			$this->_db->setQuery($query);
			$this->projectteam=$this->_db->loadObject();
			if ($this->projectteam)
			{
				$this->projectid=$this->projectteam->project_id; // if only ttid was set
				$this->teamid=$this->projectteam->team_id; // if only ttid was set
			}
		}
		return $this->projectteam;
	}

	function getTeam()
	{
		if (is_null($this->team))
		{
			if (!$this->teamid)
			{
				$this->setError(JText::_('Missing team id'));
				return false;
			}
			if (!$this->projectid)
			{
				$this->setError(JText::_('Missing project id'));
				return false;
			}
			$query='	SELECT	t.*,
								CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\',t.id,t.alias) ELSE t.id END AS slug
						FROM #__joomleague_team AS t
						WHERE t.id='.$this->_db->Quote($this->teamid);
			$this->_db->setQuery($query);
			$this->team=$this->_db->loadObject();
		}
		return $this->team;
	}

	/**
	 * return team players by positions
	 * @return array
	 */
	function getTeamPlayers()
	{
		$projectteam =& $this->getprojectteam();
		if (empty($this->_players))
		{
			$query='	SELECT	pr.firstname, 
								pr.nickname,
								pr.lastname,
								pr.country,
								pr.birthday,
								pr.deathday,
								tp.id AS playerid,
								pr.id AS pid,
								pr.picture AS ppic,
								tp.jerseynumber AS position_number,
								tp.notes AS description,
								tp.injury AS injury,
								tp.suspension AS suspension,
								pt.team_id,
								tp.away AS away,tp.picture,
								pos.name AS position,
								ppos.position_id,
								ppos.id as pposid,
								CASE WHEN CHAR_LENGTH(pr.alias) THEN CONCAT_WS(\':\',pr.id,pr.alias) ELSE pr.id END AS slug
						FROM #__joomleague_team_player tp
						INNER JOIN #__joomleague_project_team AS pt ON pt.id=tp.projectteam_id
						INNER JOIN #__joomleague_person AS pr ON tp.person_id=pr.id
						INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=tp.project_position_id
						INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
						WHERE tp.projectteam_id='.$this->_db->Quote($projectteam->id).'
						AND pr.published = 1
						AND tp.published = 1
						ORDER BY pos.ordering, ppos.position_id, tp.jerseynumber, pr.lastname, pr.firstname';
			$this->_db->setQuery($query);
			$this->_players=$this->_db->loadObjectList();
		}
		$bypos=array();
		foreach ($this->_players as $player)
		{
			if (isset($bypos[$player->position_id]))
			{
				$bypos[$player->position_id][]=$player;
			}
			else
			{
				$bypos[$player->position_id]= array($player);
			}
		}
		return $bypos;
	}

	function getStaffList()
	{
		$projectteam =& $this->getprojectteam();
		$query='	SELECT	pr.firstname, 
							pr.nickname,
							pr.lastname,
							pr.country,
							pr.birthday,
							pr.deathday,
							ts.id AS ptid,
							ppos.position_id,
							ppos.id AS pposid,
							pr.id AS pid,
							pr.picture AS ppic,
							pos.name AS position,
							ts.picture,
							ts.notes AS description,
							ts.injury AS injury,
							ts.suspension AS suspension,
							ts.away AS away,
							pos.parent_id,
							posparent.name AS parentname,				
							CASE WHEN CHAR_LENGTH(pr.alias) THEN CONCAT_WS(\':\',pr.id,pr.alias) ELSE pr.id END AS slug
					FROM #__joomleague_team_staff ts
					INNER JOIN #__joomleague_person AS pr ON ts.person_id=pr.id
					INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=ts.project_position_id
					INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
					LEFT JOIN #__joomleague_position AS posparent ON pos.parent_id=posparent.id
					WHERE ts.projectteam_id='.$this->_db->Quote($projectteam->id).'
					  AND pr.published = 1
					  AND ts.published = 1
					ORDER BY pos.parent_id, pos.ordering';
		$this->_db->setQuery($query);
		$stafflist=$this->_db->loadObjectList();
		return $stafflist;
	}

	function getPositionEventTypes($positionId=0)
	{
		$result=array();
		$query='	SELECT	pet.*,
							ppos.id AS pposid,
							ppos.position_id,
							et.name AS name,
							et.icon AS icon
					FROM #__joomleague_position_eventtype AS pet
					INNER JOIN #__joomleague_eventtype AS et ON et.id=pet.eventtype_id
					INNER JOIN #__joomleague_project_position AS ppos ON ppos.position_id=pet.position_id
					WHERE ppos.project_id='.$this->projectid.' AND et.published=1 ';
		if ($positionId > 0)
		{
			$query .= ' AND pet.position_id='.(int)$positionId;
		}
		$query .= ' ORDER BY pet.ordering, et.ordering';
		$this->_db->setQuery($query);
		$result=$this->_db->loadObjectList();
		if ($result)
		{
			if ($positionId)
			{
				return $result;
			}
			else
			{
				$posEvents=array();
				foreach ($result as $r)
				{
					$posEvents[$r->position_id][]=$r;
				}
				return ($posEvents);
			}
		}
		return array();
	}

	function getPlayerEventStats()
	{
		$playerstats=array();
		$rows =& $this->getTeamPlayers();
		if (!empty($rows))
		{
			$positioneventtypes=$this->getPositionEventTypes();
			//init
			foreach ($rows as $position => $players)
			{
				foreach ($players as $player)
				{
					$playerstats[$player->pid]=array();
				}
			}
			foreach ($positioneventtypes as $position => $eventtypes)
			{
				foreach ($eventtypes as $eventtype)
				{
					$teamstats=$this->getTeamEventStat($eventtype->eventtype_id);
					if(isset($rows[$position])) {
						foreach ($rows[$position] as $player)
						{
							$playerstats[$player->pid][$eventtype->eventtype_id]=(isset($teamstats[$player->pid]) ? $teamstats[$player->pid]->total : 0);
						}
					}
				}
			}
		}
		return $playerstats;
	}

	function getTeamEventStat($eventtype_id)
	{
		$projectteam =& $this->getprojectteam();
		$query='	SELECT	SUM(me.event_sum) as total,
							tp.person_id
					FROM #__joomleague_match_event AS me
					INNER JOIN #__joomleague_team_player AS tp ON me.teamplayer_id=tp.id
					WHERE me.event_type_id = '.$this->_db->Quote($eventtype_id).' 
					  AND tp.projectteam_id = '.$this->_db->Quote($projectteam->id).'
					GROUP BY tp.person_id';
		$this->_db->setQuery($query);
		$result=$this->_db->loadObjectList('person_id');
		return $result;
	}

	function getInOutStats($player_id)
	{
		$teaminout=&$this->_getTeamInOutStats();
		if (isset($teaminout[$player_id])) {
			return $teaminout[$player_id];
		}
		else {
			return null;
		}
	}

	function _getTeamInOutStats()
	{
		$projectteam=&$this->getprojectteam();
		if (empty($this->_teaminout))
		{
			$projectteam_id = $this->_db->Quote($projectteam->id);

			// Split the problem in two;
			// 1. Get the number of matches played per teamplayer of the projectteam.
			//    This is derived from three tables: match_player, match_statistic and match_event
			// 2. Get the in/out stats.
			//    This is derived from the match_player table only.

			// Sub 1: get number of matches played by teamplayers of the projectteam
			$common_query_part 	= ' INNER JOIN #__joomleague_match AS m ON m.id = md.match_id'
								. ' INNER JOIN #__joomleague_team_player AS tp ON tp.id = md.teamplayer_id'
								. ' INNER JOIN #__joomleague_project_team AS pt ON pt.id=tp.projectteam_id'
								. ' WHERE pt.id='.$projectteam_id;

			// Use md (stands for match detail, where the detail can be a match_player, match_statistic or match_event)
			// All of them have a match_id and teamplayer_id.
			$query_mp = ' SELECT DISTINCT m.id AS mid, tp.id AS tpid'
					  . ' FROM #__joomleague_match_player AS md'
					  . $common_query_part
					  . ' AND (md.came_in = 0 || md.came_in = 1)';

			$query_ms = ' SELECT DISTINCT m.id AS mid, tp.id AS tpid'
					  . ' FROM #__joomleague_match_statistic AS md'
					  . $common_query_part;

			$query_me = ' SELECT DISTINCT m.id AS mid, tp.id AS tpid'
					  . ' FROM #__joomleague_match_event AS md'
					  . $common_query_part;

			$query	= ' SELECT pse.person_id, '
					. '        COUNT(pse.mid) AS played,'
					. '        0 AS started,'
					. '        0 AS sub_in,'
					. '        0 AS sub_out'
					. ' FROM'
					. ' ('
					. '     SELECT DISTINCT m.id as mid, tp.id as tpid, tp.person_id'
					. '     FROM #__joomleague_match AS m'
					. '     INNER JOIN #__joomleague_round r ON m.round_id=r.id '
					. '     INNER JOIN #__joomleague_project AS p ON p.id=r.project_id '
					. '     LEFT  JOIN ('.$query_mp.') AS mp ON mp.mid = m.id'
					. '     LEFT  JOIN ('.$query_ms.') AS ms ON ms.mid = m.id'
					. '     LEFT  JOIN ('.$query_me.') AS me ON me.mid = m.id'
					. '     INNER JOIN #__joomleague_team_player AS tp'
					. '             ON (tp.id = mp.tpid OR tp.id = ms.tpid OR tp.id = me.tpid)'
					. '     WHERE tp.projectteam_id = '.$projectteam_id
					. '       AND m.published = 1 '
					. '       AND p.published = 1 '
					. ' ) AS pse'
					. ' GROUP BY pse.tpid';
			$this->_db->setQuery($query);
			$this->_teaminout = $this->_db->loadObjectList('person_id');

			// Sub 2: get the in/out stats
			$query	= ' SELECT tp1.id AS tp_id1, '
					. ' tp1.person_id AS person_id1, '
					. ' tp2.id AS tp_id2, '
					. ' tp2.person_id AS person_id2,'
					. ' m.id AS mid, '
					. ' mp.came_in, mp.out, mp.in_for'
					. ' FROM #__joomleague_match AS m'
					. ' INNER JOIN #__joomleague_round r ON m.round_id=r.id '
					. ' INNER JOIN #__joomleague_project AS p ON p.id=r.project_id '
					. ' INNER JOIN #__joomleague_match_player AS mp ON mp.match_id=m.id '
					. ' INNER JOIN #__joomleague_team_player AS tp1 ON tp1.id=mp.teamplayer_id'
					. ' LEFT JOIN #__joomleague_team_player AS tp2 ON tp2.id = mp.in_for'
					. ' WHERE tp1.projectteam_id = '.$this->_db->Quote($projectteam->id)
					. ' AND m.published = 1 '
					. ' AND p.published = 1 ';
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			foreach ($rows AS $row)
			{
				$this->_teaminout[$row->person_id1]->started += ($row->came_in == 0);
				$this->_teaminout[$row->person_id1]->sub_in  += ($row->came_in == 1);
				$this->_teaminout[$row->person_id1]->sub_out += ($row->out == 1);

				// Handle the second player tp2 (only applicable when one goes out AND another comes in; tp2 is the player that goes out)
				if (isset($row->person_id2))
				{
					$this->_teaminout[$row->person_id2]->sub_out++;
				}
			}
		}

		return $this->_teaminout;
	}

	/**
	 * return the injury,suspension,away data from a player
	 *
	 * @param int $round_id
	 * @param int $player_id
	 *
	 * @access public
	 * @since  1.5.0a
	 *
	 * @return object
	 */
	function getTeamPlayer($round_id,$player_id)
	{
		$query="	SELECT	tp.injury AS injury,
							tp.suspension AS suspension,
							tp.away AS away, pt.picture,
							ppos.id As pposid,
							pos.id AS position_id
					FROM #__joomleague_team_player AS tp
					INNER JOIN #__joomleague_person AS pr ON tp.person_id=pr.id
					INNER JOIN #__joomleague_project_team AS pt ON pt.id=tp.projectteam_id
					INNER JOIN #__joomleague_round AS r ON r.project_id=pt.project_id
					INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=tp.project_position_id
					INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
					WHERE r.id=".$round_id." 
					  AND tp.id=".$player_id." 
					  AND pr.published = '1'
					  AND tp.published = '1'
					  ";
		$this->_db->setQuery($query);
		$rows=$this->_db->loadObjectList();
		return $rows;
	}

	function getRosterStats()
	{
		$stats =& $this->getProjectStats();
		$projectteam =& $this->getprojectteam();
		$result=array();
		foreach ($stats as $pos => $pos_stats)
		{
			foreach ($pos_stats as $k => $stat)
			{
				$result[$pos][$stat->id]=$stat->getRosterStats($projectteam->team_id,$projectteam->project_id, $pos);
			}
		}
		return $result;
	}

}
?>