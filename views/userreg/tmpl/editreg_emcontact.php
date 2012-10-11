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
global $colon;

$emcontact_array = isset($this->contact_details->contact_details)?$this->contact_details->contact_details:array();
$contact_key = $this->contact_details->contact_key ;// "em_";
?>
		<input type="hidden" name="contact_key[]" value="<?php echo $contact_key ?>" id="contact_key_<?php echo  $contact_key ?>"/>
		<div class="n  fleft">		
		<label class="lbcls" for="<?php echo $contact_key; ?>surname">Surname</label><?php echo $colon ;?><input type="text" class="intext" name="<?php echo $contact_key; ?>surname" id="<?php echo $contact_key; ?>surname" value="<?php echo wContact($emcontact_array,$contact_key,"surname");?>"/>
		</div>
		<div class="n fleft">
		<label class="lbcls" for="<?php echo $contact_key; ?>givenname"><?php echo GIVENNAME; ?></label><?php echo $colon ;?><input type="text" class="intext" name="<?php echo $contact_key; ?>givenname" id="<?php echo $contact_key; ?>givenname" value="<?php echo wContact($emcontact_array,$contact_key,"givenname");?>"/>
		</div>
		<div class="n"><p class="cl"></p>
		<label class="lbcls" for="<?php echo $contact_key; ?>emailaddress">Email-Address</label><?php echo $colon ;?><input type="text"  class="intext validate-email" name="<?php echo $contact_key; ?>emailaddress" id="<?php echo $contact_key; ?>emailaddress" value="<?php echo wContact($emcontact_array,$contact_key,"emailaddress");;?>"/>
		</div>			
		<div class="n  fleft">
		<label class="lbcls" for="<?php echo $contact_key; ?>phoneno">Phone </label><?php echo $colon ;?><input type="text"  class="intext" name="<?php echo $contact_key; ?>phoneno" id="<?php echo $contact_key; ?>phoneno" value="<?php echo wContact($emcontact_array,$contact_key,"phoneno");?>"/>
		</div>
		<div class="n  fleft">
		<label class="lbcls" for="<?php echo $contact_key; ?>mobile">Mobile</label><?php echo $colon ;?><input type="text"  class="intext" style="width:150px;" name="<?php echo $contact_key; ?>mobile" id="<?php echo $contact_key; ?>mobile" value="<?php echo wContact($emcontact_array,$contact_key,"mobile");;	?>"/>
		</div>	
		<div class="n"><p class="cl"></p>
		<label class="lbcls" for="<?php echo $contact_key; ?>address">Address</label><?php echo $colon ;?><input class="intext" type="text" name="<?php echo $contact_key; ?>address" id="<?php echo $contact_key; ?>address" value="<?php echo wContact($emcontact_array,$contact_key,"address");?>"/>
		</div>
		<div class="n  fleft">
		<label class="lbcls" for="<?php echo $contact_key; ?>suburb">Suburb / Town</label><?php echo $colon ;?><input type="text"  class="intext" name="<?php echo $contact_key; ?>suburb" id="<?php echo $contact_key; ?>suburb" value="<?php echo wContact($emcontact_array,$contact_key,"suburb");	?>"/>
		</div>
		<div class="n  fleft">
		<label class="lbcls" for="<?php echo $contact_key; ?>postcode">Postcode</label><?php echo $colon ;?><input type="text" class="intext half" name="<?php echo $contact_key; ?>postcode" id="<?php echo $contact_key; ?>postcode" value="<?php echo wContact($emcontact_array,$contact_key,"postcode");?>"/>
		</div>
		<? if($contact_key == "em_"){ ?>	
		<div class="n"><p class="cl"></p>
			<label class="lbcls" for="<?php echo $contact_key; ?>medical">Special Medical<br />/Allergies</label><?php echo $colon ;?><textarea rows="10"  class="intext" name="<?php echo $contact_key; ?>medical" id="<?php echo $contact_key; ?>medical"><?php echo stripslashes(wContact($emcontact_array,$contact_key,"medical"));?></textarea>
		</div>
		<? } ?>
<p class="cl"></p>