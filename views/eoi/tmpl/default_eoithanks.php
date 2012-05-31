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

<div class="componentheading">
	Expression of Interest
</div>
<?php if(isset($this->eoi_template) && ($this->eoi_template->template_id > 0 )){ 
	echo stripslashes($this->eoi_template->template_text);
 }else{?>
<p>
Thank you for registering your expression of interest,

One of our team leaders will get back to you in due course
</p>
<?php  } ?>