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
$page_title = (intval($member_data->member_id) > 0)?($member_data->surname." ".$member_data->givenname):"Registering New Details";

$member_params = $this->all_headings["member_params"];
ClubregHelper::generate_menu_tabs($member_params,$page_title );

JHTML::_('behavior.formvalidation');
JHTML::_('behavior.calendar');

$append = '';
$in_type = "hidden";
global $option,$Itemid;
$document =& JFactory::getDocument();
$document->setTitle($page_title );

$colon = "<span><b>:</b>&nbsp;&nbsp;&nbsp;</span>";

ob_start();
?>
	function simpleValidate(){
			$('normal_save').addEvent('click',function(){
					f = this.form;
				 if (document.formvalidator.isValid(f)) {
				 	f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
     	 			return true;				 
				 }else{			
					var msg = 'Some values are not acceptable.  Please retry.';	
					if($('g_surname').hasClass('invalid')){msg += '\n\t* Invalid Surname';}
					if($('g_givenname').hasClass('invalid')){msg += '\n\t* Invalid <?php echo GIVENNAME; ?>';}
					if($('g_emailaddress').hasClass('invalid')){msg += '\n\t* Invalid Email';}
					
					alert(msg);
				}		
				return false;
			});	
	};
	Window.onDomReady(simpleValidate);
<?php 
		$t_script = ob_get_contents();
		ob_end_clean();
	 	$document->addScriptDeclaration($t_script);

?>
<form action="index.php" method="post" name="adminForm"   id="adminForm"  class="form-validate gud">
<div class="top_buttons">
<div class="left_buttons"><input class="button" name='back_' id="back_url" type="button" onclick="document.location='<?php echo $this->back_url; ?>'"  value='<?php echo JText::_('Back To List'); ?>' /></div>
<?php $edit_url = $this->edit_url; ClubHiddenHelper::renderButtons($edit_url); ?>
</div><p class="cl"></p>
<div class="h3">Parent - Guardian Details</div>	
<div class="fieldset">
	<div class="tset">
		<label class="lbcls" for="g_surname">Surname<span class="isReq">*</span></label><?php echo $colon;?><input type="text" class="intext required" name="g_surname" id="g_surname" value="<?php echo $member_data->surname;	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_givenname"><?php echo GIVENNAME; ?><span class="isReq">*</span></label><?php echo $colon;?><input type="text" class="intext required" name="g_givenname" id="g_givenname" value="<?php echo $member_data->givenname;	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_emailaddress">Email-Address<span class="isReq">*</span></label><?php echo $colon;?><input type="text"  class="intext required validate-email" name="g_emailaddress" id="g_emailaddress" value="<?php echo $member_data->emailaddress;?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_address">Address</label><?php echo $colon;?><input class="intext" type="text" name="g_address" id="g_address" value="<?php echo $member_data->address;?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_suburb">Suburb / Town</label><?php echo $colon;?><input type="text"  class="intext" name="g_suburb" id="g_suburb" value="<?php echo $member_data->suburb;?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_postcode">Postcode</label><?php echo $colon;?><input type="text" class="intext half" name="g_postcode" id="g_postcode" value="<?php echo $member_data->postcode;?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_phoneno">Phone</label><?php echo $colon;?><input type="text"  class="intext" name="g_phoneno" id="g_phoneno" value="<?php echo $member_data->phoneno;?>"/>
	</div>
	<div class="tset">	
		<label class="lbcls" for="g_mobile">Mobile</label><?php echo $colon;?><input type="text"  class="intext" style="width:150px;" name="g_mobile" id="g_mobile" value="<?php echo $member_data->mobile;?>"/>
	</div>
	<div class="tset">
		<?php  $t_sendnews = ($member_data->send_news != -1)?"checked":""; ?>
		<input type="checkbox" name="g_send_news" id="g_send_news" value="send" <?php echo $t_sendnews; ?>/>Do not send me promotional emails
	</div><p class="cl"></p>
		<?php 	ClubregHelper::renderLastUpdate($this->last_update); ?>	
