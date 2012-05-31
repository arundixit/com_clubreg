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

$document =& JFactory::getDocument();

$in_type = "hidden";
$page_title = "Preview Communications";
$document->setTitle($page_title );


$all_headings = $this->all_headings;
?>
<table border=0 width="800" align="center" cellpadding=0 cellspacing=0>
  
  <tr>
    <td><div class="h3" style="margin-top:2px">Message Details</div></td>    
  </tr>
  <tr>
  	<td>  	
		<div class="fieldset">
		<table border=0 width="100%">
			<tr>
				<td valign="top">		
					<label class="lbcls" for="subject">To </label><?php echo $colon ;?>
				</td>
				<td align="left">&nbsp;</td>
			</tr>	
			<tr>
				<td valign="top">		
					<label class="lbcls" for="subject">Subject </label><?php echo $colon ;?>
				</td>
				<td align="left"><?php echo stripslashes($all_headings["message_subject"]); ?></td>
			</tr>
			<tr>
				<td valign="top" >		
					<label class="lbcls" for="message">Message </label><?php echo $colon ;?>
					</td>
					<td  align="left" width="80%"><?php echo stripslashes($all_headings["message_body"]); ?></td>
			</tr>	
		</table>
		</div>  	
  	</td>
  
  </tr>
</table>



