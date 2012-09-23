<?php
/**
 * @package
 * @author
 * @link
 * @version
 * @copyright
 * @license
 */

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');
define('JLG_LIVE_TICKER_PATH_SITE',  JPATH_SITE . DS . 'components' .DS . 'com_joomleague'); 
require_once( JLG_LIVE_TICKER_PATH_SITE . DS . 'helpers' . DS . 'countries.php' );
$action = JRequest::getCmd('action');

$use_local_jquery   = $params->get( 'use_local_jquery', true );
$allow_unregistered = $params->get( 'allow_unregistered', false );
$use_secret_salt    = $params->get( 'use_secret_salt', true );
$secret_salt        = $params->get( 'secret_salt', 'tGbd8mfTb4p3f1_aAQpn84Qds' );
$add_timeout        = $params->get( 'add_timeout' , 240);
$update_timeout     = $params->get( 'update_timeout' , 240);

$display_username   = $params->get( 'display_username', 1 );
$display_title      = $params->get( 'display_title', 1 );
$display_num        = $params->get( 'display_num', 5 );
$display_guests     = $params->get( 'display_guests', 1 );
$display_welcome    = $params->get( 'display_welcome', 1 );
$size	            = $params->get( 'size' );
$cols	            = $params->get( 'cols' );
$rows	            = $params->get( 'rows' );

$use_css            = $params->get( 'use_css', 'simple' );
$class	            = $params->get( 'moduleclass_sfx', '' );



$user		     =& JFactory::getUser();
$userId		     = (int) $user->get('id');
$name		     = $user->get('name');
$display_add_box = ($userId || $allow_unregistered);

$display_teamname    = $params->get( 'display_teamname', 1 );
$display_teamwappen    = $params->get( 'display_teamwappen', 0 );
$display_anstoss    = $params->get( 'display_anstoss', 0 );
$display_abpfiff    = $params->get( 'display_abpfiff', 0 );
$display_liganame    = $params->get( 'display_liganame', 0 );
$display_ligaflagge    = $params->get( 'display_ligaflagge', 0 );