</div>
<p class="cl"></p>
<?php  $div_counter = 2;
$group_data 	=& JModel::getInstance('groups', 'ClubRegModel');
if(count($this->lists["children"]) > 0){	
	$children = $this->lists["children"];
	$format = '%d/%m/%Y';
	foreach($children as $a_child){ $j = $a_child->member_id; ?>
	<div class="h3"><?php echo $a_child->givenname ," ",$a_child->surname ;?> Details </div>
	<div class="fieldset">	
		<div class="tset">
			<label class="lbcls" for="r_surname[player_<?php echo $j; ?>]">Surname</label><?php echo $colon;?>
				<input type="text" class="intext" name="r_surname[player_<?php echo $j; ?>]" value="<?php echo $a_child->surname ;?>"/>
		</div>
		<div class="tset">		
			<label class="lbcls" for="r_givenname[player_<?php echo $j; ?>]"><?php echo GIVENNAME; ?></label><?php echo $colon;?>
				<input type="text" class="intext" name="r_givenname[player_<?php echo $j; ?>]" value="<?php echo $a_child->givenname;?>"/>
		</div>
		<div class="tset">
			<label class="lbcls" for="r_dob[player_<?php echo $j; ?>]">Date Of Birth</label><?php echo $colon;?>
				<?php 	
					$name = "r_dob[player_". $j."]"; $id= "r_dob_player_".$j;					
					$value_ = explode("-",$a_child->dob);					
					if(count($value_) == 3 && !preg_match("/0000-00-00/",$a_child->dob)){
						$value = sprintf("%s/%s/%s",$value_[2],$value_[1],$value_[0]);
					}else{ 
						$value ="";
					}				
					echo JHTML::_('calendar', $value, $name, $id, $format, array('class' => 'intext','style'=>'width:80px;')); 
				?>			
		</div>
		<div class="tset">
			<label class="lbcls" for="r_gender[player_<?php echo $j; ?>]" >Gender </label><?php echo $colon ;?>
			<?php $t_gender = $a_child->gender; ?>
				<input type="radio" name="r_gender[player_<?php echo $j; ?>]" value="male" <?php echo ($t_gender == "male")?"checked":""; ?> />Male <input type="radio" name="r_gender[player_<?php echo $j; ?>]" value="female" <?php echo ($t_gender == "female")?"checked":""; ?>/>Female 
				<input type="<?php echo $in_type?>" name="junior_id[]" value="<?php echo $j; ?>">
		</div><p class="cl"></p>
		<div class="tset">
			<label class="lbcls" for="r_group[player_<?php echo $j; ?>]"><?php echo GROUP; ?></label><?php echo $colon;?>&nbsp;<?php
			$name = "r_group[player_". $j."]";	$id= "r_group_player_".$j;	$value = $a_child->group;
			echo JHTML::_('select.genericlist',  $this->lists['current_groups'], $name, 'class="group_select intext" id="'.$id.'"  size="1" ', 'value', 'text', $value);?>
		</div>
		<div class="tset">
			<label class="lbcls" for="g_subgroup"><?php echo SUBGROUP?></label><?php echo $colon ; echo "&nbsp;";
				$value = $a_child->subgroup;	$name = "r_subgroup[player_". $j."]";	$id= "r_subgroupplayer_".$j;
				$subgroups =  $group_data->load_subgrougs($a_child->group); // get the subgroups of the current group
				echo JHTML::_('select.genericlist',  $subgroups, $name, 'class="intext" id="'.$id.'"  size="1" ', 'value', 'text', $value);	?>
		</div>
		<div class="tset">
			<label class="lbcls" for="r_year_registered[player_<?php echo $j; ?>]">Season</label><?php echo $colon ;?>
				<?php 	$name = "r_year_registered[player_". $j."]"; $id= "r_year_registered_".$j;	$t_value = ($a_child->year_registered)?$a_child->year_registered:date('Y');
					echo JHTML::_('select.genericlist',  $this->lists['year_registered_list'], $name, 'class="intext half" id="'.$id.'"  size="1" '.$t_prop, 'value', 'text', $t_value);
				?>
		</div>
		<p class="cl"></p>	
		<div class="tset">
		<span class="spn_tag">
		<?php $edit_url = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%s",$option,$Itemid,$a_child->member_id); ?>
		<a href="<?php echo $edit_url; ?>" target="_blank">Edit Details</a></span></div>
		<p class="cl"></p>
	</div>
	
	<?php 		
	}
}
$j = 0;
$t_request['p_surname'] = JRequest::getVar('p_surname', array(), 'post', 'array' );
$t_request['p_givenname'] = JRequest::getVar('p_givenname', array(), 'post', 'array' );
$t_request['p_dob'] = JRequest::getVar('p_dob', array(), 'post', 'array' );
$t_request['p_group'] = JRequest::getVar('p_group', array(), 'post', 'array' );
$t_request['p_gender'] = JRequest::getVar('p_gender', array(), 'post', 'array' );
$t_request['p_year_registered'] = JRequest::getVar('p_year_registered', array(), 'post', 'array' );

$t_request['p_surname'] = $t_request['p_givenname'] = $t_request['p_dob'] = $t_request['p_group'] = $t_request['p_gender'] = $t_request['p_year_registered'] =  array();

