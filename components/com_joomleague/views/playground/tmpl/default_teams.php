<?php 
defined( '_JEXEC' ) or die( 'Restricted access' ); 
?>

<div class="contentpaneopen" >	
<div class="contentheading">
<h2>
			<?php echo JText::_('JL_PLAYGROUND_CLUB_TEAMS'); ?>
            </h2>
		</div> 

<!-- Now show teams of this club -->
<div class="venuecontent">
	<?php foreach ((array)$this->teams AS $key => $value): ?>	
	  <?php $projectname = $value->project; ?> 
	  <?php foreach ($value->project_team AS $team): 
			$teaminfo = $value->teaminfo[0][0];
			$link = JoomleagueHelperRoute :: getTeamInfoRoute($team->project_id, $team->team_id);	
			?>
			<h4><?php echo $projectname . " - " . JHTML :: link($link, $teaminfo->name) . ($teaminfo->short_name ? " (" . $teaminfo->short_name . ")" : ''); ?></h4>
			<div class="clubteaminfo">
        	<?php
        	$description = $teaminfo->notes;
        	echo (!empty($description) ? JText :: _('JL_PLAYGROUND_TEAMINFO') . " " . JHTML::_('content.prepare', $description) : ''); ?>
			</div>
		<?php endforeach; ?>
	<?php endforeach; ?>
</div>
</div>