$is_ajaxed = isset($_SERVER["HTTP_X_REQUESTED_WITH"])?($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") : false;

switch ($action) {
	case "turtushout_shout":

		if (!$display_add_box) {
			$ajax_return = "Login First!";
			break;
		}
		if ($use_secret_salt && !(
			JRequest::getInt('ts') && isset($_COOKIE['tstoken']) && $_COOKIE['tstoken'] == md5($secret_salt . JRequest::getInt('ts'))
		)) {
			$ajax_return = "Access Error!";
			break;
		}
		if($use_secret_salt && ((JRequest::getInt('ts') + 120) < mktime())) {
			$ajax_return = "Access Error!";
			break;
		}

		$errors = modTurtushoutHelper::shout($display_username, $display_title, $add_timeout);
		if ($errors)
			$ajax_return = $errors;
		else
			$ajax_return = "Shouted!";
	break;
	case "turtushout_del":
		if ($user->gid == 25) {
			$errors = modTurtushoutHelper::delete();
			if ($errors)
				$ajax_return = $errors;
			else
				$ajax_return = "Deleted!";
		} else {
			$ajax_return = "Access denied!";
		}
	break;
	case "turtushout_token":
		$ct = mktime();
		setcookie('tstoken', md5($secret_salt . $ct), 0, '/');
		$ajax_return = $ct;
	break;
	default:

	break;

}

if (!$is_ajaxed || ($action == "turtushout_shouts"))
   {
	$list      = modTurtushoutHelper::getList($params, $display_num);
	$list_html = "";
	
$list_html .= "<div class='turtushout-entry'>";

$list_html .=  "<div class='turtushout-name'>";
$list_html .=  "<table width=\"100%\">";

$list_html .=  "<thead>" ;
$list_html .=  "<tr>" ;
$list_html .= "<td colspan=\"\" align=\"middle\" >" . "aktuelle Zeit" . "</td>";
$list_html .= "<td colspan=\"8\" align=\"left\" >" . date("H:i:s",time()). "</td>";
$list_html .= "</tr>" ;
$list_html .=  "<tr>" ;

if ( $display_liganame == 0 && $display_ligaflagge == 0 )
{
$list_html .= "<td colspan=\"2\">" . "Liga" . "</td>";
}

if ( $display_liganame == 1 && $display_ligaflagge == 1 )
{
}

if ( ( $display_liganame == 0 && $display_ligaflagge == 1 ) || ( $display_liganame == 1 && $display_ligaflagge == 0 ) )
{
$list_html .= "<td colspan=\"1\">" . "Liga" . "</td>";
}


if ( $display_anstoss == 0 )
{
$list_html .= "<td>" . "Anpfiff" . "</td>";
}

if ( $display_abpfiff == 0 )
{
$list_html .= "<td>" . "Abpfiff" . "</td>";
}

// wappen und teamname
if ( $display_teamwappen == 0 && $display_teamname < 3 )
{
$list_html .= "<td colspan=\"4\" align=\"middle\" >" . "Paarung" . "</td>";
}
// kein wappen und teamname
if ( $display_teamwappen == 1 && $display_teamname < 3 )
{
$list_html .= "<td colspan=\"2\" align=\"middle\" >" . "Paarung" . "</td>";
}
// wappen und kein teamname
if ( $display_teamwappen == 0 && $display_teamname == 3 )
{
$list_html .= "<td colspan=\"2\" align=\"middle\" >" . "Paarung" . "</td>";
}
// kein wappen und kein teamname
if ( $display_teamwappen == 1 && $display_teamname == 3 )
{
}


$list_html .= "<td colspan=\"2\">" . "Ergebnis" . "</td>";
$list_html .= "</tr>" ;

$list_html .=  "</thead>" ;

	for ($i = 0, $ic = count($list); $i<$ic; $i++)
    {
//		$list_html .= "<div class='turtushout-entry'>";

//        $list_html .=  "<div class='turtushout-name'>" . $list[$i]->name . " </div>";
//        $list_html .=  "<div class='turtushout-name'>" . $list[$i]->heim . " </div>";
//        $list_html .=  "<div class='turtushout-name'>" . $list[$i]->gast . " </div>";
//        $list_html .=  "<div class='turtushout-name'>" . $list[$i]->matchpart1_result . " </div>";
//        $list_html .=  "<div class='turtushout-name'>" . $list[$i]->matchpart2_result . " </div>";

$anstossdatum = explode(" ",$list[$i]->match_date);

$anstoss = $anstossdatum[1];
$abpfiff = $anstoss + ( ( $list[$i]->game_regular_time + $list[$i]->halftime ) * 60 );

$abpfiff = date('H:i:s', strtotime($anstoss) + ($list[$i]->game_regular_time + $list[$i]->halftime)*60);

$matchpart1_pic = (trim($list[$i]->wappenheim) != "")? sprintf('<img src="%s" alt="%s" width="20"/>', $list[$i]->wappenheim, $list[$i]->heim) : "";
$matchpart2_pic = (trim($list[$i]->wappengast) != "")? sprintf('<img src="%s" alt="%s" width="20"/>', $list[$i]->wappengast, $list[$i]->gast) : "";

//$isoflag =  strtolower($list[$i]->countries_iso_code_2);

$list_html .=  "<tr>" ;

// ligaflagge
if ( $display_ligaflagge == 0 )
{
//$list_html .= "<td>" . "<img src=\""."/images/joomleague/flags/".$isoflag.".png\" alt=\"".$list[$i]->countries_iso_code_3."\" title=\"".$list[$i]->countries_iso_code_3."\" hspace=\"2\" /> " . "</td>";
$list_html .= "<td>" . Countries::getCountryFlag($list[$i]->leaguecountry) . "</td>";
}

// liganame
if ( $display_liganame == 0 )
{
$list_html .= "<td>" . $list[$i]->name . "</td>";
}

if ( $display_anstoss == 0 )
{
$list_html .= "<td>" . $anstoss . "</td>";
}

if ( $display_abpfiff == 0 )
{
$list_html .= "<td>" . $abpfiff . "</td>";
}

if ( $display_teamwappen == 0 )
{
$list_html .= "<td>" . $matchpart1_pic . "</td>";
}

if ( $display_teamname == 0 )
{
$list_html .= "<td>" . $list[$i]->heim . "</td>";
}
if ( $display_teamname == 1 )
{
$list_html .= "<td>" . $list[$i]->heim_middle_name . "</td>";
}
if ( $display_teamname == 2 )
{
$list_html .= "<td>" . $list[$i]->heim_short_name . "</td>";
}
if ( $display_teamname == 3 )
{
}



if ( $display_teamwappen == 0 )
{
$list_html .= "<td>" . $matchpart2_pic . "</td>";
}


if ( $display_teamname == 0 )
{
$list_html .= "<td>" . $list[$i]->gast . "</td>";
}
if ( $display_teamname == 1 )
{
$list_html .= "<td>" . $list[$i]->gast_middle_name . "</td>";
}
if ( $display_teamname == 2 )
{
$list_html .= "<td>" . $list[$i]->gast_short_name . "</td>";
}
if ( $display_teamname == 3 )
{
}

$list_html .= "<td>" . $list[$i]->team1_result . "</td>";
$list_html .= "<td>" . $list[$i]->team2_result . "</td>";
$list_html .= "</tr>" ;
//$list_html .= "</table>";
//$list_html .= "</div>";

        
		/*
		if ( $list[$i]->created_by_alias && $display_username) {
			if ($list[$i]->created_by && $display_guests) {
				$list_html .=  "<div class='turtushout-name'>" . $list[$i]->created_by_alias . " says:</div>";
			} else {
				$list_html .=  "<div class='turtushout-name'>Guest " . $list[$i]->created_by_alias . " says:</div>";
			}
		}
		$list_html .=  "<div class='turtushout-created'>" . $list[$i]->created;
		if ($user->gid == 25)
			$list_html .= "<a class='turtushout-action' onclick='TurtushoutDelete(" . $list[$i]->id . "); return false;'>[x]</a>";
		$list_html .= "</div>";
		if ( $list[$i]->title && $display_title)
			$list_html .=  "<div class='turtushout-title'>" . $list[$i]->title . "</div>";
		$list_html .=  "<div class='turtushout-text'>" . $list[$i]->text . "</div>";
		*/

//		$list_html .= "</div>";
	}
        $list_html .= "</table>";
		$list_html .= "</div>";
		$list_html .= "</div>";
		
	$ajax_return = $list_html;

}

if ($is_ajaxed) {
	echo $ajax_return;
	exit;
}



require(JModuleHelper::getLayoutPath('mod_joomleague_liveticker'));
