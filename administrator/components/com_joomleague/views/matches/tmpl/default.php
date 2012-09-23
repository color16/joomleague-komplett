<?php defined('_JEXEC') or die('Restricted access');
$version = urlencode(JoomleagueHelper::getVersion());
JHTML::script('JL_matchdetailsediting.js?v='.$version,'administrator/components/com_joomleague/assets/js/');
JHTML::script('JL_matcheventsediting.js?v='.$version,'administrator/components/com_joomleague/assets/js/');

$massadd=JRequest::getVar('massadd');

// Set toolbar items for the page
JToolBarHelper::title(JText::_('JL_ADMIN_MATCHES_TITLE'),'Matchdays');

if (!$massadd)
{
	JToolBarHelper::publishList();
	JToolBarHelper::unpublishList();
	JToolBarHelper::divider();

	JToolBarHelper::apply('saveshort');
	JToolBarHelper::divider();

	JToolBarHelper::custom('massadd','new.png','new_f2.png',JText::_('JL_ADMIN_MATCHES_MASSADD_MATCHES'),false);
	JToolBarHelper::addNewX('addmatch',JText::_('JL_ADMIN_MATCHES_MASSADD_ADD_MATCH'));
	JToolBarHelper::deleteList(JText::_('JL_ADMIN_MATCHES_MASSADD_WARNING'));
	JToolBarHelper::divider();

	JToolBarHelper::back('Back','index.php?option=com_joomleague&view=rounds&controller=round');
}
else
{
	JToolBarHelper::custom('cancelmassadd','cancel.png','cancel_f2.png',JText::_('JL_ADMIN_MATCHES_MASSADD_CANCEL_MATCHADD'),false);
}
JToolBarHelper::help('screen.joomleague',true);

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<script type="text/javascript">
<!--
	window.addEvent('domready',function()
	{
		$$('tr.row-result').each(function(row)
		{
			var matchid=row.id.substr(7);
			var cb=row.getElement('input[id^=cb]');
			if (cb)
			{
				// item is not checked out
				row.getElements('select').addEvent('change',function() { cb.checked=true; });

				row.getElements('input').addEvent('change',function() { if (this.id != cb.id) { cb.checked=true; } });

				//special for calendar
				// row.getElements('.calendar').addEvent('click',function() { cb.checked=true; });

				//special for roster selection
				row.getElements('select[id^=team]').addEvent('change',function()
				{
					// handles the link ref for starting lineup window
					var matchid=this.id.substr(10);
					$E('a.openroster-'+this.id).href="index.php?option=com_joomleague&tmpl=component&controller=match&task=editlineup&cid[]="+matchid+"&team="+this.value;
				});
			}
			else
			{
				//item is checked out
			}
			//alert(matchid);
			// we should replace all the inline 'onchange' with code here.

		});
	});

	function switchMenu(obj)
	{
		var el=document.getElementById(obj);
		if (el.style.display != "none")
		{
			el.style.display='none';
		}
		else
		{
			el.style.display='block';
		}
	}

	function copymatches ()
	{
		document.getElementById('addtype').value=2;
		document.copyform.submit();
		return true;
	}

	function addmatches ()
	{
		document.getElementById('addmatchescount').value=document.getElementById('tempaddmatchescount').value;
		document.getElementById('addtype').value=1;
		return true;
	}

	function displayTypeView()
	{
		if (document.getElementById('ct').value==0)
		{
			document.getElementById('massadd_standard').style.display='none';
			document.getElementById('massadd_type2').style.display='none';
		}
		else if (document.getElementById('ct').value==1)
		{
			document.getElementById('massadd_standard').style.display='block';
			document.getElementById('massadd_type2').style.display='none';
		}
		else if (document.getElementById('ct').value==2)
		{
			document.getElementById('massadd_standard').style.display='none';
			document.getElementById('massadd_type2').style.display='block';
		}
	}
	
	function SaveMatch(a,b)
	{
		var f = document.matrixForm;
		if(f)
		{  
			f.elements['projectteam1_id'].value = a;  
			f.elements['projectteam2_id'].value = b;  
			f.submit();  
		}  
	}	
//-->
</script>
<style>
.subsequentdecision {
	background-color: #BBB;
}
</style>
<div id="alt_decision_enter" style="display:<?php echo ($massadd == 0) ? 'none' : 'block'; ?>">
<?php echo $this->loadTemplate('massadd'); ?>
</div>
<?php echo $this->loadTemplate('matches'); ?>	
<?php echo $this->loadTemplate('matrix'); ?>
