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

 if ( $this->params->get( 'show_page_title' ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->params->get( 'page_title' ); ?>
</div>
<?php endif; 
//&& !$this->member->params->get( 'popup' ) 
global $colon;
$append = '';
$member_details = $this->lists["member"] ;

$user_data = $member_details->user_data;

$my_details = $member_details->member_details;

if(count($my_details)> 0){ //$contact->email_to = JHTML::_('email.cloak', $contact->email_to);
?>
<div class="h3">Team Member Details </div>
<div  class="fieldset">
<table>
<?php $i =0; $t_email = "/email/";
	foreach($my_details as $a_details){		
		?><tr class="n">
			<td valign=top><label class="lbcls"><?php echo  $a_details->config_name; ?></label></td><td valign=top><?php echo $colon ;?></td>
			<td valign=top>						
			<?php if(preg_match($t_email,$a_details->member_detail)){
						$a_details->member_value = JHTML::_('email.cloak', $a_details->member_value);
						echo $a_details->member_value;
					}else{
						//write_debug($a_details);
						echo nl2br($a_details->member_value);
					} ?>
			</td>
				
		</tr>
<?php  $i= 1- $i;	}
?>
	</table>
</div>
<?php
}

$my_groups = $member_details->group_leaders;

if(count($my_groups) > 0){
?>
<div class="h3"><img alt="" src="components/<?php echo $option; ?>/assets/images/groups.png" align=middle hspace=3 width=24>Leader of <?php echo GROUPS; ?></div>
<div class="fieldset">
<?php 
echo "<ul>";
	foreach($my_groups as $a_details){
		echo "<li>".nl2br($a_details->text)."</li>";
	}
echo "</ul>";
?></div>
<?php 
}
$my_groups = array();
$my_groups = $member_details->group_members;

if(count($my_groups) > 0){ 	?>
	
	<div class="h3"><img alt="" src="components/<?php echo $option; ?>/assets/images/groups.png" align=middle hspace=3  width=24>My <?php echo GROUPS; ?></div>
	<div class="fieldset">
	<?php 
echo "<ul>";
	foreach($my_groups as $a_details){	
		echo "<li>".nl2br($a_details->text)."</li>";
	}
echo "</ul>";
?></div><?php
}

ClubregHelper::write_footer();