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

$in_type = "hidden";
$page_title = "Submitted Expression of Interests";
$member_params = $this->all_headings["member_params"];

ClubregHelper::generate_menu_tabs($member_params,$page_title );
?>
<form action="index.php?option=<?= $option; ?>&Itemid=<?=$Itemid ?>" method="post" name="adminForm">
<table>
	<tr>
		<td><?php echo JHTML::_('select.genericlist',  $this->playertype_list, 'playertype', 'class="inputbox"', 'value', 'text', $this->all_headings["return_data"]['playertype']);?></td>
		<td><input type="submit" name="bt_search" value="Filter" class="button"/></td>
		<?php if($member_params->get('registereoi' ) == "yes" ){?>
		<td><input type="submit" name="bt_search" value="Register" class="button" onclick='this.form.task.value="registereoi"'/></td>
		<?php } 
		if($member_params->get('deleteeoi' ) == "yes"){?>
		<td><input type="submit" name="bt_search" value="Delete" class="button" onclick='this.form.task.value="deleteeoi"'/></td>
		
		<?php } ?>
	</tr>
</table>	
	<?php clubTables::renderTables($this->all_results,$this->all_headings); ?>	
		<input type="<?= $in_type; ?>" name="option" value="<?= $option;?>" />		
		<input type="<?= $in_type; ?>" name="view" value="eoi" />
		<input type="<?= $in_type; ?>" name="Itemid" value="<?= $Itemid;?>" />		
		<input type="<?= $in_type; ?>" name="layout" value="loadeoi" />
		<input type="<?= $in_type; ?>" name="c" value="eoi" />
		<input type="<?= $in_type; ?>" name="task" value="loadeoi" />		
		<input type="<?= $in_type; ?>" name="boxchecked" value="0" />
		<input type="<?= $in_type; ?>" name="filter_order" value="<?= $this->all_headings['filter_order']; ?>" />
		<input type="<?= $in_type; ?>" name="filter_order_Dir" value="<?= $this->all_headings['filter_order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>		
</form>
<?php ClubregHelper::write_footer(); ?>		