for($i = 1 ; $i <= $div_counter; $i++,$j++){
	$t_index = "player_".$j;
?>
<input type="<?php echo $in_type; ?>" name="div_counter" value="<?php echo $div_counter; ?>" />
<div class="h3">Junior <?php echo PLAYER ;?> Details <?php echo $i;?></div>
<div class="fieldset">
	<div class="tset">
		<label class="lbcls" for="p_surname[player_<?php echo $j; ?>]">Surname</label><?php echo $colon;?>	
			<input type="text" class="intext" name="p_surname[player_<?php echo $j; ?>]" value="<?php echo JArrayHelper::getValue($t_request['p_surname'], $t_index, '', 'string' );?>"/>
	</div>
	<div class="tset">		
		<label class="lbcls" for="p_givenname[player_<?php echo $j; ?>]"><?php echo GIVENNAME; ?></label><?php echo $colon;?>
			<input type="text" class="intext" name="p_givenname[player_<?php echo $j; ?>]" value="<?php echo JArrayHelper::getValue($t_request['p_givenname'], $t_index, '', 'string' );?>"/>
	</div>
	<div class="tset">			
		<label class="lbcls" for="p_dob[player_<?php echo $j; ?>]">Date Of Birth</label><?php echo $colon;?>
			<?php 
				$format = '%d/%m/%Y';
				$name = "p_dob[player_". $j."]";
				$id= "p_dob_player_".$j;
				$value = JArrayHelper::getValue($t_request['p_dob'], $t_index, '', 'string' );
				echo JHTML::_('calendar', $value, $name, $id, $format, array('class' => 'intext','style'=>'width:80px;')); 
			?>	
		
	</div>
	<div class="tset">	
		<label class="lbcls" for="p_gender[player_<?php echo $j; ?>]" >Gender</label><?php echo $colon;?>
		<?php 
			$t_gender = JArrayHelper::getValue($t_request['p_gender'], $t_index, '', 'string' );?>
			<input type="radio" name="p_gender[player_<?php echo $j; ?>]" value="male" <?php echo ($t_gender == "male")?"checked":""; ?> />Male <input type="radio" name="p_gender[player_<?php echo $j; ?>]" value="female" <?php echo ($t_gender == "female")?"checked":""; ?>/>Female
	</div>	
	<p class="cl"></p>
	<div class="tset">		
		<label class="lbcls" for="p_group[player_<?php echo $j; ?>]"><?php echo GROUP; ?></label><?php echo $colon;?>&nbsp;<?php
		$name = "p_group[player_". $j."]";	$id= "p_group_player_".$j;	$value = JArrayHelper::getValue($t_request['p_group'], $t_index, '', 'string' );
		echo JHTML::_('select.genericlist',  $this->lists['current_groups'], $name, 'class="group_select intext" id="'.$id.'"  size="1" ', 'value', 'text', $value);
		?>
	</div>
	<div class="tset">	
		<label class="lbcls" for="p_subgroup"><?php echo SUBGROUP; ?></label><?php echo $colon ;	echo "&nbsp;";			
				$value = -1; $name = "p_subgroup[player_". $j."]";	$id= "p_subgroupplayer_".$j;
				$subgroups =  $group_data->load_subgrougs(0); // get the subgroups of the current group						
				echo JHTML::_('select.genericlist',  $subgroups, $name, 'class="intext" id="'.$id.'"  size="1" ', 'value', 'text', $value);	?>		
	</div>
	<div class="tset">			
		<label class="lbcls" for="p_year_registered[player_<?php echo $j; ?>]">Season</label><?php echo $colon ;?>
				<?php					
					$name = "p_year_registered[player_". $j."]";$id= "p_year_registered_".$j;$value = JArrayHelper::getValue($t_request['p_year_registered'], $t_index, '', 'string' );
					echo JHTML::_('select.genericlist',  $this->lists['year_registered_list'], $name, 'class="intext half" id="'.$id.'"  size="1" ', 'value', 'text', $value);
				?>
	</div>	
	<p class="cl"></p>	
</div>
<br />
<?php  } ?>
<div class=center>
<input class="button" name='back_' id="back_url" type="button" onclick="document.location='<?php echo $this->back_url; ?>'"  value='<?php echo JText::_('Back To List'); ?>' />
<input class="button validate" name='normal_save' id="normal_save" type="submit"  value='<?php echo JText::_('Update Details'); ?>' />
</div>
	
	<input type="<?= $in_type ?>" name="member_id" value="<?php echo $member_data->member_id; ?>" />	
	<input type="<?= $in_type ?>" name="g_playertype" value="guardian" />	
	<input type="<?= $in_type ?>" name="option" id="option" value="<?php echo $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" id="Itemid" value="<?php echo $Itemid ?>" />
	<input type="<?= $in_type ?>" name="task" value="save_details" />
	<input type="<?= $in_type ?>" name="c" value="userreg" />
	<input type="<?= $in_type ?>" name="ordinal" value="<?php echo $this->ordinal; ?>" />
	<input type="<?= $in_type ?>" name="check" value="check" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
//button
ClubregHelper::write_footer();