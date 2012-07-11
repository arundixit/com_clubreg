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
global $Itemid,$option,$colon;
$in_type = "hidden";

$extra_details_values = $this->contact_details->contact_details;
?>
<form action="index2.php" method="post" name="adminFormExtradetails"   id="adminFormExtradetails"  class="form-validate" target="_extraframe" onsubmit="return renderRotating();">
<div class="h3">Extra <?= PLAYER ?> Details</div>	
<div class="fieldset">
<?php $extra_details = $this->extra_details; 
	foreach($extra_details as $a_detail){
		
		
		
		$control_params = new JParameter( $a_detail->params );
		$t_style = $control_params->get("control_width");
		if(preg_match("/px/",$t_style)){
			$t_style =" style='".$t_style."'";
		}else{
			$t_style =" class='".$t_style."'";
		}	
		
		$control_name = "extra_".$a_detail->config_short;
		
		
		$Objectvalue = isset($extra_details_values[$control_name])?$extra_details_values[$control_name]->contact_value:"";
		
		
		$control_id = $a_detail->config_short;		
		?>
		<div class="n">
			<label class="lbcls" for="<?php echo $a_detail->config_short; ?>"><?php echo $a_detail->config_name ?></label><?php echo $colon;?>
			<?php 
				switch($control_params->get("control_type")){
					
					case "select":						
						$t_values = explode("\n",$a_detail->config_text);
						$t_array= array(); 
						$t_object = new stdClass() ; $t_object->value = '-1'; $t_object->text = '- Select- ';$t_array[] = $t_object;
						foreach($t_values as $a_value){
							$a_value = trim($a_value); 
							
							if(strlen($a_value) > 0){
								$t_object = new stdClass() ; $t_object->value = $a_value; $t_object->text = ucwords($a_value);
								$t_array[] = $t_object;
							}
						}										
						echo JHTML::_('select.genericlist',  $t_array, $control_name, $t_style.' id="'.$control_id.'"  size="1" ', 'value', 'text', $Objectvalue);							
					break;
					case "monthyear":						
						$t_array = getMonths();$t_style ="class='intext half'";$tcontrol_name = $control_name."_month"; $tcontrol_id = $control_id."_month";
						$Objectvalue = isset($extra_details_values[$tcontrol_name])?$extra_details_values[$tcontrol_name]->contact_value:"";						
						echo JHTML::_('select.genericlist',  $t_array, $tcontrol_name, $t_style.' id="'.$tcontrol_id.'"  size="1" ', 'value', 'text', $Objectvalue); 
						$tcontrol_name = $control_name."_year"; $tcontrol_id = $control_id."_year";
						$Objectvalue = isset($extra_details_values[$tcontrol_name])?$extra_details_values[$tcontrol_name]->contact_value:"";?>					
						<input type="text" <?php echo $t_style ;?> name="<?php echo $tcontrol_name?>" id="<?php echo $tcontrol_id; ?>" value="<?php echo $Objectvalue; ?>"/><?php
					break;
					case "date":						
						$format = '%d/%m/%Y';						
						echo JHTML::_('calendar', $Objectvalue, $control_name, $control_id, $format, array('class' => 'intext half','readonly'=>'readonly'));						
					break;
					case "checkbox":
						
					break;
					case "email":
						?><input type="text" <?php echo $t_style; ?> name="<?php echo $control_name?>" id="<?php echo $control_id?>" value="<?php echo $Objectvalue; ?>"/><?php
					break;
					case "file":
							
					break;
					case"text":
						?><input type="text" <?php echo $t_style; ?> name="<?php echo $control_name?>" id="<?php echo $control_name?>" value="<?php echo $Objectvalue; ?>"/><?php 
					break;
					case "textarea":
						?><textarea rows="10"  <?php echo $t_style; ?> name="<?php echo $control_name?>" id="<?php echo $control_name?>"><?php echo $Objectvalue; ?></textarea><?php 
					break;
					
				}
			
			?>		
		</div>
		
		<?php 
		
	}


?>


</div>
<div  id="adminFormExtradetails_span" name="adminFormExtradetails_span" style="float:right"></div><p class="cl"></p>
<div class="center">	
	<input class="button validate" name='normal_save' id="normal_save" type="submit"  value='<?php echo JText::_("Update Details"); ?>' />
</div>
	<input type="<?= $in_type ?>" name="member_id" value="<?php echo $member_data->member_id; ?>" />	
	<input type="<?= $in_type ?>" name="g_playertype" value="<?php echo $member_data->playertype;?>" />	
	<input type="<?= $in_type ?>" name="option" id="option" value="<?php echo $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" id="Itemid" value="<?php echo $Itemid ?>" />
	<input type="<?= $in_type ?>" name="task" value="save_extradetails" />	
	<input type="<?= $in_type ?>" name="c" value="userreg" />
	<input type="<?= $in_type ?>" name="no_html" value="1" />
	<input type="<?= $in_type ?>" name="ordinal" value="<?php echo $this->ordinal; ?>" />	
	<?php echo JHTML::_( 'form.token' ); 
?>
</form>
<iframe name="_extraframe" id="_extraframe" style="width:100%" height="1" frameborder=0></iframe>
<?php
