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
$page_title = "Manage ".STATS;
$document->setTitle($page_title );

$member_params = $this->all_headings["member_params"];
ClubMenuHelper::generate_menu_tabs($member_params,$page_title );

ob_start();	
?>
 var token_value ='<?php echo JUtility::getToken(); ?>'; //send token
<?php 
$t_script = ob_get_contents();
ob_end_clean();
$document->addScriptDeclaration($t_script);

$all_headings = $this->all_headings;
$switcher_key =  $all_headings["return_data"]["jaykenzo"];
?>
<form action="index.php?option=<?= $option; ?>&Itemid=<?=$Itemid ?>" method="post" name="adminForm">
<table>
<tr>
<td>
	<?php echo JHTML::_('select.genericlist',  $this->playertype_list, 'playertype', 'class="inputbox" onchange="simple_reset();"', 'value', 'text', $this->all_headings["return_data"]['playertype']);?>
</td>
<td><label for="stats_date"><?php echo STATS; ?> Date</label> :</td>
<td><?php 
		$format = '%d/%m/%Y';$ctrlname ="stats_date";
		echo JHTML::_('calendar', $this->all_headings["return_data"]["stats_date"], $ctrlname, $ctrlname, $format, array('class' => 'intext calendarHalf','readonly'=>'readonly'));
	?>
<input type="submit" value="<?php echo $all_headings["switcher"][$switcher_key][1]; ?> " name="bt_search" onclick='this.form.jaykenzo.value="<?php echo $all_headings["switcher"][$switcher_key][0]; ?>"' class="button" />
<input type="submit" value="Filter" name="bt_search" onclick='this.form.task.value="liststats"' class="button" />
</td>
</tr>
</table>
<?php
clubTables::renderfilters($this->all_headings);
echo $this->loadTemplate('statstable');
?>
<input type="<?= $in_type; ?>" name="option" id="option" value="<?= $option;?>" />
<input type="<?= $in_type; ?>" name="Itemid" id="Itemid" value="<?= $Itemid;?>" />
<input type="<?= $in_type; ?>" name="c" value="stats" />
<input type="<?= $in_type; ?>" name="task" value="liststats" id="task"/>
<input type="<?= $in_type; ?>" name="jaykenzo" value="<?php echo $switcher_key; ?>" id="jaykenzo"/>
<input type="<?= $in_type; ?>" name="boxchecked" id="boxchecked" value="0" />
<input type="<?= $in_type; ?>" name="filter_order" value="<?= $this->all_headings['filter_order']; ?>" />
<input type="<?= $in_type; ?>" name="filter_order_Dir" value="<?= $this->all_headings['filter_order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>