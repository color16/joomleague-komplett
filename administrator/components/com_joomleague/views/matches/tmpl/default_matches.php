<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
?>
<div id="editcell">
	<fieldset class="adminform">
		<legend><?php echo JText::sprintf('JL_ADMIN_MATCHES_TITLE2','<i>'.$this->roundws->name.'</i>','<i>'.$this->projectws->name.'</i>'); ?></legend>
		<!-- round selector START -->
		<form name="roundForm" method="post">
			<input type='hidden' name='controller' value='match' />
			<input type='hidden' name='option' value='com_joomleague' id='option' />
			<input type='hidden' name='view' value='matches' id='view' />
			<input type='hidden' name='task' value='' />
			<input type='hidden' name='boxchecked' value='0' />
			<?php echo JHTML::_('form.token')."\n"; ?>

			<div align="right">
				<?php
				$lv=""; $nv=""; $sv=false;

				foreach($this->ress as $v) { if ($v->id == $this->roundws->id) { break; } $lv=$v->id; }
				foreach($this->ress as $v) { $nv=$v->id; if ($sv) { break; } if ($v->id == $this->roundws->id) { $sv=true; } }

				if ($lv != "")
				{
					$query="option=com_joomleague&controller=match&view=matches&rid[]=".$lv;
					$link=JRoute::_('index.php?'.$query);
					$prevlink=JHTML::link($link,JText::_('JL_ADMIN_MATCHES_PREV_MATCH'));
					echo $prevlink;
				}
				else
				{
					echo JText::_('JL_ADMIN_MATCHES_PREV_MATCH');
				}

				echo "&nbsp;&nbsp;";
				echo $this->lists['project_rounds'];
				echo "&nbsp;&nbsp;";

				if (($nv != "") && ($nv != $this->roundws->id))
				{
					$query="option=com_joomleague&controller=match&view=matches&rid[]=".$nv;
					$link=JRoute::_('index.php?'.$query);
					$nextlink=JHTML::link($link,JText::_('JL_ADMIN_MATCHES_NEXT_MATCH'));
					echo $nextlink;
				}
				else
				{
					echo JText::_('JL_ADMIN_MATCHES_NEXT_MATCH');
				}
				?>
			</div>
		</form>
		<!-- round selector END -->
		<!-- Start games list -->
		<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" id='adminForm'>
			<?php
			$colspan=($this->projectws->allow_add_time) ? 16 : 15;
			?>
			<table class='adminlist' border='0'>
				<thead>
					<tr>
						<th width="5" ><?php echo count($this->matches).'/'.$this->pagination->total; ?></th>
						<th width="20" >
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->matches); ?>);" />
						</th>
						<th width="20" >&nbsp;</th>
						<th width="20" >
							<?php echo JHTML::_('grid.sort','JL_ADMIN_MATCHES_MATCHNR','mc.match_number',$this->lists['order_Dir'],$this->lists['order']); ?>
						</th>
						<th class="title" nowrap="nowrap" >
							<?php echo JHTML::_('grid.sort','JL_ADMIN_MATCHES_DATE','mc.match_date',$this->lists['order_Dir'],$this->lists['order']); ?>
						</th>
						<th class="title" nowrap="nowrap" ><?php echo JTEXT::_('JL_ADMIN_MATCHES_TIME'); ?></th>
						<th class="title" nowrap="nowrap" ><?php echo JText::_( 'JL_ADMIN_MATCH_F_MD_ATT' ); ?></th>
						
            <?php 
							if($this->projectws->project_type=='DIVISIONS_LEAGUE') 
              {
								$colspan++;
						?>
						<th >
							<?php 
								echo JHTML::_('grid.sort','JL_ADMIN_MATCHES_DIVISION','divhome.id',$this->lists['order_Dir'],$this->lists['order']);
								echo '<br>'.JHTML::_(	'select.genericlist',
													$this->lists['divisions'],
													'division',
													'class="inputbox" size="1" onchange="window.location.href=window.location.href.split(\'&division=\')[0]+\'&division=\'+this.value"',
													'value','text', $this->division);
								
							?>
						</th>
						<?php 
							}
							// roundlist for change round in match
							if( $this->show_roundlist_in_matches )
							{
							?>
              <th class="title" nowrap="nowrap" ><?php echo JTEXT::_('JL_ADMIN_MATCHES_CHANGE_ROUNDLIST'); ?></th>
              <?php 
              }
              
              
              
							
						?>
						<th class="title" nowrap="nowrap" ><?php echo JTEXT::_('JL_ADMIN_MATCHES_HOME_TEAM'); ?></th>
						<th class="title" nowrap="nowrap" ><?php echo JTEXT::_('JL_ADMIN_MATCHES_AWAY_TEAM'); ?></th>
						<th style="  "><?php echo JTEXT::_('JL_ADMIN_MATCHES_RESULT'); ?></th>
						<?php
						if ($this->projectws->allow_add_time)
						{
							?>
							<th style="text-align:center;  "><?php echo JTEXT::_('JL_ADMIN_MATCHES_RESULT_TYPE'); ?></th>
							<?php
						}
						?>
						<th class="title" nowrap="nowrap" ><?php echo JTEXT::_('JL_ADMIN_MATCHES_EVENTS'); ?></th>
						<th class="title" nowrap="nowrap" ><?php echo JTEXT::_('JL_ADMIN_MATCHES_STATISTICS'); ?></th>
						<th class="title" nowrap="nowrap" ><?php echo JTEXT::_('JL_ADMIN_MATCHES_REFEREE'); ?></th>
						<th width="1%" nowrap="nowrap" ><?php echo JTEXT::_('JL_GLOBAL_PUBLISHED'); ?></th>
						<th width="1%" class="title" nowrap="nowrap" >
							<?php echo JHTML::_('grid.sort','JL_GLOBAL_ID','mc.id',$this->lists['order_Dir'],$this->lists['order']); ?>
						</th>
					</tr>
				</thead>
				<tfoot><tr><td colspan="<?php echo $colspan; ?>"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
				<tbody>
					<?php
					$k=0;
					for ($i=0,$n=count($this->matches); $i < $n; $i++)
					{
						$row		=& $this->matches[$i];
						$checked	= JHTML::_('grid.checkedout',$row,$i,'id');
						$published	= JHTML::_('grid.published',$row,$i);

						list($date,$time)=explode(" ",$row->match_date);
						$time=strftime("%H:%M",strtotime($time));
						?>
						<tr class="<?php echo "row$k"; ?>">
						<?php if(($row->cancel)>0)
								{
									$style="text-align:center;  background-color: #FF9999;";
								}
								else
								{
									$style="text-align:center; ";
								}
								?>
							<td style="<?php echo $style;?>">
								<?php
								echo $this->pagination->getRowOffset($i);
								?>
							</td>
							<td style="text-align:center; ">
								<?php
								echo $checked;
								?>
							</td>
							<td style="text-align:center; ">
								<a	rel="{handler: 'iframe',size: {x: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900); ?>,y: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600); ?>}}"
									href="index.php?option=com_joomleague&tmpl=component&controller=match&task=edit&cid[]=<?php echo $row->id; ?>"
									 class="modal">
									<?php
									echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
													JText::_('JL_ADMIN_MATCHES_EDIT_DETAILS'),'title= "' .
													JText::_('JL_ADMIN_MATCHES_EDIT_DETAILS').'"');
									?>
								</a>
							</td>
							<td style="text-align:center; ">
								<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" name="match_number<?php echo $row->id; ?>"
										value="<?php echo $row->match_number; ?>" size="6" tabindex="1" class="inputbox" />
							</td>
							<td nowrap="nowrap" style="text-align:center; ">
								<?php
								echo JHTML::calendar(	JoomleagueHelper::convertDate($date),
														'match_date'.$row->id,
														'match_date'.$row->id,
														'%d-%m-%Y',
														'size="9"  tabindex="2" ondblclick="copyValue(\'match_date\')" onchange="document.getElementById(\'cb'.$i.'\').checked=true"');
								?>
							</td>
							<td nowrap="nowrap" style="text-align: left; ">


								<input ondblclick="copyValue('match_time')" onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" name="match_time<?php echo $row->id; ?>"
										value="<?php echo $time; ?>" size="4" maxlength="5" tabindex="3" class="inputbox" />

								<a	href="javascript:void(0)"
									onclick="switchMenu('present<?php echo $row->id; ?>')">&nbsp;
									<?php echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/arrow_open.png',
															JTEXT::_('JL_ADMIN_MATCHES_PRESENT'),
															'title= "'.JTEXT::_('JL_ADMIN_MATCHES_PRESENT').'"');
									?>
								</a><br />
								<span id="present<?php echo $row->id; ?>" style="display: none">
									<br />
										<input ondblclick="copyValue('time_present')" onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" name="time_present<?php echo $row->id; ?>"
												value="<?php echo $row->time_present; ?>" size="4" maxlength="5" tabindex="4" class="inputbox" title="<?php echo JText::_('JL_ADMIN_MATCHES_PRESENT'); ?>" />	
										<?php echo JText::_('JL_ADMIN_MATCHES_PRESENT_SHORT'); ?>
								</span>
							</td>
							<td style="text-align:center; ">
								<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" name="crowd<?php echo $row->id; ?>"
										value="<?php echo $row->crowd; ?>" size="4" maxlength="5" tabindex="4" class="inputbox" />
							</td>
              
							<?PHP
              // divisionlist for change division in match  
              if( $this->projectws->project_type == 'DIVISIONS_LEAGUE' )
							{
							$append='';
							$append.=' onchange="document.getElementById(\'cb'.$i.'\').checked=true" ';
              ?>
							<td style="text-align:center; ">
								<?php
							echo JHTML::_(	'select.genericlist',$this->lists['divisions'],'division_id'.$row->id,
												'class="inputbox select-division_id" size="1"'.$append,'value','text',$row->division_id);
							?>
							</td>
							<?php 
							}  
              
              // roundlist for change round in match  
              if( $this->show_roundlist_in_matches )
							{
							$append='';
							$append.=' onchange="document.getElementById(\'cb'.$i.'\').checked=true" ';
              ?>
							<td style="text-align:center; ">
								<?php
							echo JHTML::_(	'select.genericlist',$this->lists['project_change_rounds'],'round_id'.$row->id,
												'class="inputbox select-round_id" size="1"'.$append,'value','text',$row->round_id);
							?>
							</td>
							<?php 
							}
              
              
              
              
                 
							?>
							<td style="text-align: right; " nowrap="nowrap">
								<a	onclick="handleRosterIconClick(<?php echo $this->prefill; ?>, this, '<?php echo JText::_('JL_ADMIN_MATCH_PREFILL_LAST_ROSTER_ALERT'); ?>', '<?php echo JText::_('JL_ADMIN_MATCH_PREFILL_PROJECTTEAM_PLAYERS_ALERT')?>')"
									rel="{handler: 'iframe',size: {x: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900); ?>,y: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600); ?>}}"
									href="index.php?option=com_joomleague&tmpl=component&controller=match&task=editlineup&cid[]=<?php echo $row->id; ?>&team=<?php echo $row->projectteam1_id; ?>&prefill="
									 class="modal openroster-team1<?php echo $row->id; ?>"
									 title="<?php echo JText::_('JL_ADMIN_MATCHES_EDIT_LINEUP_HOME'); ?>">
									 <?php
									 if($row->homeplayers_count==0 || $row->homestaff_count==0 ) {
									 	$image = 'players_add.png';
									 } else {
									 	$image = 'players_edit.png';
									 }
									 $title=  ' '.JText::_('JL_F_PLAYERS').': ' .$row->homeplayers_count. ', ' . 
													 ' '.JText::_('JL_F_TEAM_STAFF').': ' .$row->homestaff_count . ' ';
									
									echo '<sub>'.$row->homeplayers_count.'</sub> ';
												 

									 echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/'.$image,
													 JText::_('JL_ADMIN_MATCHES_EDIT_LINEUP_HOME'),
													 'title= "' .$title. '"');
													 
									 echo '<sub>'.$row->homestaff_count.'</sub> ';	
									 									 ?>
								</a>
								<?php
								$append='';
								if ($row->projectteam1_id == 0)
								{
									$append=' style="background-color:#bbffff"';
								}
								$append.=' onchange="document.getElementById(\'cb'.$i.'\').checked=true" ';
								echo JHTML::_(	'select.genericlist',$this->lists['teams_'+$row->divhomeid],'projectteam1_id'.$row->id,
												'class="inputbox select-hometeam" size="1"'.$append,'value','text',$row->projectteam1_id);
								?>
							</td>
							<td style="text-align: left; " nowrap="nowrap">
								<?php
								$append='';
								if ($row->projectteam2_id == 0)
								{
									$append=' style="background-color:#bbffff"';
								}
								$append.=' onchange="document.getElementById(\'cb'.$i.'\').checked=true" ';
								echo JHTML::_(	'select.genericlist',$this->lists['teams_'+$row->divhomeid],'projectteam2_id'.$row->id,
												'class="inputbox select-awayteam" size="1"'.$append,'value','text',$row->projectteam2_id);
								?>
								<a	onclick="handleRosterIconClick(<?php echo $this->prefill; ?>, this, '<?php echo JText::_('JL_ADMIN_MATCH_PREFILL_LAST_ROSTER_ALERT'); ?>', '<?php echo JText::_('JL_ADMIN_MATCH_PREFILL_PROJECTTEAM_PLAYERS_ALERT')?>')"
									rel="{handler: 'iframe',size: {x: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900); ?>,y: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600); ?>}}"
									href="index.php?option=com_joomleague&tmpl=component&controller=match&task=editlineup&cid[]=<?php echo $row->id;?>&team=<?php echo $row->projectteam2_id;?>&prefill="
									class="modal open-starting-away"
									title="<?php echo JText::_('JL_ADMIN_MATCHES_EDIT_LINEUP_AWAY'); ?>">
									 <?php
									 if($row->awayplayers_count==0 || $row->awaystaff_count==0 ) {
									 	$image = 'players_add.png';
									 } else {
									 	$image = 'players_edit.png';
									 }
									 $title=' '.JText::_('JL_F_PLAYERS').': ' .$row->awayplayers_count. ', ' . 
													 ' '.JText::_('JL_F_TEAM_STAFF').': ' .$row->awaystaff_count;
													 
									 echo '<sub>'.$row->awayplayers_count.'</sub> ';
									 echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/'.$image,
													 JText::_('JL_ADMIN_MATCHES_EDIT_LINEUP_AWAY'),
													 'title= "' .$title. '"');
									 echo '<sub>'.$row->awaystaff_count.'</sub> ';	
								
									  ?>
								</a>
							</td>
							<td nowrap="nowrap" style="text-align: left; ">
								<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" <?php if($row->alt_decision==1) echo "class=\"subsequentdecision\" title=\"".JText::_('JL_ADMIN_MATCHES_SUB_DECISION')."\"" ?> type="text" name="team1_result<?php echo $row->id; ?>"
										value="<?php echo $row->team1_result; ?>" size="2" tabindex="5" class="inputbox" /> : 
								<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" <?php if($row->alt_decision==1) echo "class=\"subsequentdecision\" title=\"".JText::_('JL_ADMIN_MATCHES_SUB_DECISION')."\"" ?> type="text" name="team2_result<?php echo $row->id; ?>"
										value="<?php echo $row->team2_result; ?>" size="2" tabindex="5" class="inputbox" />
								<a	href="javascript:void(0)"
									onclick="switchMenu('part<?php echo $row->id; ?>')">&nbsp;
									<?php echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/arrow_open.png',
															JText::_('JL_ADMIN_MATCHES_PERIOD_SCORES'),
															'title= "'.JText::_('JL_ADMIN_MATCHES_PERIOD_SCORES').'"');
									?>
								</a><br />
								<span id="part<?php echo $row->id; ?>" style="display: none">
									<br />
									<?php
									$partresults1=explode(";",$row->team1_result_split);
									$partresults2=explode(";",$row->team2_result_split);
									for ($x=0; $x < ($this->projectws->game_parts); $x++)
									{
										 ?>

										<input	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" onchange="document.getElementById(\'cb'<?php echo $i; ?>'\').checked=true" type="text" style="font-size: 9px;"
												name="team1_result_split<?php echo $row->id;?>[]"
												value="<?php echo (isset($partresults1[$x])) ? $partresults1[$x] : ''; ?>"
												size="2" tabindex="6" class="inputbox" /> : 
										<input	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" onchange="document.getElementById(\'cb'<?php echo $i; ?>'\').checked=true" type="text" style="font-size: 9px;"
												name="team2_result_split<?php echo $row->id; ?>[]"
												value="<?php echo (isset($partresults2[$x])) ? $partresults2[$x] : ''; ?>"
												size="2" tabindex="6" class="inputbox" />
										<?php
										echo '&nbsp;&nbsp;'.($x+1).".<br />";
									}
									if ($this->projectws->allow_add_time == 1)
									{
										 ?>

										<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" style="font-size: 9px;" name="team1_result_ot<?php echo $row->id;?>"
											value="<?php echo (isset($row->team1_result_ot)) ? $row->team1_result_ot : '';?>"
											size="2" tabindex="7" class="inputbox" /> : 
										<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" style="font-size: 9px;" name="team2_result_ot<?php echo $row->id;?>"
											value="<?php echo (isset($row->team2_result_ot)) ? $row->team2_result_ot : '';?>"
											size="2" tabindex="7" class="inputbox" />
										<?php echo '&nbsp;&nbsp;OT:<br />';?>

										<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" style="font-size: 9px;" name="team1_result_so<?php echo $row->id;?>"
											value="<?php echo (isset($row->team1_result_so)) ? $row->team1_result_so : '';?>"
											size="2" tabindex="8" class="inputbox" /> : 
										<input onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" type="text" style="font-size: 9px;" name="team2_result_so<?php echo $row->id;?>"
											value="<?php echo (isset($row->team2_result_so)) ? $row->team2_result_so : '';?>"
											size="2" tabindex="8" class="inputbox" />
										<?php echo '&nbsp;&nbsp;SO:<br />';?>
										<?php
									}
									?>
								</span>
							</td>
							<?php
							if ($this->projectws->allow_add_time)
							{
								?>
								<td nowrap="nowrap">
									<?php
									echo JHTML::_(	'select.genericlist',$this->lists['match_result_type'],
													'match_result_type'.$row->id,'class="inputbox" size="1"','value','text',
													$row->match_result_type);
									?>
								</td>
								<?php
							}
							?>
							<td style="text-align:center; ">
								<a	rel="{handler: 'iframe',size: {x: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900); ?>,y: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600); ?>}}"
									 href="index.php?option=com_joomleague&tmpl=component&controller=match&task=editevents&cid[]=<?php echo $row->id; ?>"
									 class="modal open-editevents"
									 title="<?php echo JText::_('JL_ADMIN_MATCHES_EDIT_EVENTS'); ?>">
									 <?php
									 echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/events.png',
													 JText::_('JL_ADMIN_MATCHES_EDIT_EVENTS'),'title= "'.JText::_('JL_ADMIN_MATCHES_EDIT_EVENTS').'"');
									 ?>
								</a>
								<?php
								//end statistics
								//start add several events in one operation:
								?>
								<a	rel="{handler: 'iframe',size: {x: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900); ?>,y: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600); ?>}}"
									 href="index.php?option=com_joomleague&controller=match&task=editeventsbb&cid[]=<?php echo $row->id; ?>"
									 title="<?php echo JText::_('JL_ADMIN_MATCHES_EDIT_SBBEVENTS'); ?>">
									 <?php
									 echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/teams.png',
													 JText::_('JL_ADMIN_MATCHES_EDIT_SBBEVENTS'),'title= "'.JText::_('JL_ADMIN_MATCHES_EDIT_SBBEVENTS').'"');
								?>
								</a>
								<?php
								//end several events
								?>
							</td>
							<td style="text-align:center; ">
								<?php
								//start statistics:
								?>
								<a	rel="{handler: 'iframe',size: {x: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900); ?>,y: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600); ?>}}"
									 href="index.php?option=com_joomleague&tmpl=component&controller=match&task=editstats&cid[]=<?php echo $row->id; ?>"
									 class="modal open-editstats"
									 title="<?php echo JText::_('JL_ADMIN_MATCHES_EDIT_STATS'); ?>">
									 <?php
									 echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/calc16.png',
													 JText::_('JL_ADMIN_MATCHES_EDIT_STATS'),'title= "'.JText::_('JL_ADMIN_MATCHES_EDIT_STATS').'"');
								?>
								</a>								
							</td>
							<td style="text-align:center; ">
								<a	rel="{handler: 'iframe',size: {x: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900); ?>,y: <?php echo JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600); ?>}}"
									href="index.php?option=com_joomleague&tmpl=component&controller=match&task=editreferees&cid[]=<?php echo $row->id; ?>&team=<?php echo $row->team1; ?>"
									 class="modal open-editreferees"
									 title="<?php echo JText::_('JL_ADMIN_MATCHES_EDIT_REFEREES'); ?>">
									 <?php
									 if($row->referees_count==0) {
									 	$image = 'players_add.png';
									 } else {
									 	$image = 'icon-16-Referees.png';
									 }
									 $title= $row->referees_count;
									 echo JHTML::_(	'image','administrator/components/com_joomleague/assets/images/'.$image,
													 JText::_('JL_ADMIN_MATCHES_EDIT_REFEREES'),
													 'title= "'. $title. '"') ;
									 echo '<sub>'.$row->referees_count.'</sub> ';	
									 ?>
								</a>
							</td>
							<td style="text-align:center; ">
								<?php
								echo $published;
								?>
							</td>
							<td style="text-align:center; ">
								<?php
								echo $row->id;
								?>
							</td>
						</tr>
						<?php
						$k=1 - $k;
					}
					?>
				</tbody>
			</table>
				
			<?php $dValue=$this->roundws->round_date_first.' '.$this->projectws->start_time; ?>
			
			<input type='hidden' name='match_date' value='<?php echo $dValue; ?>' />
			<input type='hidden' name='act' value='' id='short_act' />
			<input type='hidden' name='controller' value='match' />
			<input type='hidden' name='boxchecked' value='0' />
			<input type='hidden' name='search_mode' value='<?php echo $this->lists['search_mode']; ?>' />
			<input type='hidden' name='filter_order' value='<?php echo $this->lists['order']; ?>' />
			<input type='hidden' name='filter_order_Dir' value='' />
			<input type='hidden' name='rid[]' value='<?php echo $this->roundws->id; ?>' />
			<input type='hidden' name='project_id' value='<?php echo $this->roundws->project_id; ?>' />
			<input type='hidden' name='act' value='' />
			<input type='hidden' name='task' value='' id='task' />
			<?php echo JHTML::_('form.token')."\n"; ?>
		</form>
	</fieldset>
</div>