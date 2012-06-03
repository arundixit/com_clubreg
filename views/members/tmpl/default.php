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

	foreach($this->headings as $hd_key => $hd_value){
		$t_value = isset($my_details[$hd_key])?$my_details[$hd_key]->member_value:"";
		?>
		<tr class="n">
			<td valign=top><label class="lbcls"><?php echo  $hd_value->config_name; ?></label></td><td valign=top><?php echo $colon ;?></td>
			<td valign=top>
				<?php $control_params = new JParameter( $hd_value->params );
					switch($control_params->get("control_type")){
						
						case "monthyear":
							$month_key = $hd_key."_month"; $year_key = $hd_key."_year";
							$t_array = getMonths();							
							$month_value = $t_value = isset($my_details[$month_key])?$my_details[$month_key]->member_value:"";
							$year_value = $t_value = isset($my_details[$year_key])?$my_details[$year_key]->member_value:"";
							echo sprintf("%s %s",$t_array[$month_value-1]->text,$year_value);
						break;
						case "email":
							echo JHTML::_('email.cloak', $t_value);
						break;
						default:
							if(preg_match($t_email,$t_value)){
								echo JHTML::_('email.cloak', $t_value);
							}else {
								echo $t_value;
							}
								
						break;
					}
					
				?>
			
			</td>
		</tr>		
		<?php 
		
	}
	
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