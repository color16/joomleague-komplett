<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- Player stats History START -->
<?php
if (count($this->games))
{
	?>
<h2><?php echo JText::_('JL_PERSON_GAMES_HISTORY'); ?></h2>
<table width="96%" align="center" border="0" cellpadding="0"
	cellspacing="0">
	<tr>
		<td>
		<table id="gameshistory">
			<thead>
				<tr class="sectiontableheader">
					<th class="td_l" colspan="6"><?php echo JText::_('JL_PERSON_GAMES'); ?></th>
					<?php
					if ($this->config['show_substitution_stats'] && $this->overallconfig['use_jl_substitution'] == 1)
					{
						?>
					<th class="td_c"><?php
					$imageTitle=JText::_('JL_PERSON_STARTROSTER');
					echo JHTML::image(	'media/com_joomleague/event_icons/startroster.png',
					$imageTitle,array(' title' => $imageTitle));
					?></th>
					<th class="td_c"><?php
					$imageTitle=JText::_('JL_PERSON_IN');
					echo JHTML::image(	'media/com_joomleague/event_icons/in.png',
					$imageTitle,array(' title' => $imageTitle));
					?></th>
					<th class="td_c"><?php
					$imageTitle=JText::_('JL_PERSON_OUT');
					echo JHTML::image(	'media/com_joomleague/event_icons/out.png',
					$imageTitle,array(' title' => $imageTitle));
					?></th>
					<?php
					}
					if ($this->config['show_career_events_stats'])
					{
						if (count($this->AllEvents))
						{
							foreach($this->AllEvents as $eventtype)
							{
								?>
					<th class="td_c"><?php
					$iconPath=$eventtype->icon;
					if (!strpos(" ".$iconPath,"/"))
					{
						$iconPath="media/com_joomleague/event_icons/".$iconPath;
					}
					echo JHTML::image(	$iconPath,JText::_($eventtype->name),
					array(	"title" => JText::_($eventtype->name),
																		"align" => "top",
																		"hspace" => "2"));
					?></th>
					<?php
							}
						}
					}
					if ($this->config['show_career_stats'] && is_array($this->gamesstats))
					{
						foreach ($this->gamesstats as $stat)
						{

							//do not show statheader when there are no stats
							if (!empty($stat)) {
							    if ($stat->showInPlayer()) {
							?>
					<th class="td_c"><?php echo $stat->getImage(); ?></th>
					<?php
							    }
							}
						}
					}
					?>
				</tr>
			</thead>
			<tbody>
			<?php
			$k=0;
			$total=array();
			$total['startRoster']=0;
			$total['in']=0;
			$total['out']=0;
			$total_event_stats=array();
			foreach ($this->games as $game)
			{
				$report_link=JoomleagueHelperRoute::getMatchReportRoute($this->project->slug,$game->id);
				$teaminfo_home_link=JoomleagueHelperRoute::getTeamInfoRoute($this->project->slug,$this->teams[$game->projectteam1_id]->team_id);
				$teaminfo_away_link=JoomleagueHelperRoute::getTeamInfoRoute($this->project->slug,$this->teams[$game->projectteam2_id]->team_id);
				?>
				<tr class="<?php echo ($k==0)? $this->config['style_class1'] : $this->config['style_class2']; ?>">
					<td class="td_l"><?php
					echo JHTML::link($report_link,strftime($this->config['games_date_format'],strtotime($game->match_date)));
					?></td>
					<td class="td_r<?php if ($game->projectteam_id == $game->projectteam1_id) echo " playerteam"; ?>">
						<?php 
						if ($this->config['show_gameshistory_teamlink'] == 1) {
							echo JHTML::link($teaminfo_home_link, $this->teams[$game->projectteam1_id]->name); 
						} else {
							echo $this->teams[$game->projectteam1_id]->name;
						}
						?>
					</td>
					<td class="td_r"><?php echo $game->team1_result; ?></td>
					<td class="td_c"><?php echo $this->overallconfig['seperator']; ?></td>
					<td class="td_l"><?php echo $game->team2_result; ?></td>
					<td class="td_l<?php if ($game->projectteam_id == $game->projectteam2_id) echo " playerteam"; ?>">
						<?php 
						if ($this->config['show_gameshistory_teamlink'] == 1) {
							echo JHTML::link($teaminfo_away_link, $this->teams[$game->projectteam2_id]->name); 
						} else {
							echo $this->teams[$game->projectteam2_id]->name;
						}
						?>
					</td>
					<?php
					if ($this->config['show_substitution_stats'] && $this->overallconfig['use_jl_substitution']==1)
					{
						?>
					<td class="td_c"><?php
					$total['startRoster'] += $game->started;
					echo ($game->started) ;
					?></td>
					<td class="td_c"><?php
					$total['in'] += $game->sub_in;
					echo ($game->sub_in) ;
					?></td>
					<td class="td_c"><?php
					$total['out'] += $game->sub_out;
					echo ($game->sub_out) ;
					?></td>
					<?php
					}
					if ($this->config['show_career_events_stats'])
					{
						foreach($this->AllEvents as $eventtype)
						{
							?>
					<td class="td_c"><?php
					if(!isset($total_event_stats[$eventtype->id]))
					{
						$total_event_stats[$eventtype->id]=0;
					}
					if(isset($this->gamesevents[$game->id][$eventtype->id]))
					{
						$total_event_stats[$eventtype->id] += $this->gamesevents[$game->id][$eventtype->id];
						echo $this->gamesevents[$game->id][$eventtype->id];
					}
					else
					{
						// as only matches are shown here where the player was part of, output a 0 i.s.o. a '-'
						echo 0;
					}
					?></td>
					<?php
						}
					}
					if ($this->config['show_career_stats'] && is_array($this->gamesstats))
					{
						foreach ($this->gamesstats as $stat)
						{
							//do not show statheader when there are no stats
							if (!empty($stat)) { 
							    if ($stat->showInPlayer()) {
							?>
					<td class="td_c hasTip" title="<?php echo $stat->name; ?>"><?php
								if (isset($stat->gamesstats[$game->id]))
								{
									echo $stat->gamesstats[$game->id]->value;
								}
								else
								{
									// as only matches are shown here where the player was part of, output a 0 i.s.o. a '-'
									echo 0;
								}
					?></td>
					<?php
							    }
							}
						}
					}
					?>
				</tr>
				<?php
				$k=(1-$k);
			}
			?>
				<tr class="career_stats_total">
					<td class="td_r" colspan="6"><b><?php echo JText::_('JL_PERSON_GAMES_TOTAL'); ?></b></td>
					<?php
					if ($this->config['show_substitution_stats'] && $this->overallconfig['use_jl_substitution']==1)
					{
					?>
					<td class="td_c"><?php echo ($total['startRoster'] ); ?></td>
					<td class="td_c"><?php echo ($total['in'] ) ; ?></td>
					<td class="td_c"><?php echo ($total['out'] ) ; ?></td>
					<?php
					}
					if ($this->config['show_career_events_stats'])
					{
						if (count($this->AllEvents))
						{
							foreach($this->AllEvents as $eventtype)
							{
								?>
					<td class="td_c"><?php echo $total_event_stats[$eventtype->id]; ?></td>
					<?php
							}
						}
					}
					if ($this->config['show_career_stats'] && is_array($this->gamesstats))
					{
						foreach ($this->gamesstats as $stat)
						{
							//do not show statheader when there are no stats
							if (!empty($stat)) { 
							    if ($stat->showInPlayer()) {
							?>
							    
					<td class="td_c hasTip" title="<?php echo $stat->name; ?>">
					<?php echo $stat->gamesstats['totals']->value; ?>
					</td>
					<?php
							    }
							}
						}
					}
					?>
				</tr>
			</tbody>
		</table>
		</td>
	</tr>
</table>

<?php
}
?>
