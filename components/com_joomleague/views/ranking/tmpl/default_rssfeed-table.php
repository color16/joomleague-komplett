<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
echo 'rssfeed <br><pre>';
print_r( $this->rssfeedoutput );
echo '</pre>';
*/

?>

<?php if($mainframe->getCfg('caching')): ?>
<script type="text/javascript">
	//<![CDATA[
	document.write('\
	<style type="text/css" media="all">\
		@import "<?php echo $filePath; ?>/css/rssfeedstyle.css";\
	</style>\
	');
	//]]>
</script>
<?php else: ?>
<?php 
 
?>
<?php endif; ?>

<div class="srfrContainer <?php echo $moduleclass_sfx; ?>">
	
	<?php if($this->feedsBlockPreText): ?>
	<p class="srfrPreText"><?php echo $this->feedsBlockPreText; ?></p>
	<?php endif; ?>
	
	<table width="100%" class="srfrList">
		
    <?php
    $rssfeedrow = 1; 
    foreach($this->rssfeedoutput as $key => $feed): 
    if ( $rssfeedrow %2 ) 
    { 
    echo "<tr>"; 
    }
     
    ?>
    
		<td class="srfrRow<?php echo $key%2; ?>">
			<?php if($this->feedItemTitle): ?>
			<h3><a target="_blank" href="<?php echo $feed->itemLink; ?>"><?php echo $feed->itemTitle; ?></a></h3>
			<?php endif; ?>
			
			<?php if($this->feedTitle): ?>
			<span class="srfrFeedSource"<?php if($this->feedFavicon && $feed->feedFavicon) echo ' style="display:block;padding:2px 0 2px 20px;background:url('.$feed->feedFavicon.') no-repeat 0 50%;"'; ?>>
				<a target="_blank" href="<?php echo $feed->siteURL; ?>"><?php echo $feed->feedTitle; ?></a>
			</span>
			<?php endif; ?>

			<?php if($this->feedItemDate): ?>
			<span class="srfrFeedItemDate"><?php echo $feed->itemDate; ?></span>
			<?php endif; ?>			

			<?php if($this->feedItemDescription || $feed->feedImageSrc): ?>
			<p>
				<?php if($feed->feedImageSrc): ?>
				<a target="_blank" href="<?php echo $feed->itemLink; ?>">
					<img class="srfrImage" src="<?php echo $feed->feedImageSrc; ?>" alt="<?php echo $feed->itemTitle; ?>" />
				</a>
				<?php endif; ?>
				
				<?php if($this->feedItemDescription): ?>
				<?php echo $feed->itemDescription; ?>
				<?php endif; ?>
			</p>
			<?php endif; ?>
			
			<?php if($this->feedItemReadMore): ?>
			<span class="srfrReadMore">
				<a target="_blank" href="<?php echo $feed->itemLink; ?>"><?php echo JText::_('MOD_JW_SRFR_READ_MORE'); ?></a>
			</span>
			<?php endif; ?>
			
			<span class="clr"></span>
		</td>
    
		<?php 
    $rssfeedrow++;
    if ( $rssfeedrow %2 ) 
    { 
    echo "</tr>"; 
    }
    endforeach; 
    ?>	
    
	</table>
	
  
  
	<?php if($this->feedsBlockPostText): ?>
	<p class="srfrPostText"><?php echo $this->feedsBlockPostText; ?></p>
	<?php endif; ?>
	
	<?php if($this->feedsBlockPostLink): ?>
	<p class="srfrPostTextLink"><a href="<?php echo $this->feedsBlockPostLinkURL; ?>"><?php echo $this->feedsBlockPostLinkTitle; ?></a></p>
	<?php endif; ?>
</div>

<div class="clr"></div>
