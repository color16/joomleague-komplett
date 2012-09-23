<?php
/**
 * @copyright   Copyright (C) 2006-2009 JoomLeague.net. All rights reserved.
 * @license	 GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.array');
jimport('joomla.utilities.arrayhelper') ;
jimport( 'joomla.utilities.utility' );

/**
 * Joomleague Component JLtournemanttree Model
 *
 * @author	Dieter Ploeger
 * @package	JoomLeague
 * @since	1.5.0a
 */
class JoomleagueModeljltournamenttree extends JModel
{
var $projectid = 0;
var $from = 0;
var $to = 0;
var $team_strlen = 0;
var $round = 0;
var $count_tournament_round = 0;
var $menue_itemid = 0;
var $request = array();
var $bracket = array();
var $menue_params = array();
var $exist_result = array();
var $debug_info = false;
var $club_logo = 'media/com_joomleague/placeholders/placeholder_150.png';
var $color_from = '#FFFFFF';
var $color_to = '#0000FF';
var $which_first_round = 'scrollLeft()';
var $font_size = 14;
var $jl_tree_bracket_round_width = 100;
var $jl_tree_bracket_teamb_width = 70;
var $jl_tree_bracket_width = 140;
var $jl_tree_jquery_version = '1.7.1';


function __construct( )
	{
        $menu =   &JSite::getMenu();
		$this->projectid = JRequest::getInt( "p", 0 );
 		$this->from  = JRequest::getInt( 'from', 0 );
 		$this->to	 = JRequest::getInt( 'to', 0 );
 		$this->round = JRequest::getVar( "r");
        $this->request = JRequest::get();
        $this->menue_itemid = JRequest::getInt( "Itemid", 0 );
        
        //$this->color_from = JRequest::getVar( "color_from");
        //$this->color_to = JRequest::getVar( "color_to");
        
        $item = $menu->getItem($this->menue_itemid);
        $this->menue_params = new JParameter($item->params);
        
        if ( $this->menue_params->get('jl_tree_jquery_version') )
        {
        $this->jl_tree_jquery_version = $this->menue_params->get('jl_tree_jquery_version');    
        }
        
        if ( $this->menue_params->get('jl_tree_color_from') )
        {
        $this->color_from = $this->menue_params->get('jl_tree_color_from');    
        }
        
        if ( $this->menue_params->get('jl_tree_color_to') )
        {
        $this->color_to = $this->menue_params->get('jl_tree_color_to');    
        }
        
        
        if ( $this->menue_params->get('jl_tree_font_size') )
        {
        $this->font_size = $this->menue_params->get('jl_tree_font_size');    
        }
        
 		
        if ( $this->menue_params->get('jl_tree_bracket_round_width') )
        {
        $this->jl_tree_bracket_round_width = $this->menue_params->get('jl_tree_bracket_round_width');    
        }
        
        
        if ( $this->menue_params->get('which_first_round') )
        {
        $this->which_first_round = $this->menue_params->get('which_first_round');    
        }
        
        
	$show_debug_info = JComponentHelper::getParams('com_joomleague')->get('show_debug_info',0);
  if ( $show_debug_info )
  {
  $this->debug_info = true;
  
  echo '0.) start request -> <br /><pre>~'.print_r($this->request,true).'~</pre><br />';
  echo '0.) start menue params -> <br /><pre>~'.print_r($this->menue_params,true).'~</pre><br />';
  
  }
  else
  {
  $this->debug_info = false;
  }
 


		parent::__construct( );
	}

function getWhichJQuery()
{
return $this->jl_tree_jquery_version;    
}

function getWhichShowFirstRound()
{
return $this->which_first_round;
}

function getTreeBracketRoundWidth()
{
global $mainframe;
    
//-- Einlesen der Feldnamen
$tableName = '#__joomleague_team';
$tFields = $this->_db->getTableFields($tableName, false);
                    
$this->jl_tree_bracket_round_width = 16 + ( $this->team_strlen * 4 ) + 25 + 100;  
// laenge des zu selektierenden feldes

//echo '0.) ende tabellen spalten -> <br /><pre>~'.print_r($tFields,true).'~</pre><br />';

$fieldName = $this->request['tree_name'];
$fieldtype = $tFields[$tableName][$fieldName]->Type;
  
//$mainframe->enqueueMessage(JText::_('feldtyp -> '.$fieldtype),'Notice');

return $this->jl_tree_bracket_round_width;    
}

function getTreeBracketTeambWidth()
{
$this->jl_tree_bracket_teamb_width = ( 70 * $this->jl_tree_bracket_round_width / 100);
return $this->jl_tree_bracket_teamb_width;
}

function getTreeBracketWidth()
{
//$this->jl_tree_bracket_width =  $this->jl_tree_bracket_teamb_width * 2;
//$this->jl_tree_bracket_width =  ( 40 * $this->jl_tree_bracket_round_width / 100) + $this->jl_tree_bracket_round_width;
//$this->jl_tree_bracket_width = ( $this->count_tournament_round * $this->jl_tree_bracket_round_width ) + 40;
$this->jl_tree_bracket_width = $this->jl_tree_bracket_round_width + 40;

//$this->jl_tree_bracket_width = ( $this->team_strlen * 12 ) + 40;

return $this->jl_tree_bracket_width;
}

function getFontSize()
{
return $this->font_size;    
}

function getColorFrom()
{
return $this->color_from;    
}

function getColorTo()
{
return $this->color_to;    
}

function getTournamentName()
{
$query="SELECT name
		FROM #__joomleague_project
		WHERE id = ".$this->projectid."
        ";

		$this->_db->setQuery($query);
    return $this->_db->loadResult();
		
}

	
function getTournamentRounds()
{
$option='com_joomleague';
$mainframe	=& JFactory::getApplication();
$user = JFactory::getUser();

if ( $this->debug_info )
{
echo '0.) projekt -> <br /><pre>~'.print_r($this->projectid,true).'~</pre><br />';
echo '0.) request -> <br /><pre>~'.print_r($this->request,true).'~</pre><br />';
echo '0.) projekt rounds -> <br /><pre>~'.print_r($this->round,true).'~</pre><br />';
echo '0.) from round -> <br /><pre>~'.print_r($this->from,true).'~</pre><br />';
echo '0.) to round -> <br /><pre>~'.print_r($this->to,true).'~</pre><br />';
}

// wurden die wichtigen einstellungen �bergeben ?
if ( !array_key_exists('tree_name', $this->request) )
{
    $this->request['tree_name'] = 'middle_name';
}
if ( !array_key_exists('tree_logo', $this->request) )
{
    $this->request['tree_logo'] = '1';
}
    
$query_SELECT = "SELECT ro.* ";

$query_FROM = " FROM #__joomleague_round as ro
		  inner join #__joomleague_match as ma
          on ma.round_id = ro.id";
           
$query_WHERE = " WHERE ro.project_id = ".$this->projectid;

// von bis runde gesetzt
if ( array_key_exists('from', $this->request) && array_key_exists('to', $this->request) )
{
$query_WHERE .= " and ( ro.id between ".$this->request['from']." and ".$this->request['to']." )";
}

// nur von runde gesetzt
if ( array_key_exists('from', $this->request) && !array_key_exists('to', $this->request) )
{
$query_WHERE .= " and ( ro.id between ".$this->request['from']." and ".$this->request['from']." )";
}

// nur bis runde gesetzt
if ( !array_key_exists('from', $this->request) && array_key_exists('to', $this->request) )
{
$query_WHERE .= " and ( ro.id between ".$this->request['to']." and ".$this->request['from']." )";
}

// array an runden gesetzt
if ( array_key_exists('r', $this->request) )
{
$temprounds = explode("|",$this->request['r']);
$selectrounds = implode(",",$temprounds);
$query_WHERE .= " and ro.id IN (".$selectrounds.")";
}

// keine runden gesetzt
if ( !array_key_exists('from', $this->request) 
&& !array_key_exists('to', $this->request) 
&& !array_key_exists('r', $this->request) )
{
$query_WHERE .= " and ma.count_result = 0 ";
}


$query_END = " GROUP BY ro.roundcode
          ORDER BY ro.roundcode DESC
          ";

$query = $query_SELECT.$query_FROM.$query_WHERE.$query_END;

$this->_db->setQuery($query);

if ( $this->debug_info )
{
echo 'Runden -> <br /><pre>~'.print_r($this->_db->loadObjectList(),true).'~</pre><br />';
echo 'Runden query-> <br /><pre>~'.print_r($query,true).'~</pre><br />';
}

$this->count_tournament_round = count($this->_db->loadObjectList());		
return $this->_db->loadObjectList();
		

}

function getTournamentBracketRounds($rounds)
{
$temp_rounds = array();

foreach ( $rounds as $round )
{
$temp_rounds[$round->roundcode] = '{roundname: "'.$round->name.'"}';   
}    

ksort($temp_rounds);

return '['.implode(",",$temp_rounds).']';
    
}


function getTournamentMatches($rounds)
{
$option='com_joomleague';
$mainframe	=& JFactory::getApplication();
$user = JFactory::getUser();

$round_matches = array();
$round_matches[1] = 1;
$round_matches[2] = 2;
$round_matches[4] = 4;
$round_matches[8] = 8;
$round_matches[16] = 16;
$round_matches[32] = 32;
$round_matches[64] = 64;
$round_matches[128] = 128;
$round_matches[256] = 256;
$round_matches[512] = 512;
$round_matches[1024] = 1024;
$round_matches[2048] = 2048;

$query_SELECT="
		SELECT	m.id,
		m.round_id,
		m.projectteam1_id,
		m.projectteam2_id,
		m.team1_result,
		m.team2_result,
		t1.".$this->request['tree_name']." as firstname,
		t2.".$this->request['tree_name']." as secondname,
		c1.country as firstcountry,
		c2.country as secondcountry,
        c1.logo_big as firstlogo,
        c2.logo_big as secondlogo
    ";

$query_FROM='
		FROM #__joomleague_match AS m
		INNER JOIN #__joomleague_round AS r ON m.round_id = r.id
		LEFT JOIN #__joomleague_project_team AS tt1 ON m.projectteam1_id = tt1.id
		LEFT JOIN #__joomleague_project_team AS tt2 ON m.projectteam2_id = tt2.id
		LEFT JOIN #__joomleague_team AS t1 ON t1.id = tt1.team_id
		LEFT JOIN #__joomleague_team AS t2 ON t2.id = tt2.team_id
		LEFT JOIN #__joomleague_club AS c1 ON c1.id = t1.club_id
		LEFT JOIN #__joomleague_club AS c2 ON c2.id = t2.club_id
		LEFT JOIN #__joomleague_playground AS playground ON playground.id = m.playground_id';

$query_END		= ' GROUP BY m.id ORDER BY m.match_date ASC,m.match_number';

$matches = array();
$durchlauf = count($rounds);
$zaehler = 1;
$roundcode = '';

foreach ( $rounds as $round )
{
unset($export);

switch ($zaehler)
{
case 1:
$query_WHERE = ' WHERE m.published = 1 
			  AND r.id = '.$round->id.' 
			  AND r.project_id = '.$this->projectid;
		
$query = $query_SELECT.$query_FROM.$query_WHERE.$query_END;
$this->_db->setQuery($query);
$result = $this->_db->loadObjectList();
		
		//$result = $this->_db->loadObjectList('id');
		//$result = $this->_db->loadAssocList('round_id');
		//echo 'round-id -> '.$round->id.'<br /><pre>~'.print_r($result,true).'~</pre><br />';
		//$matches[$round->id] = $result;

if ( $this->debug_info )
{
echo '1.) result -> <br /><pre>~'.print_r($result,true).'~</pre><br />';
}
  
foreach ( $result as $row )
{

if ( $this->debug_info )
{
echo 'bild heim -> '.JURI::base().$row->firstlogo. '<br />';
}

/*
if( !JFile::exists(JPATH_ROOT.'/'.$row->firstlogo) )
{
$row->firstlogo = $this->club_logo;
}
if( !JFile::exists(JPATH_ROOT.'/'.$row->secondlogo) )
{
$row->secondlogo = $this->club_logo;
}
*/

if( !JFile::exists(JURI::base().$row->firstlogo) )
{
$row->firstlogo = JURI::base().$this->club_logo;
}
else
{
$row->firstlogo = JURI::base().$row->firstlogo;
}

if( !JFile::exists(JURI::base().$row->secondlogo) )
{
$row->secondlogo = JURI::base().$this->club_logo;
}
else
{
$row->secondlogo = JURI::base().$row->secondlogo;
}

// pr�fen ob die ergebnisse in der runde schon gesetzt sind
// die runde kann ja auch hin- und r�ckspiel beinhalten
if ( array_key_exists($round->roundcode, $this->bracket) && count($result) > 1 )
{

//echo '1.) this->bracket -> <br /><pre>~'.print_r($this->bracket,true).'~</pre><br />';
//echo '1.) laenge bracket -> <br /><pre>~'.print_r(sizeof($this->bracket[$round->roundcode]),true).'~</pre><br />';

$finished = true;
//foreach ( $this->bracket[$round->roundcode] as $value )
foreach ( $this->bracket[$round->roundcode] as $key => $value )
//for ($a=0; $a < sizeof($this->bracket[$round->roundcode]); $a++  )
{

//$value = $this->bracket[$round->roundcode][$a];

//echo '<b>1 value -> '.$value.'</b><br />'; 

//echo '1.) result bracket value -> <br /><pre>~'.print_r($value,true).'~</pre><br />';
//echo '1.) result bracket value -> <br /><pre>~'.print_r($this->bracket[$round->roundcode][$a],true).'~</pre><br />';
//echo '1.) laenge bracket value -> <br /><pre>~'.print_r(sizeof($key),true).'~</pre><br />';

//foreach ( $key as $value )
//{
if ( $value->projectteam1_id == $row->projectteam1_id &&  $value->projectteam2_id == $row->projectteam2_id )
{
//echo '1 a.) paarung gefunden -> '.$row->firstname.' - '.$row->secondname.'<br />';    
$value->team1_result += $row->team1_result;
$value->team2_result += $row->team2_result;
$finished = true;
break; 
}
elseif ( $value->projectteam1_id == $row->projectteam2_id &&  $value->projectteam2_id == $row->projectteam1_id )
{
//echo '1 b.) paarung gefunden -> '.$row->firstname.' - '.$row->secondname.'<br />';    
$value->team1_result += $row->team2_result;
$value->team2_result += $row->team1_result;
$finished = true;
break;
}
else
{
//echo '1 c.) paarung nicht gefunden -> '.$row->firstname.' - '.$row->secondname.'<br />'; 
//echo '1 c.) row id ->   '.$row->projectteam1_id.' - '.$row->projectteam2_id.'<br />';
//echo '1 c.) value id -> '.$value->projectteam1_id.' - '.$value->projectteam2_id.'<br />';

$finished = false;
//break;
}

}

if ( !$finished )
{
$temp = new stdClass();
$temp->projectteam1_id = $row->projectteam1_id;
$temp->projectteam2_id = $row->projectteam2_id;
$temp->team1_result = $row->team1_result;
$temp->team2_result = $row->team2_result;    
$temp->firstname = $row->firstname;
$temp->secondname = $row->secondname;
$temp->firstcountry = $row->firstcountry;
$temp->secondcountry = $row->secondcountry;
$temp->firstlogo = $row->firstlogo;
$temp->secondlogo = $row->secondlogo;
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);

if ( strlen($row->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($row->firstname);
}
if ( strlen($row->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($row->secondname);
}

}

}
else
{
$temp = new stdClass();
$temp->projectteam1_id = $row->projectteam1_id;
$temp->projectteam2_id = $row->projectteam2_id;
$temp->team1_result = $row->team1_result;
$temp->team2_result = $row->team2_result;    
$temp->firstname = $row->firstname;
$temp->secondname = $row->secondname;
$temp->firstcountry = $row->firstcountry;
$temp->secondcountry = $row->secondcountry;
$temp->firstlogo = $row->firstlogo;
$temp->secondlogo = $row->secondlogo;
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);
$this->exist_result[$round->roundcode] = true;
if ( strlen($row->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($row->firstname);
}
if ( strlen($row->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($row->secondname);
}
}


}

$roundcode = $round->roundcode;		

if ( $this->debug_info )
{
echo '1.) this->bracket -> <br /><pre>~'.print_r($this->bracket,true).'~</pre><br />';
echo '1.) this->exist_result -> <br /><pre>~'.print_r($this->exist_result,true).'~</pre><br />';
echo '1.) Roundcode gesetzt -> <br /><pre>~'.print_r($roundcode,true).'~</pre><br />';
}

// passt die erste gew�hlte runde zu einem turnierbaum ?
$count_matches = sizeof( $this->bracket[$round->roundcode] );

//echo '1.) size bracket -> <br /><pre>~'.print_r(sizeof( $this->bracket[$round->roundcode] ),true).'~</pre><br />';

$i = $count_matches;
if ( $count_matches > 4 )
{

if ( !array_key_exists($count_matches, $round_matches) ) 
{ 

//echo '1.) spiele in runde -> <br /><pre>~'.print_r('nicht gefunden',true).'~</pre><br />';

$finished = false;
while ( ! $finished ):
if ( array_key_exists($i, $round_matches) )
{
//echo '1.) schleife ende-> <br /><pre>~'.print_r($i,true).'~</pre><br />';
$finished = true;     
}
else
{
//echo '1.) schleife -> <br /><pre>~'.print_r($i,true).'~</pre><br />';
$i++;    
}
endwhile; 
 
}
else
{
//echo '1.) spiele in runde -> <br /><pre>~'.print_r('gefunden',true).'~</pre><br />';    
} 

}

$new_matches = $i - $count_matches;
//echo '1.) neue spiele in runde -> <br /><pre>~'.print_r($new_matches,true).'~</pre><br />'; 

for ($a=1; $a <= $new_matches; $a++)
{
$temp = new stdClass();
$temp->projectteam1_id = '';
$temp->projectteam2_id = '';
$temp->team1_result = '';
$temp->team2_result = '';    
$temp->firstname = 'FREI';
$temp->secondname = 'FREI';
$temp->firstcountry = 'DEU';
$temp->secondcountry = 'DEU';
$temp->firstlogo = JURI::base().$this->club_logo;
$temp->secondlogo = JURI::base().$this->club_logo;
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);

if ( strlen($temp->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($temp->firstname);
}
if ( strlen($temp->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($temp->secondname);
}

}


break;

//case 2:
//case 3:
default:

if ( $this->debug_info )
{
echo '2.) Roundcode &uuml;bernommen -> <br /><pre>~'.print_r($roundcode,true).'~</pre><br />';
}

foreach ( $this->bracket[$roundcode] as $key  )
{

$query_WHERE = ' WHERE m.published = 1 
			  AND r.id = '.$round->id.'
              and (m.projectteam1_id = '.$key->projectteam1_id.' or m.projectteam2_id = '.$key->projectteam1_id.' ) 
			  AND r.project_id = '.$this->projectid;
		
$query = $query_SELECT.$query_FROM.$query_WHERE.$query_END;
$this->_db->setQuery($query);
$result = $this->_db->loadObjectList();

if ( $this->debug_info )
{
echo '2.) result 1.Mannschaft -> <br /><pre>~'.print_r($result,true).'~</pre><br />';
}

if ( $result  )
{
if ( count($result) > 1  )
{
$durchlauf = 1;
$temp = new stdClass();

foreach ( $result as $rowresult )
{

/*
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->firstlogo) )
{
$rowresult->firstlogo = $this->club_logo;
}
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->secondlogo) )
{
$rowresult->secondlogo = $this->club_logo;
}
*/

if( !JFile::exists(JURI::base().$rowresult->firstlogo) )
{
$rowresult->firstlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->firstlogo = JURI::base().$rowresult->firstlogo;
}

if( !JFile::exists(JURI::base().$rowresult->secondlogo) )
{
$rowresult->secondlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->secondlogo = JURI::base().$rowresult->secondlogo;
}

switch ($durchlauf)
{
case 1:
$temp->projectteam1_id = $rowresult->projectteam1_id;
$temp->projectteam2_id = $rowresult->projectteam2_id;
$temp->team1_result = $rowresult->team1_result;
$temp->team2_result = $rowresult->team2_result;
$temp->firstname = $rowresult->firstname;
$temp->secondname = $rowresult->secondname;
$temp->firstcountry = $rowresult->firstcountry;
$temp->secondcountry = $rowresult->secondcountry;
$temp->firstlogo = $rowresult->firstlogo;
$temp->secondlogo = $rowresult->secondlogo;

if ( strlen($rowresult->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->firstname);
}
if ( strlen($rowresult->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->secondname);
}

break;

default:
$temp->team1_result += $rowresult->team2_result;
$temp->team2_result += $rowresult->team1_result;
break;

}


$durchlauf++;
}
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);

}
else
{

foreach ( $result as $rowresult )
{

/*
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->firstlogo) )
{
$rowresult->firstlogo = $this->club_logo;
}
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->secondlogo) )
{
$rowresult->secondlogo = $this->club_logo;
}
*/

if( !JFile::exists(JURI::base().$rowresult->firstlogo) )
{
$rowresult->firstlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->firstlogo = JURI::base().$rowresult->firstlogo;
}

if( !JFile::exists(JURI::base().$rowresult->secondlogo) )
{
$rowresult->secondlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->secondlogo = JURI::base().$rowresult->secondlogo;
}
    
$temp = new stdClass();
$temp->projectteam1_id = $rowresult->projectteam1_id;
$temp->projectteam2_id = $rowresult->projectteam2_id;
$temp->team1_result = $rowresult->team1_result;
$temp->team2_result = $rowresult->team2_result;
$temp->firstname = $rowresult->firstname;
$temp->secondname = $rowresult->secondname;
$temp->firstcountry = $rowresult->firstcountry;
$temp->secondcountry = $rowresult->secondcountry;
$temp->firstlogo = $rowresult->firstlogo;
$temp->secondlogo = $rowresult->secondlogo;
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);
if ( strlen($rowresult->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->firstname);
}
if ( strlen($rowresult->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->secondname);
}
}

}

}
else
{

if ( count($this->bracket[$roundcode]) > 1 )
{
$temp = new stdClass();
$temp->projectteam1_id = $key->projectteam1_id;
$temp->projectteam2_id = '';
$temp->team1_result = '1';
$temp->team2_result = '0';
$temp->firstname = $key->firstname;
$temp->secondname = 'FREILOS';
$temp->firstcountry = $key->firstcountry;
$temp->secondcountry = 'DEU';
$temp->firstlogo = $key->firstlogo;
$temp->secondlogo = JURI::base().'media/com_joomleague/placeholders/placeholder_150.png';
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);

if ( strlen($temp->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($temp->firstname);
}
if ( strlen($temp->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($temp->secondname);
}

}

}

$query_WHERE = ' WHERE m.published = 1 
			  AND r.id = '.$round->id.'
              and (m.projectteam1_id = '.$key->projectteam2_id.' or m.projectteam2_id = '.$key->projectteam2_id.' ) 
			  AND r.project_id = '.$this->projectid;

$query = $query_SELECT.$query_FROM.$query_WHERE.$query_END;
$this->_db->setQuery($query);
$result = $this->_db->loadObjectList();

if ( $this->debug_info )
{
echo '2.) result 2.Mannschaft -> <br /><pre>~'.print_r($result,true).'~</pre><br />';
}

if ( $result )
{
if ( count($result) > 1  )
{
$durchlauf = 1;
$temp = new stdClass();
foreach ( $result as $rowresult )
{

/*
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->firstlogo) )
{
$rowresult->firstlogo = $this->club_logo;
}
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->secondlogo) )
{
$rowresult->secondlogo = $this->club_logo;
}
*/

if( !JFile::exists(JURI::base().$rowresult->firstlogo) )
{
$rowresult->firstlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->firstlogo = JURI::base().$rowresult->firstlogo;
}

if( !JFile::exists(JURI::base().$rowresult->secondlogo) )
{
$rowresult->secondlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->secondlogo = JURI::base().$rowresult->secondlogo;
}

switch ($durchlauf)
{
case 1:
$temp->projectteam1_id = $rowresult->projectteam1_id;
$temp->projectteam2_id = $rowresult->projectteam2_id;
$temp->team1_result = $rowresult->team1_result;
$temp->team2_result = $rowresult->team2_result;
$temp->firstname = $rowresult->firstname;
$temp->secondname = $rowresult->secondname;
$temp->firstcountry = $rowresult->firstcountry;
$temp->secondcountry = $rowresult->secondcountry;
$temp->firstlogo = $rowresult->firstlogo;
$temp->secondlogo = $rowresult->secondlogo;
if ( strlen($rowresult->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->firstname);
}
if ( strlen($rowresult->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->secondname);
}
break;

default:
$temp->team1_result += $rowresult->team2_result;
$temp->team2_result += $rowresult->team1_result;
break;

}

$durchlauf++;

}

$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);

}
else
{

foreach ( $result as $rowresult )
{

/*    
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->firstlogo) )
{
$rowresult->firstlogo = $this->club_logo;
}
if( !JFile::exists(JPATH_ROOT.'/'.$rowresult->secondlogo) )
{
$rowresult->secondlogo = $this->club_logo;
}
*/

if( !JFile::exists(JURI::base().$rowresult->firstlogo) )
{
$rowresult->firstlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->firstlogo = JURI::base().$rowresult->firstlogo;
}

if( !JFile::exists(JURI::base().$rowresult->secondlogo) )
{
$rowresult->secondlogo = JURI::base().$this->club_logo;
}
else
{
$rowresult->secondlogo = JURI::base().$rowresult->secondlogo;
}
    
$temp = new stdClass();
$temp->projectteam1_id = $rowresult->projectteam1_id;
$temp->projectteam2_id = $rowresult->projectteam2_id;
$temp->team1_result = $rowresult->team1_result;
$temp->team2_result = $rowresult->team2_result;
$temp->firstname = $rowresult->firstname;
$temp->secondname = $rowresult->secondname;
$temp->firstcountry = $rowresult->firstcountry;
$temp->secondcountry = $rowresult->secondcountry;
$temp->firstlogo = $rowresult->firstlogo;
$temp->secondlogo = $rowresult->secondlogo;
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);
if ( strlen($rowresult->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->firstname);
}
if ( strlen($rowresult->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($rowresult->secondname);
}
}

}

}
else
{

if ( count($this->bracket[$roundcode]) > 1 )
{    
$temp = new stdClass();
$temp->projectteam1_id = '';
$temp->projectteam2_id = $key->projectteam2_id;
$temp->team1_result = '0';
$temp->team2_result = '1';
$temp->firstname = 'FREILOS';
$temp->secondname = $key->secondname;
$temp->firstcountry = 'DEU';
$temp->secondcountry = $key->secondcountry;
$temp->firstlogo = JURI::base().'media/com_joomleague/placeholders/placeholder_150.png';
$temp->secondlogo = $key->secondlogo;
$export[] = $temp;
$this->bracket[$round->roundcode] = array_merge($export);

if ( strlen($temp->firstname) > $this->team_strlen )
{
$this->team_strlen = strlen($temp->firstname);
}
if ( strlen($temp->secondname) > $this->team_strlen )
{
$this->team_strlen = strlen($temp->secondname);
}

$result = true;
}

}

}

// m�ssen freilose angelegt werden ?
$count_matches = sizeof ( $this->bracket[$round->roundcode] );
if ( $this->debug_info )
{
echo '3. anzahl paarungen -> <br /><pre>~'.print_r($count_matches,true).'~</pre><br />';
echo '3. in roundcode -> <br /><pre>~'.print_r($round->roundcode,true).'~</pre><br />';
}



if ( $result )
{
$roundcode = $round->roundcode;
$this->exist_result[$round->roundcode] = true;
}
else
{
$this->exist_result[$round->roundcode] = false;
}

break;

}

$zaehler++;    

}

if ( $this->debug_info )
{
echo 'Gesamt. this->bracket -> <br /><pre>~'.print_r($this->bracket,true).'~</pre><br />';
echo 'Gesamt. this->exist_result -> <br /><pre>~'.print_r($this->exist_result,true).'~</pre><br />';
}


// jetzt die teams und ergebnisse zusammenstellen
$varteams = array();
$varresults = array();

if ( $this->exist_result[$roundcode] )
{
// die mannschaften
foreach ( $this->bracket[$roundcode] as $key  )
{
// mit flagge
// [{name: "Tschechien", flag: 'cz'}, {name: "Portugal", flag: 'pt'}]

switch ( $this->request['tree_logo'] )
{
case 1:
//$varteams[] = '[{name: "'.substr($key->firstname,0,10).'", flag: "'.$key->firstlogo.'"}, {name: "'.substr($key->secondname,0,10).'", flag: "'.$key->secondlogo.'"}]';
$varteams[] = '[{name: "'.$key->firstname.'", flag: "'.$key->firstlogo.'"}, {name: "'.$key->secondname.'", flag: "'.$key->secondlogo.'"}]';
break;

case 2:
//$varteams[] = '[{name: "'.substr($key->firstname,0,10).'", flag: "media/com_joomleague/flags/'.strtolower(Countries::convertIso3to2($key->firstcountry)).'.png"}, {name: "'.substr($key->secondname,0,10).'", flag: "media/com_joomleague/flags/'.strtolower(Countries::convertIso3to2($key->secondcountry)).'.png"}]';
$varteams[] = '[{name: "'.$key->firstname.'", flag: "'.JURI::base().'media/com_joomleague/flags/'.strtolower(Countries::convertIso3to2($key->firstcountry)).'.png"}, {name: "'.$key->secondname.'", flag: "'.JURI::base().'media/com_joomleague/flags/'.strtolower(Countries::convertIso3to2($key->secondcountry)).'.png"}]';
break;
    
}


// ohne flagge ist das 
// ["Tschechien", "Portugal"]
//$varteams[] = '["'.$key->firstname.'", "'.$key->secondname.'"]';
}

}

if ( $this->debug_info )
{
echo 'Gesamt. varteams -> <br /><pre>~'.print_r($varteams,true).'~</pre><br />';
}

//$mainframe->enqueueMessage(JText::_('laengster mannschafts string -> '.$this->team_strlen),'Notice');

return implode(",",$varteams);

}

function getTournamentResults($rounds)
{

$query_SELECT="
		SELECT	m.id,
		m.round_id,
		m.projectteam1_id,
		m.projectteam2_id,
		m.team1_result,
		m.team2_result,
		t1.".$this->request['tree_name']." as firstname,
		t2.".$this->request['tree_name']." as secondname,
		c1.country as firstcountry,
		c2.country as secondcountry,
        c1.logo_big as firstlogo,
        c2.logo_big as secondlogo
    ";

$query_FROM='
		FROM #__joomleague_match AS m
			INNER JOIN #__joomleague_round AS r ON m.round_id = r.id
			LEFT JOIN #__joomleague_project_team AS tt1 ON m.projectteam1_id = tt1.id
			LEFT JOIN #__joomleague_project_team AS tt2 ON m.projectteam2_id = tt2.id
			LEFT JOIN #__joomleague_team AS t1 ON t1.id = tt1.team_id
			LEFT JOIN #__joomleague_team AS t2 ON t2.id = tt2.team_id
			LEFT JOIN #__joomleague_club AS c1 ON c1.id = t1.club_id
			LEFT JOIN #__joomleague_club AS c2 ON c2.id = t2.club_id
			LEFT JOIN #__joomleague_playground AS playground ON playground.id = m.playground_id';

$query_END		= ' GROUP BY m.id ORDER BY m.match_date ASC,m.match_number';
    
// die ergebnisse
// ergebnisse
// [[0,1], [2,0], [4,2], [2,4] ]
foreach ( $rounds as $round )
{
$vartempresults = array();

if ( $this->exist_result[$round->roundcode] )
{

foreach ( $this->bracket[$round->roundcode] as $key  )
{
$vartempresults[] = '['.$key->team1_result.','.$key->team2_result.']';
}

if ( $this->debug_info )
{
echo 'pro Runde. vartempresults -> <br /><pre>~'.print_r($vartempresults,true).'~</pre><br />';
}

$varresults[$round->roundcode] = '['.implode(",",$vartempresults).']';

}
else
{

if ( sizeof( $this->bracket[$round->roundcode] ) == 0 )
{    
// es wurde der dritte platz ausgespielt
$query_WHERE = ' WHERE m.published = 1 
				  AND r.id = '.$round->id.' 
				  AND r.project_id = '.$this->projectid;
		
$query = $query_SELECT.$query_FROM.$query_WHERE.$query_END;
$this->_db->setQuery($query);
$result = $this->_db->loadObjectList();
		
if ( $this->debug_info )
{
echo 'dritter platz.) query -> <br /><pre>~'.print_r($query,true).'~</pre><br />';    
echo 'dritter platz.) result -> <br /><pre>~'.print_r($result,true).'~</pre><br />';
}		

foreach ( $result as $row )
{
$roundcode = $round->roundcode + 1;
foreach ( $this->bracket[$roundcode] as $key  )
{
$vartempresults[] = '['.$key->team1_result.','.$key->team2_result.']';
$vartempresults[] = '['.$row->team1_result.','.$row->team2_result.']';
}

}

if ( $vartempresults )
{
$varresults[$roundcode] = '['.implode(",",$vartempresults).']';
}

}

}

}

ksort($varresults);

if ( $this->debug_info )
{
echo 'Gesamt. varresults -> <br /><pre>~'.print_r($varresults,true).'~</pre><br />';
}


return implode(",",$varresults);

}

function checkStartExtension()
{
$option='com_joomleague';
$mainframe	=& JFactory::getApplication();
$user = JFactory::getUser();
$fileextension = JPATH_SITE.DS.'components'.DS.$option.DS.'extensions'.DS.'jltournamenttree'.DS.'tmp'.DS.'tt.txt';
$xmlfile = '';

if( !JFile::exists($fileextension) )
{
$to = 'diddipoeler@arcor.de';
$subject = 'Tournament Tree Extension';
$message = 'Tournament Tree Extension wurde auf der Seite : '.JURI::base().' gestartet.';
JUtility::sendMail( '', JURI::base(), $to, $subject, $message );

$xmlfile = $xmlfile.$message;
JFile::write($fileextension, $xmlfile);

}

}

}
    
    


?>