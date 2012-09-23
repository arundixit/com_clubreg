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
global $option,$Itemid;
$key_var = sprintf("&stats_date=%s",date("d.m.Y"));
$stats_url = sprintf("index2.php?option=%s&c=stats&task=editstats&Itemid=%s&member_id=%s&no_html=0&path=&%s=1%s",
		$option,$Itemid,$this->member_data->member_id,JUtility::getToken(),$key_var);
?>
<div class="h3"><?php  ClubHtmlHelper::renderIcon(array('img'=>'stats.png','text'=>'Stats')); ?><?php echo STATS; ?></div>
<div class="fieldset" >
<div class="right_buttons"><a href="<?php echo $stats_url;?>" class="modal-button" rel="{handler: 'iframe', size: {x: 400, y: 400}}" style="font-weight:normal">Add <?php echo STATS; ?></a></div>
<p class="cl"></p>
<div style="padding:1px 5px 0px 10px" id="stats_div">
<?php 	ClubStatsHelper::renderStatsList($this->stats_list,$this->member_data); ?>
</div>
</div>