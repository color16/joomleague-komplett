<?php
/**
 * @copyright  Copyright (C) 2005-2012 JoomLeague.net. All rights reserved.
 * @license    GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>
<h1><?php echo JText::sprintf('JL_ADMIN_MATCH_ER_TITLE'); ?></h1>
<div class="clear"></div>
<div id="lineup">
	<form id="startingSquadsForm" name="startingSquadsForm" method="post">
		<fieldset class="adminform">
			<legend><?php echo JText::_('JL_ADMIN_MATCH_ER_DESCR'); ?></legend>
			<table class='adminlist'>
			<thead>
				<tr>
					<th>
					<?php echo JText::_('JL_ADMIN_MATCH_ER_REFS'); ?>
					</th>
					<th>
					<?php echo JText::_('JL_ADMIN_MATCH_ER_ASSIGNED'); ?>
					</th>					
				</tr>
			</thead>			
				<tr>
					<td colspan="2">
						<input type="submit" class="inputbox" value="<?php echo JText::_('JL_ADMIN_MATCH_ER_SAVE_REF'); ?>" />
					</td>
				</tr>
				<tr>
					<td style="text-align:center; ">
						<?php
						// echo select list of non assigned players from team roster
						echo $this->lists['team_referees'];
						?>
					</td>
					<td style="text-align:center; vertical-align:top; ">
						<table>
							<?php
							foreach ($this->positions AS $key=>$pos)
							{
								?>
								<tr>
									<td style='text-align:center; vertical-align:middle; '>
										<!-- left / right buttons -->
										<br />
										<input	type="button" id="moveright-<?php echo $key;?>" class="inputbox move-right"
												value="&gt;&gt;" /><br />
										&nbsp;&nbsp;
										<input	type="button" id="moveleft-<?php echo $key;?>" class="inputbox move-left"
												value="&lt;&lt;" />
										&nbsp;&nbsp;
									</td>
									<td>
										<!-- player affected to this position -->
										<b><?php echo JText::_($pos->text); ?></b><br />
										<?php echo $this->lists['team_referees'.$key];?>
									</td>
									<td style='text-align:center; vertical-align:middle; '>
										<!-- up/down buttons -->
										<br />
										<input	type="button" id="moveup-<?php echo $key;?>" class="inputbox move-up"
												value="<?php echo JText::_('JL_GLOBAL_UP'); ?>" /><br />
										<input	type="button" id="movedown-<?php echo $key;?>" class="inputbox move-down"
												value="<?php echo JText::_('JL_GLOBAL_DOWN'); ?>" />
									</td>
								</tr>
								<?php
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<br/>
		<br/>
		<input type="hidden" name="task" value="saveReferees" />
		<input type="hidden" name="view" value="match" />
		<input type="hidden" name="controller" value="match" />
		<input type="hidden" name="cid[]" value="<?php echo $this->match->id; ?>" />
		<input type="hidden" name="project" value="<?php echo $this->project_id; ?>" />
		<input type="hidden" name="changes_check" value="0" id="changes_check" />
		<input type="hidden" name="option" value="com_joomleague" id="option" />
		<input type="hidden" name="positionscount" value="<?php echo count($this->positions); ?>" id="positioncount" />
		<?php echo JHTML::_('form.token')."\n"; ?>
	</form>
</div>