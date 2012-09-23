<?php
require_once( 'joomleague.core.php' );
function JoomleagueBuildRoute( &$query )
{
	$segments = array();

	// include extensions routers for custom views - if extension does have a route file, use it
	$extensions = JoomleagueHelper::getExtensions(0);
	foreach ($extensions as $type)
	{
		$file = JLG_PATH_SITE.DS.'extensions'.DS.$type.DS.'route.php';
		if (file_exists($file))
		{
			require_once($file);
			$obj = new $classname();
			$func = 'JoomleagueBuildRoute'.ucfirst($type);
			if ($segs = $func($query))
			{
				return $segs;
			}
		}
	}

	$view = (isset($query['view']) ? $query['view'] : null);
	if ($view)
	{
		$segments[] = $view;
		unset($query['view']);
	}
	else {
		return $segments;
	}

	// project id, always just after the view if specified
	if (isset($query['p']))
	{
		$segments[] = $query['p'];
		unset( $query['p'] );
	}

	// now, the specifics
	switch ($view)
	{
		case 'clubinfo':
			if (isset($query['cid']))
			{
				$segments[] = $query['cid'];
				unset( $query['cid'] );
			}
			break;
		case 'curve':
			if (isset($query['tid1']))
			{
				$segments[] = $query['tid1'];
				unset( $query['tid1'] );
			}
			if (isset($query['tid2']))
			{
				$segments[] = $query['tid2'];
				unset( $query['tid2'] );
			}
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			break;
		case 'eventsranking':
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			if (isset($query['tid']))
			{
				$segments[] = $query['tid'];
				unset( $query['tid'] );
			}
			if (isset($query['evid']))
			{
				$segments[] = $query['evid'];
				unset( $query['evid'] );
			}
			if (isset($query['mid']))
			{
				$segments[] = $query['mid'];
				unset( $query['mid'] );
			}
			break;
		case 'editmatch':
		case 'editevents':
			if (isset($query['mid']))
			{
				$segments[] = $query['mid'];
				unset( $query['mid'] );
			}
			break;
		case "matrix":
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			if (isset($query['r']))
			{
				$segments[] = $query['r'];
				unset( $query['r'] );
			}
			break;
		case 'matchreport':
		case 'nextmatch':
			if (isset($query['mid']))
			{
				$segments[] = $query['mid'];
				unset( $query['mid'] );
			}
			if (isset($query['pics']))
			{
				$segments[] = $query['pics'];
				unset( $query['pics'] );
			}
			if (isset($query['ptid']))
			{
				$segments[] = $query['ptid'];
				unset( $query['ptid'] );
			}
			break;
		case "playground":
			if (isset($query['pgid']))
			{
				$segments[] = $query['pgid'];
				unset( $query['pgid'] );
			}
			break;
		case 'ranking':
			if (isset($query['type']))
			{
				$segments[] = $query['type'];
				unset( $query['type'] );
			}
			if (isset($query['r']))
			{
				$segments[] = $query['r'];
				unset( $query['r'] );
			}
			if (isset($query['from']))
			{
				$segments[] = $query['from'];
				unset( $query['from'] );
			}
			if (isset($query['to']))
			{
				$segments[] = $query['to'];
				unset( $query['to'] );
			}
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			break;
		case 'roster':
		case 'teaminfo':
		case 'teamstats':
			if (isset($query['tid']))
			{
				$segments[] = $query['tid'];
				unset( $query['tid'] );
			}
			break;
		case 'teamplan':
			if (isset($query['tid']))
			{
				$segments[] = $query['tid'];
				unset( $query['tid'] );
			}
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			if (isset($query['mode']))
			{
				$segments[] = $query['mode'];
				unset( $query['mode'] );
			}
			break;
		case 'results':
		case 'resultsmatrix':
		case 'resultsranking':
			if (isset($query['r']))
			{
				$segments[] = $query['r'];
				unset( $query['r'] );
			}
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			if (isset($query['mode']))
			{
				$segments[] = $query['mode'];
				unset( $query['mode'] );
			}
			if (isset($query['order']))
			{
				$segments[] = $query['order'];
				unset( $query['order'] );
			}
			if (isset($query['form']))
			{
				$segments[] = $query['form'];
				unset( $query['form'] );
			}
			break;
		case 'player':
		case 'staff':
			if (isset($query['tid']))
			{
				$segments[] = $query['tid'];
				unset( $query['tid'] );
			}
			if (isset($query['pid']))
			{
				$segments[] = $query['pid'];
				unset( $query['pid'] );
			}
			break;
		case 'clubs':
		case 'stats':
		case 'teams':
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			break;
		case 'statsranking':
			if (isset($query['division']))
			{
				$segments[] = $query['division'];
				unset( $query['division'] );
			}
			if (isset($query['tid']))
			{
				$segments[] = $query['tid'];
				unset( $query['tid'] );
			}
			break;
		case 'referee':
			if (isset($query['pid']))
			{
				$segments[] = $query['pid'];
				unset( $query['pid'] );
			}
			break;
		case 'tree':
			if (isset($query['did']))
			{
				$segments[] = $query['did'];
				unset( $query['did'] );
			}
			break;
				
		default:
			break;
	}
	return $segments;
}

