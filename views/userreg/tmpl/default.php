<?php 
/*------------------------------------------------------------------------
# com_clubreg - Manage Club Member Registrations
# ------------------------------------------------------------------------
# author    Omokhoa Agbagbara
# copyright Copyright (C) 2012 applications.deltastateonline.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://applications.deltastateonline.com
# Technical Support:  email - joomla@deltastateonline.com
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

global $Itemid,$option;
$document =& JFactory::getDocument();

$in_type = "hidden";
$page_title = "Registered ".PLAYERS;
$document->setTitle($page_title );

$member_params = $this->all_headings["member_params"];
$filter_heading = $this->all_headings["filters"];
ClubregHelper::generate_menu_tabs($member_params,$page_title );
ob_start();
?>
	function simple_reset(){
	
		if($('filter_group')){
			$('filter_group').selectedIndex  = 0;
		}
		if($('filter_gender')){
			$('filter_gender').selectedIndex  = 0;
		}
		if($('filter_sgroup')){
			$('filter_sgroup').selectedIndex  = 0;
		}
			
	};
	function confirmdelete(d_button){
		if(confirm('Are You sure You wish to Proceed??')){
			d_button.form.task.value="deletereg";
		}
	}
<?php 
		$t_script = ob_get_contents();
		ob_end_clean();
	 	$document->addScriptDeclaration($t_script);
	 	

		 
	 	if(in_array($this->all_headings["return_data"]["playertype"], array("junior","senior"))){
	 		$show_batch_update = true;
	 	}else{	
	 		$show_batch_update = false;
	 	}
?>
<div class="overflow">
<form action="index.php?option=<?= $option; ?>&Itemid=<?=$Itemid ?>" method="post" name="adminForm">
<table width=100%>
	<tr>		
		<td valign="top">
			<?php echo JHTML::_('select.genericlist',  $this->playertype_list, 'playertype', 'class="inputbox" onchange="simple_reset();"', 'value', 'text', $this->all_headings["return_data"]['playertype']);?>
		<input type="submit" name="bt_search" value="Filter" class="button"/>			
		<?php 
		if($member_params->get('deletereg' ) == "yes"){ ?>
		<input type="submit" name="bt_search" value="Delete" class="button" onclick='confirmdelete(this);'/></td>		
		<?php } ?>
		<td valign="top"><?php echo JHTML::_('select.genericlist',  $this->vtype_list, 'vtype', 'class="inputbox"  onchange="this.form.submit();"  ', 'value', 'text', $this->all_headings["return_data"]['vtype']);?></td>
		<td valign="top">
		<div id="subNav">
			<ol >
				<?php if($show_batch_update) { // only batch update junior or senior player details and not guardian?>
				<li><a href="javascript:void(0)" id="batch_updater"><span>Batch Update</span></a></li>
				<?php } ?>
				<li><a href="index.php?option=<?php echo $option; ?>&c=userreg&task=editreg&Itemid=<?php echo $Itemid; ?>&member_id=0&playertype=guardian" style="font-weight:normal"><span>Register Guardian</span></a></li>
				<li><a href="index.php?option=<?php echo $option; ?>&c=userreg&task=editreg&Itemid=<?php echo $Itemid; ?>&member_id=0&playertype=junior" style="font-weight:normal"><span>Register Junior <?php echo PLAYER; ?></span></a></li>
				<li class="last"><a href="index.php?option=<?php echo $option; ?>&c=userreg&task=editreg&Itemid=<?php echo $Itemid; ?>&member_id=0&playertype=senior" style="font-weight:normal"><span>Register Senior <?php echo PLAYER; ?></span></a></li>
			</ol>
		</div>
		</td>
	</tr>
</table>	
<?php if($show_batch_update) { 
	$filter_value = null; ?>
<div id="update_table">
<table class="updatetbl shading" width=100% cellpadding=0 cellspacing=0>
	<tr>
	<td style="padding-left:5px"><label for="" class="fltlbl"><?php echo PLAYER ?> Level</label><br />
	<?php echo JHTML::_('select.genericlist',  $filter_heading["memberlevel"]["values"], "update_memberlevel", 'class="inputbox" id="update_memberlevel"', 'value', 'text', $filter_value); ?>
	</td>
	<td style="padding-left:5px"><label for="" class="fltlbl"><?php echo GROUPS ?></label><br />
	<?php echo JHTML::_('select.genericlist',  $filter_heading["group"]["values"], "update_group", 'class="inputbox" id="update_group"', 'value', 'text', $filter_value); ?>
	</td>
	<td><label for="" class="fltlbl"><?php echo SUBGROUPS ?></label><br />
	<?php echo JHTML::_('select.genericlist',  $filter_heading["sgroup"]["values"], "update_sgroup", 'class="inputbox" id="update_sgroup"', 'value', 'text', $filter_value); ?>
	</td>
	<td><label for="" class="fltlbl"><?php echo SEASON ?></label><br />
	<?php echo JHTML::_('select.genericlist',  $filter_heading["year_registered"]["values"], "update_season", 'class="inputbox" id="update_season"', 'value', 'text', $filter_value); ?>
	</td>
	<td><label for="" class="fltlbl"><?php echo "Gender" ?></label><br />
	<?php echo JHTML::_('select.genericlist',  $filter_heading["gender"]["values"], "update_gender", 'class="inputbox" id="update_gender"', 'value', 'text', $filter_value); ?>
	</td>
	<td style="padding-bottom:5px"><br /><input type="submit" value="Batch Update" class="button" id="bt_update" name="bt_update"/></td>
	</tr>
</table>

</div>
<?php } ?>
	<?php 
			if($this->all_headings["return_data"]['vtype'] == "detail"){ 
				clubTables::renderTables_divs($this->all_results,$this->all_headings); 
			}else{
				clubTables::renderTables($this->all_results,$this->all_headings); 
			}
	
		 ?>	
		<input type="<?= $in_type; ?>" name="option" id="option" value="<?= $option;?>" />		
		<input type="<?= $in_type; ?>" name="view" value="userreg" />
		<input type="<?= $in_type; ?>" name="Itemid" id="Itemid" value="<?= $Itemid;?>" />				
		<input type="<?= $in_type; ?>" name="c" value="userreg" />
		<input type="<?= $in_type; ?>" name="task" value="loadregistered" id="task"/>		
		<input type="<?= $in_type; ?>" name="boxchecked" id="boxchecked" value="0" />
		<input type="<?= $in_type; ?>" name="filter_order" value="<?= $this->all_headings['filter_order']; ?>" />
		<input type="<?= $in_type; ?>" name="filter_order_Dir" value="<?= $this->all_headings['filter_order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>		
</form>
</div>
<?php ClubregHelper::write_footer(); ?>		