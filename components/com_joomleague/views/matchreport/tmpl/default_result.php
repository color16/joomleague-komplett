<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<!-- START: game result -->
<table class="matchreport" border="0">

    <?php
    if ($this->config['show_team_logo'] == 1)
    {
    ?>
        <tr>
            <td class="teamlogo">
                <?php 
                //dynamic object property string
                $pic = $this->config['show_picture'];
                echo JoomleagueHelper::getPictureThumb($this->team1->$pic, 
                                            $this->team1->name,
                                            $this->config['team_picture_width'],
                                            $this->config['team_picture_height'],1);
                ?>
		</td>
		<td>
		</td>
		<td class="teamlogo">
			<?php 
			echo JoomleagueHelper::getPictureThumb($this->team2->$pic, 
										$this->team2->name,
										$this->config['team_picture_width'],
										$this->config['team_picture_height'],1);
		?>
		</td>
	</tr>

    <?php
    } // end team logo
    ?>
    
	<tr>
		<td class="team">
			<?php 
			if ( $this->config['names'] == "short_name" ) {
			    echo $this->team1->short_name; 
			}
			if ( $this->config['names'] == "middle_name" ) {
			    echo $this->team1->middle_name; 
			}
			if ( $this->config['names'] == "name" ) {
			    echo $this->team1->name; 
			}
			?>
		</td>
		<td>
			<?php echo JText::_('JL_MATCHREPORT_VS') ?>
		</td>
		<td class="team">
			<?php 
			if ( $this->config['names'] == "short_name" ) {
			    echo $this->team2->short_name; 
			}
			if ( $this->config['names'] == "middle_name" ) {
			    echo $this->team2->middle_name; 
			}
			if ( $this->config['names'] == "name" ) {
			    echo $this->team2->name; 
			}
			?>
		</td>
	</tr>
</table>

<?php
if ($this->match->cancel > 0)
{
	?>
	<table class="matchreport" border="0">
		<tr>
			<td class="result">
					<?php echo $this->match->cancel_reason; ?>
			</td>
		</tr>
	</table>
	<?php
}
else
{
	?>
	<table class="matchreport" border="0">
		<tr>
			<td class="result">
				<?php echo $this->showMatchresult($this->match->alt_decision, 1); ?>
			</td>
			<td class="result">
				<?php echo $this->showMatchresult($this->match->alt_decision, 2); ?>
			</td>
		</tr>
        
		<?php
        if ($this->config['show_period_result'] == 1)
        {
            if ( $this->showLegresult() )
            {
                ?>
                <tr>
                    <td class="legs">
                        <?php echo $this->showLegresult(1); ?>
                    </td>
                    <td class="legs">
                        <?php echo $this->showLegresult(2); ?>
                    </td>
                </tr>
                <?php
            }
        }

        if ($this->config['show_overtime_result'] == 1)
        {
            if ( $this->showOvertimeResult() )
            {
                ?>
                <tr>
                    <td class="legs" colspan="2">
                        <?php echo JText::_('JL_MATCHREPORT_OVERTIME');
                        echo " " . $this->showOvertimeresult(); ?>
                    </td>
                </tr>
                <?php
            }
        }

        if ($this->config['show_shotout_result'] == 1)
        {
            if ( $this->showShotoutResult() )
            {
                ?>
                <tr>
                    <td class="legs" colspan="2">
                        <?php echo JText::_('JL_MATCHREPORT_SHOOTOUT');
                        echo " " . $this->showShotoutResult(); ?>
                    </td>
                </tr>
                <?php
            }
        }
		?>
	</table>
	<?php
}
?>

<!-- START of decision info -->
<?php
if ( $this->match->decision_info != '' )
{
	?>
	<table class="matchreport">
		<tr>
			<td>
				<i><?php echo $this->match->decision_info; ?></i>
			</td>
		</tr>
	</table>

	<?php
}
?>
<!-- END of decision info -->
<!-- END: game result -->