function JoomleagueParseRoute( $segments )
{
	// include extensions routers for custom views - if extension does have a route file, use it
	$extensions = JoomleagueHelper::getExtensions(0);
	foreach ($extensions as $type)
	{
		$file = JLG_PATH_SITE.DS.'extensions'.DS.$type.DS.'route.php';
		if (file_exists($file))
		{
			require_once($file);
			$obj = new $classname();
			$func = 'JoomleagueParseRoute'.ucfirst($type);
			if ($vars = $func($segments))
			{
				return $vars;
			}
		}
	}

	$vars = array();

	$vars['view'] = $segments[0];

	switch( $vars['view'] ) // the view...
	{
		case 'clubinfo':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['cid'] = $segments[2];
			}
			break;
		case 'curve':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['tid1'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['tid2'] = $segments[3];
			}
			if (isset($segments[4])) {
				$vars['division'] = $segments[4];
			}
				
			break;
		case 'eventsranking':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['division'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['tid'] = $segments[3];
			}
			if (isset($segments[4])) {
				$vars['evid'] = $segments[4];
			}
			if (isset($segments[5])) {
				$vars['mid'] = $segments[5];
			}
			break;
		case 'editmatch':
		case 'editevents':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['mid'] = $segments[2];
			}
			break;
		case 'matrix':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['division'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['r'] = $segments[3];
			}
			break;
		case 'playground':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['pgid'] = $segments[2];
			}
			break;
		case "ranking":
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['type'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['r'] = $segments[3];
			}
			if (isset($segments[4])) {
				$vars['from'] = $segments[4];
			}
			if (isset($segments[5])) {
				$vars['to'] = $segments[5];
			}
			if (isset($segments[6])) {
				$vars['division'] = $segments[6];
			}
			break;
		case 'teamplan':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['tid'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['division'] = $segments[3];
			}
			if (isset($segments[4])) {
				$vars['mode'] = $segments[4];
			}
			break;
		case 'roster':
		case 'teaminfo':
		case 'teamstats':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['tid'] = $segments[2];
			}
			break;
				
		case 'results':
		case 'resultsmatrix':
		case 'resultsranking':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['r'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['division'] = $segments[3];
			}
			if (isset($segments[4])) {
				$vars['mode'] = $segments[4];
			}
			if (isset($segments[5])) {
				$vars['order'] = $segments[5];
			}
			if (isset($segments[6])) {
				$vars['layout'] = $segments[6];
			}
			break;
				
		case 'matchreport':
		case 'nextmatch':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['mid'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['pics'] = $segments[3];
			}
			if (isset($segments[4])) {
				$vars['ptid'] = $segments[4];
			}
			break;

			// /esl-dods-fall-league-2010-roster/staff/44-ED - DODS Fall League 2010/1-zj/2-and-one
		case 'player':
		case 'staff':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['tid'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['pid'] = $segments[3];
			}
			break;
		case 'clubs':
		case 'stats':
		case 'teams':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['division'] = $segments[2];
			}
			break;
		case 'statsranking':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['division'] = $segments[2];
			}
			if (isset($segments[3])) {
				$vars['tid'] = $segments[3];
			}
			break;
			// /standard-referee/referee/46/2
		case 'referee':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['pid'] = $segments[2];
			}
			break;
		case 'tree':
			if (isset($segments[1])) {
				$vars['p'] = $segments[1];
			}
			if (isset($segments[2])) {
				$vars['did'] = $segments[2];
			}
			break;
				
		default:
			if (isset($segments[1])) {
			$vars['p'] = $segments[1];
		}
		break;
	}
	return $vars;
}
?>