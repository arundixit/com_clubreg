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

global $colon,$Itemid,$option;
$document =& JFactory::getDocument();

$in_type = "hidden";
$page_title = "Manage Communications";
$document->setTitle($page_title );

$member_params = $this->all_headings["member_params"];
$filter_heading = $this->all_headings["filters"];
ClubMenuHelper::generate_menu_tabs($member_params,$page_title );
$template_list_text = $this->templates->template_list_text;
$headings = $this->all_headings["headings"];
?>
<form action="index.php?option=<?= $option; ?>&Itemid=<?=$Itemid ?>" method="post" name="adminForm">
<table width=100%>
	<tr>		
		<td style="vertical-align:top">
			<input type="submit" value="Filter" name="bt_search" onclick='this.form.task.value="listcomms"' class="button">	
			<input type="submit" value="Delete Comm" name="bt_delete" onclick='this.form.task.value="deletecomms"' class="button">
		</td>	
	<td style="text-align:right;vertical-align:top">
	<div  id="subNav">
		<ol>			
			<li class="last"><a href="javascript:void(0)" id="newMessages">Send New Messages</a><br />
			<?php echo $template_list_text; ?>
			</li>	
		</ol>
	</div>	
	</td>
	</tr>
</table>

		<?php 
			clubTables::renderTables_comms($this->all_results,$this->all_headings);
		?>
		<input type="<?= $in_type; ?>" name="option" id="option" value="<?= $option;?>" />		
		<input type="<?= $in_type; ?>" name="Itemid" id="Itemid" value="<?= $Itemid;?>" />				
		<input type="<?= $in_type; ?>" name="c" value="comms" />
		<input type="<?= $in_type; ?>" name="task" value="listcomms" id="task"/>		
		<input type="<?= $in_type; ?>" name="boxchecked" id="boxchecked" value="0" />
		<input type="<?= $in_type; ?>" name="filter_order" value="<?= $this->all_headings['filter_order']; ?>" />
		<input type="<?= $in_type; ?>" name="filter_order_Dir" value="<?= $this->all_headings['filter_order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>		
</form>