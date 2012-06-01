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
JHTML::_('stylesheet', 'eoi.css', $append .'components/com_clubreg/assets/');

if ( $this->params->get( 'show_page_title' ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->params->get( 'page_title' ); ?>
</div>
<?php endif; 
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.calendar');

$append = '';
$in_type = "hidden";
global $option,$Itemid;
$document =& JFactory::getDocument();

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
<form action="index.php" method="post" name="adminForm"   id="adminForm"  class="form-validate eoi">
<fieldset>
	<legend>Parent - Guardian Details</legend>	
	<div class="tset">
		<label class="lbcls" for="g_surname">Surname :<span class="isReq">*</span></label><input type="text" class="intext required" name="g_surname" id="g_surname" value="<?php echo JRequest::getVar('g_surname', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_givenname"><?php echo GIVENNAME; ?> :<span class="isReq">*</span></label><input type="text" class="intext required" name="g_givenname" id="g_givenname" value="<?php echo JRequest::getVar('g_givenname', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_emailaddress">Email-Address :<span class="isReq">*</span></label><input type="text"  class="intext required validate-email" name="g_emailaddress" id="g_emailaddress" value="<?php echo JRequest::getVar( 'g_emailaddress', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_address">Address : </label><input class="intext" type="text" name="g_address" id="g_address" value="<?php echo JRequest::getVar('g_address', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_suburb">Suburb / Town : </label><input type="text"  class="intext" name="g_suburb" id="g_suburb" value="<?php echo JRequest::getVar('g_suburb', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_postcode">Postcode : </label><input type="text" class="intext half" name="g_postcode" id="g_postcode" value="<?php echo JRequest::getVar('g_postcode', '', 'post', 'string' );	?>"/>
	
	</div>
	<div class="tset">
		<label class="lbcls" for="g_phoneno">Phone : </label><input type="text"  class="intext" name="g_phoneno" id="g_phoneno" value="<?php echo JRequest::getVar('g_phoneno', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_mobile">Mobile : </label><input type="text"  class="intext" style="width:150px;" name="g_mobile" id="g_mobile" value="<?php echo JRequest::getVar('g_mobile', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<input type="checkbox" name="g_send_news" id="g_send_news" value="send"/>Do not send me promotional emails
		</div><p class="cl"></p>
</fieldset>
<br />
<?php  $div_counter = 4;
$j = 0;
$t_request['p_surname'] = JRequest::getVar('p_surname', array(), 'post', 'array' );
$t_request['p_givenname'] = JRequest::getVar('p_givenname', array(), 'post', 'array' );
$t_request['p_dob'] = JRequest::getVar('p_dob', array(), 'post', 'array' );
$t_request['p_group'] = JRequest::getVar('p_group', array(), 'post', 'array' );
$t_request['p_gender'] = JRequest::getVar('p_gender', array(), 'post', 'array' );

for($i = 1 ; $i <= $div_counter; $i++,$j++){
	$t_index = "player_".$j;
?>
<input type="<?php echo $in_type; ?>" name="div_counter" value="<?php echo $div_counter; ?>" />
<fieldset>
	<legend>Junior Player Details <?php echo $i;?></legend>
	<div class="tset">
		<label class="lbcls" for="p_surname[player_<?php echo $j; ?>]">Surname : </label><input type="text" class="intext" name="p_surname[player_<?php echo $j; ?>]" value="<?php echo JArrayHelper::getValue($t_request['p_surname'], $t_index, '', 'string' );?>"/>
	</div>
	<div class="tset">	
		<label class="lbcls" for="p_givenname[player_<?php echo $j; ?>]"><?php echo GIVENNAME; ?> : </label><input type="text" class="intext" name="p_givenname[player_<?php echo $j; ?>]" value="<?php echo JArrayHelper::getValue($t_request['p_givenname'], $t_index, '', 'string' );?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="p_dob[player_<?php echo $j; ?>]">Date Of Birth : </label><?php 
				$format = '%d/%m/%Y'; $name = "p_dob[player_". $j."]"; $id= "p_dob_player_".$j; $value = JArrayHelper::getValue($t_request['p_dob'], $t_index, '', 'string' );
				echo JHTML::_('calendar', $value, $name, $id, $format, array('class' => 'intext','style'=>'width:80px;')); 
			?>
	</div>
	<div class="tset">	
		<label class="lbcls" for="p_group[player_<?php echo $j; ?>]"><?php echo GROUP; ?> : </label><?php $t_prop=" styler=\"width:180px;\"";  $name = "p_group[player_". $j."]";		$id= "p_group_player_".$j;
		$value = JArrayHelper::getValue($t_request['p_group'], $t_index, '', 'string' ); echo JHTML::_('select.genericlist',  $this->lists['current_groups'], $name, 'class="intext" id="'.$id.'"  size="1" '.$t_prop, 'value', 'text', $value);
		?>
	</div>
	<div class="tset">
		<label class="lbcls" for="p_gender[player_<?php echo $j; ?>]" >Gender : </label>	
		<?php 
			$t_gender = JArrayHelper::getValue($t_request['p_gender'], $t_index, '', 'string' );?>
			<input type="radio" name="p_gender[player_<?php echo $j; ?>]" value="male" <?php echo ($t_gender == "male")?"checked":""; ?> />Male <input type="radio" name="p_gender[player_<?php echo $j; ?>]" value="female" <?php echo ($t_gender == "female")?"checked":""; ?>/>Female 
		
	</div>		
</fieldset>
<br />
<?php  } ?>
<div class="center">
<?php if($this->params->get('userecaptcha')  && $this->params->get('userecaptcha') == 1 &&  $this->params->get('pubkey')){ echo recaptcha_get_html($this->params->get('pubkey')); }?>
<?php  /*?>
<button name='normal_save' id="normal_save" class="rounded  validate" onclick="this.form.task.value='send_request';"><span>Send Request</span></button>
*/?>
<input class="button validate" name='normal_save' id="normal_save" type="submit"  value='<?php echo JText::_('Send Request'); ?>' />
</div>	
	
	<input type="<?= $in_type ?>" name="g_playertype" value="guardian" />	
	<input type="<?= $in_type ?>" name="option" value="<?= $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" value="<?= $Itemid ?>" />
	<input type="<?= $in_type ?>" name="task" value="send_request" />	
	<input type="<?= $in_type ?>" name="c" value="eoi" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
//button
ClubregHelper::write_footer();