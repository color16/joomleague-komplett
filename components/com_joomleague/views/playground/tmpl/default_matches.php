<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php

if ( $this->games )
#if (1==1)
{
	?>
	<!-- Playground next games -->
	<div id="jlg_plgndnextgames">

<h2><?php echo JText::_('JL_PLAYGROUND_NEXT_GAMES'); ?></h2>
		<div class="venuecontent map">
					<table width="96%" align="center" border="0" cellpadding="0" cellspacing="0">
						<?php
						//sort games by dates
						$gamesByDate = Array();
						foreach ( $this->games as $game )
						{
							$gamesByDate[substr( $game->match_date, 0, 10 )][] = $game;
						}
						// $teams = $this->project->getTeamsFromMatches( $this->games );

						$colspan = 5;
						if ($this->config['show_logo'] == 1) {
							$colspan = 7;
						}

						foreach ( $gamesByDate as $date => $games )
						{
							?>
							<tr>
								<td align="left" colspan="<?php echo $colspan; ?>" class="sectiontableheader">
									<?php
									echo JHTML::date( $date, JText::_( 'JL_GLOBAL_MATCHDAYDATE' ) );
									?>
								</td>
							</tr>
							<?php
							foreach ( $games as $game )
							{
								$home = $this->gamesteams[$game->team1];
								$away = $this->gamesteams[$game->team2];
								?>
								<tr class="sectiontableentry1">
									<td>
										<?php
										echo substr( $game->match_date, 11, 5 );
										?>
									</td>
									<td nowrap="nowrap">
										<?php
										echo $game->project_name;
										?>
									</td>
									<?php
									if ($this->config['show_logo'] == 1) {
										$model = & $this->getModel();
										$home_logo = $model->getTeamLogo($home->id);
										$away_logo = $model->getTeamLogo($away->id);
										$teamA = '<td align="right" valign="top" nowrap="nowrap">';
										$teamA .= " " . JoomleagueModelProject::getClubIconHtml( $home_logo[0], 1 );
										$teamA .= '</td>';
										echo $teamA;
									}
									?>
									<td nowrap="nowrap">
										<?php
										echo $home->name;
										?>
									</td>
									<td nowrap="nowrap">-</td>
									<?php
									if ($this->config['show_logo'] == 1) {
										$teamB = '<td align="right" valign="top" nowrap="nowrap">';
										$teamB .= " " . JoomleagueModelProject::getClubIconHtml( $away_logo[0], 1 );
										$teamB .= '</td>';
										echo $teamB;
									}
									?>
									<td nowrap="nowrap">
										<?php
										echo $away->name;
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</table>
			</div>
	</div>
	<!-- End of playground next games -->
	<?php
}
?>