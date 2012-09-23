<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php
				echo JText::_('JL_ADMIN_PROJECTS_LIST_FILTER');
				?>&nbsp;<input	type="text" name="search" id="search"
								value="<?php echo $this->lists['search']; ?>"
								class="text_area" onchange="document.adminForm.submit(); " />
				<button onclick="this.form.submit(); "><?php echo JText::_('JL_GLOBAL_GO'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit(); ">
					<?php
					echo JText::_('JL_GLOBAL_RESET');
					?>
				</button>
			</td>
			<td nowrap="nowrap" align="right"><?php echo $this->lists['sportstypes'].'&nbsp;&nbsp;'; ?></td>
			<td nowrap="nowrap" align="right"><?php echo $this->lists['leagues'].'&nbsp;&nbsp;'; ?></td>
			<td nowrap="nowrap" align="right"><?php echo $this->lists['seasons'].'&nbsp;&nbsp;'; ?></td>
			<td nowrap="nowrap" align="right"><?php echo $this->lists['state']; ?></td>
		</tr>
	</table>
	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5" style="vertical-align: top; "><?php echo JText::_('JL_GLOBAL_NUM'); ?></th>
					<th width="20" style="vertical-align: top; ">
						<input  type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
					</th>
					<th width="20" style="vertical-align: top; ">&nbsp;</th>
					<th class="title" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_ADMIN_PROJECTS_NAME_OF_PROJECT','p.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_ADMIN_PROJECTS_LEAGUE','l.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_ADMIN_PROJECTS_SEASON','s.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_ADMIN_PROJECTS_SPORTSTYPE','st.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_ADMIN_PROJECTS_PROJECTTYPE','p.project_type',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th width="5%" nowrap="nowrap">
						<?php
						echo JText::_('JL_ADMIN_PROJECTS_GAMES');
						?>
					</th>
					<th width="5%" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_GLOBAL_PUBLISHED','p.published',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th width="85" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_GLOBAL_ORDER','p.ordering',$this->lists['order_Dir'],$this->lists['order']);
						?>
						<?php
						echo JHTML::_('grid.order',$this->items);
						?>
					</th>
					<th nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort','JL_GLOBAL_ID','p.id',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
				</tr>
			</thead>
			<tfoot><tr><td colspan='12'><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
			<tbody>
				<?php
				$k=0;
				for ($i=0,$n=count($this->items); $i < $n; $i++)
				{
					$row =& $this->items[$i];

					$link=JRoute::_('index.php?option=com_joomleague&controller=project&task=edit&cid[]='.$row->id);
					$link2=JRoute::_('index.php?option=com_joomleague&view=projects&controller=project&cid[]='.$row->id);
					$link2panel=JRoute::_('index.php?option=com_joomleague&controller=cpanel&task=workspace&layout=panel&pid[]='.$row->id.'&stid[]='.$row->sports_type_id);

					$checked    = JHTML::_('grid.checkedout',$row,$i);
					$published  = JHTML::_('grid.published',$row,$i);
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td style="text-align:center; "><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td style="text-align:center; "><?php echo $checked; ?></td>
						<?php
						if (JTable::isCheckedOut($this->user->get ('id'),$row->checked_out))
						{
							$inputappend=' disabled="disabled"';
							?><td style="text-align:center; ">&nbsp;</td><?php
						}
						else
						{
							$inputappend='';
							?>
							<td style="text-align:center; ">
								<a href="<?php echo $link; ?>">
									<img src="<?php echo JURI::root();?>/administrator/components/com_joomleague/assets/images/edit.png"
										 border="0"
										 alt="<?php echo JText::_('JL_ADMIN_PROJECTS_EDIT_DETAILS');?>"
										 title="<?php echo JText::_('JL_ADMIN_PROJECTS_EDIT_DETAILS');?>" />
								</a>
							</td>
							<?php
						}
						?>
						<td>
							<?php
							if (JTable::isCheckedOut($this->user->get('id'),$row->checked_out))
							{
								echo $row->name;
							}
							else
							{
								?><a href="<?php echo $link2panel; ?>"><?php echo $row->name; ?></a><?php
							}
							?>
						</td>
						<td><?php echo $row->league; ?></td>
						<td style="text-align:center; "><?php echo $row->season; ?></td>
						<td style="text-align:center; "><?php echo JText::_($row->sportstype); ?></td>
						<td style="text-align:center; "><?php echo JText::_($row->project_type); ?></td>
						<td style="text-align:center; ">
							<?php if ($row->current_round): ?>
								<?php echo JHTML::link('index.php?option=com_joomleague&controller=match&view=matches&pid[]='.$row->id.'&rid[]='. $row->current_round,
								                       JHTML::image(JURI::root().'administrator/components/com_joomleague/assets/images/icon-16-Matchdays.png', JText::_('JL_ADMIN_PROJECTS_GAMES_DETAILS'))); ?>
							<?php endif; ?>
						</td>
						<td style="text-align:center; "><?php echo $published; ?></td>
						<td class="order">
							<span>
								<?php echo $this->pagination->orderUpIcon($i,$i > 0 ,'orderup','JL_GLOBAL_ORDER_UP',true); ?>
							</span>
							<span>
								<?php echo $this->pagination->orderDownIcon($i,$n,$i < $n,'orderdown','JL_GLOBAL_ORDER_DOWN',true); ?>
							</span>
							<?php
							$disabled=true ?  '' : 'disabled="disabled"';
							?>
							<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
						</td>
						<td style="text-align:center; "><?php echo $row->id; ?></td>
					</tr>
					<?php
					$k=1 - $k;
				}
				?>
			</tbody>
		</table>
	</div>

	<input type="hidden" name="view"				value="projects" />
	<input type="hidden" name="controller"			value="project" />
	<input type="hidden" name="task"				value="" />
	<input type="hidden" name="boxchecked"			value="0" />
	<input type="hidden" name="filter_order"		value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir"	value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>