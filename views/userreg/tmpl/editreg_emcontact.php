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
error_reporting(0);global $colon;
?>
<div class="taghd"><?php echo EMERGENCY; ?> Details</div>
		<div class="n  fleft">
		<label class="lbcls" for="em_surname">Surname</label><?php echo $colon ;?><input type="text" class="intext required" name="em_surname" id="em_surname" value="<?php echo $emcontact->surname;	?>"/>
		</div>
		<div class="n fleft">
		<label class="lbcls" for="em_givenname"><?php echo GIVENNAME; ?></label><?php echo $colon ;?><input type="text" class="intext required" name="em_givenname" id="em_givenname" value="<?php echo $emcontact->givenname;	?>"/>
		</div>
		<div class="n">
		<label class="lbcls" for="em_emailaddress">Email-Address</label><?php echo $colon ;?><input type="text"  class="intext required validate-email" name="em_emailaddress" id="em_emailaddress" value="<?php echo $emcontact->emailaddress;?>"/>
		</div>			
		<div class="n  fleft">
		<label class="lbcls" for="em_phoneno">Phone </label><?php echo $colon ;?><input type="text"  class="intext" name="em_phoneno" id="em_phoneno" value="<?php echo $emcontact->phoneno;	?>"/>
		</div>
		<div class="n  fleft">
		<label class="lbcls" for="em_mobile">Mobile</label><?php echo $colon ;?><input type="text"  class="intext" style="width:150px;" name="em_mobile" id="em_mobile" value="<?php echo $emcontact->mobile;	?>"/>
		</div>	
		<div class="n">
		<label class="lbcls" for="em_address">Address</label><?php echo $colon ;?><input class="intext" type="text" name="em_address" id="em_address" value="<?php echo $emcontact->address;	?>"/>
		</div>
		<div class="n  fleft">
		<label class="lbcls" for="em_suburb">Suburb / Town</label><?php echo $colon ;?><input type="text"  class="intext" name="em_suburb" id="em_suburb" value="<?php echo $emcontact->suburb;	?>"/>
		</div>
		<div class="n  fleft">
		<label class="lbcls" for="em_postcode">Postcode</label><?php echo $colon ;?><input type="text" style="intext half" name="em_postcode" id="em_postcode" value="<?php echo $emcontact->postcode;	?>"/>
		</div>	
		<div class="n"><p class="cl"></p>
			<label class="lbcls" for="em_medical">Special Medical<br />/Allergies</label><?php echo $colon ;?><textarea rows="10"  class="intext" name="em_medical" id="em_medical"><?php echo stripslashes($emcontact->em_medical); ?></textarea>
		</div>