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

JHTML::_("behavior.mootools");
// MODAL - will be displayed in case e.g. highslide or shadowbox too, because in there are more links
JHTML::_('behavior.modal', 'a.modal-button');
global $option,$Itemid;
$payment_url = sprintf("index2.php?option=%s&c=userreg&task=editpayment&Itemid=%s&member_id=%s&no_html=0&path=&%s=1",$option,$Itemid,$this->member_data->member_id,JUtility::getToken());
?>
<div class="h3">Payments</div>
<div class="fieldset" >
<div class="right_buttons"><a href="<?php echo $payment_url;?>" class="modal-button" rel="{handler: 'iframe', size: {x: 400, y: 400}}" style="font-weight:normal">Add Payment</a></div>
<p class="cl"></p>
<div style="padding:1px 5px 0px 10px" id="payment_div">
<?php ClubPaymentsHelper::renderPaymentList($this->payment_list,$this->member_data ); ?>
</div>
</div>
<?php
