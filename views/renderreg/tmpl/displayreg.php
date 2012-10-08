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

$member_data = $this->member_data;
$reg_details = $member_data->reg_details;

$page_title = (intval($reg_details->member_id) > 0)?ucwords($reg_details->fullname):"No Profile";

$member_params = $this->all_headings["member_params"];
ClubMenuHelper::generate_menu_tabs($member_params,$page_title );

$lists = $this->lists;

$last_update = $this->last_update;

$colon = ":";
$tab_id = isset($reg_details->member_id)?$reg_details->member_id:0;
 
$cookie_key = 'rpl_tabs_'.$tab_id;
$pane_offset = isset($_COOKIE[$cookie_key])?$_COOKIE[$cookie_key]:0;
 
jimport('joomla.html.pane');
$pane	= &JPane::getInstance('tabs', array('useCookies'=>true,'startOffset'=>$pane_offset));
?>
<div class="top_buttons">
<div class="left_buttons"><input class="button" name='back_' id="back_url" type="button" onclick="document.location='<?php echo $this->back_url; ?>'"  value='<?php echo JText::_('Back To List'); ?>' /></div>
<?php $edit_url = $this->edit_url; ClubHiddenHelper::renderButtons($edit_url); ?>
</div><p class="cl"></p>
<?php 
$title = JText::_(PLAYER.' Details');
$title = tryUseCookies($title ,0,$tab_id);

echo $pane->startPane("player-pane");
echo $pane->startPanel($title, "detail-page1");
?>
<div class="h3"><?php echo $reg_details->playertype; ?> <?= PLAYER ?> Details</div>
<div class="fieldset">
	<?php if(in_array($reg_details->playertype, array("senior"))){ 
		$show_key = array("emailaddress"=>"Email Address","mobile"=>"Mobile #",
				"phoneno"=>"Phone #","faddress"=>"Address"); // guardian
	?>
	<div class="n">
		<div class="taghd">Contact Details</div>
		<div class="div_table">
		<table class="reg_details" >		
		<?php foreach($show_key as $tkey => $t_value){?>
			<tr>
				<td class="render_label" ><?php echo ucwords($show_key[$tkey]); ?></td><td class="reg_colon"><?php echo $colon ;?></td><td><?php echo isset($reg_details->$tkey)?trim($reg_details->$tkey):""; ?></td>
			</tr>
		<?php } ?>
		</table>
		</div>
	
	</div>

	<?php } ?>
	<div class="n">
	<div class="taghd"><?php echo GROUP; ?> Details</div>
		<div class="div_table">
			<table class="reg_details" >
				<tr>	
					<td class="render_label" ><?php echo GROUP; ?></td><td class="reg_colon"><?php echo $colon ;?></td><td><?php echo $reg_details->group_name ?></td>
				</tr>
				<tr>
					<td class="render_label" ><?php echo SUBGROUP;?></td><td class="reg_colon"><?php echo $colon ;?></td><td><?php echo $reg_details->s_group_name ?></td>
				</tr>
				<tr>
					<td class="render_label" ><?php echo SEASON; ?></td><td class="reg_colon"><?php echo $colon ;?></td><td ><?php echo $reg_details->year_registered ?></td>
				</tr>
		</table>
		</div>
	</div>	
	<p class="cl"></p>

<?php 
	if($this->parent_details){ $show_key = array("fullname"=>"Guardian Name","emailaddress"=>"Email Address","mobile"=>"Mobile #",
				"phoneno"=>"Phone #","faddress"=>"Address","reg_created"=>"Created On"); // guardian?>
	<div class="n" >	
		<div class="taghd">Guardian Details</div>		
		<div class="div_table">
		<table class="reg_details" >		
		<?php foreach($show_key as $tkey => $t_value){?>
			<tr>
				<td class="render_label" ><?php echo ucwords($show_key[$tkey]); ?></td><td class="reg_colon"><?php echo $colon ;?></td><td><?php echo isset($this->parent_details->$tkey)?trim($this->parent_details->$tkey):""; ?></td>
			</tr>
		<?php } ?>
		</table>
		</div>
	</div>
	<?php 
	}
	?>
	<div class="taghd"><?php echo EMERGENCY; ?> Details</div>
		<?php 	
		$this->contact_details->contact_key = "em_" ;
		echo $this->loadTemplate("emcontact");
?>
</div>
<?php 
echo $pane->endPanel();
echo $pane->endPane();

ClubregHelper::write_footer(); ?>
