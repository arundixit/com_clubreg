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
?>
<?php if ( $this->params->get( 'show_page_title' ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->params->get( 'page_title' ); ?>
</div>
<?php endif; 
JHTML::_('stylesheet', 'eoi.css', $append .'components/com_clubreg/assets/');
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

	 	 $t_gender =  JRequest::getVar('g_gender', '', 'post', 'string' );
	 	 $t_group = JRequest::getVar('g_group', '-1' , 'post', 'string' );
?>
<form action="index.php" method="post" name="adminForm"   id="adminForm"  class="form-validate eoi">
<fieldset>
	<legend>Senior Player Details</legend>	
	<div class="tset">
		<label class="lbcls" for="g_surname">Surname :<span class="isReq">*</span></label><input type="text" class="intext required" name="g_surname" id="g_surname" value="<?php echo JRequest::getVar( 'g_surname', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_givenname"><?php echo GIVENNAME; ?> :<span class="isReq">*</span></label><input type="text" class="intext required" name="g_givenname" id="g_givenname" value="<?php echo JRequest::getVar( 'g_givenname', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_emailaddress">Email-Address :<span class="isReq">*</span></label><input type="text"  class="intext required validate-email" name="g_emailaddress" id="g_emailaddress" value="<?php echo JRequest::getVar( 'g_emailaddress', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_group"><?php echo GROUP; ?>&nbsp;:&nbsp;&nbsp; </label><?php $t_prop="";$t=" class=\"intext\"";  $name = "g_group"; 		$id= "g_group";	
		echo JHTML::_('select.genericlist',  $this->lists['current_groups'], $name, 'class="intext" id="'.$id.'"  size="1" '.$t_prop, 'value', 'text', $t_group);		?>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_gender">Gender : </label><input type="radio" name="g_gender" value="male" <?php echo ($t_gender == "male")?"checked":""; ?>/>Male 	<input type="radio" name="g_gender" value="female" <?php echo ($t_gender == "female")?"checked":""; ?>/>Female 		
	</div>	
	<div class="tset">
		<label class="lbcls" for="g_address">Address : </label><input class="intext" type="text" name="g_address" id="g_address" value="<?php echo JRequest::getVar( 'g_address', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_suburb">Suburb / Town : </label><input type="text"  class="intext" name="g_suburb" id="g_suburb" value="<?php echo JRequest::getVar( 'g_suburb', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_postcode">Postcode : </label><input type="text" class="intext half"  name="g_postcode" id="g_postcode" value="<?php echo JRequest::getVar( 'g_postcode', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_phoneno">Phone : </label><input type="text"  class="intext" name="g_phoneno" id="g_phoneno" value="<?php echo JRequest::getVar('g_phoneno', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<label class="lbcls" for="g_mobile">Mobile : </label><input type="text"  class="intext"  name="g_mobile" id="g_mobile" value="<?php echo JRequest::getVar('g_mobile', '', 'post', 'string' );	?>"/>
	</div>
	<div class="tset">
		<input type="checkbox" name="g_send_news" id="g_send_news" value="send"/>Do not send me promotional emails
	</div><p class="cl"></p>
</fieldset>
<?php if($this->params->get('userecaptcha')  && $this->params->get('userecaptcha') == 1 &&  $this->params->get('pubkey')){ echo recaptcha_get_html($this->params->get('pubkey')); }?> 
<br />
<input class="button validate" name='normal_save' id="normal_save" type="submit"  value='<?php echo JText::_('Send Request'); ?>' />
	
	<input type="<?= $in_type ?>" name="g_playertype" value="senior" />	
	<input type="<?= $in_type ?>" name="option" value="<?= $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" value="<?= $Itemid ?>" />
	<input type="<?= $in_type ?>" name="task" value="send_request" />	
	<input type="<?= $in_type ?>" name="c" value="eoi" />
	<input type="<?= $in_type ?>" name="view" value="senior" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
ClubregHelper::write_footer();
