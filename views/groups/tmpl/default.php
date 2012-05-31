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

	$group_data = $this->group_data;
	$write_div = false; 
if(isset($group_data->group_data->group_leader_name)){
	echo sprintf("<div class=\"h3\" style='color:black'>".GROUP." Leader of %s:: ",$group_data->group_data->group_name);
	echo $group_data->group_data->group_leader_name."</div>";
	
	$write_div = true;
}
if($write_div){
	?>
	<div class="fieldset" style="padding-left:5px;">
	<?php 
}
	
if(count($group_data->team_members) > 0){
	?>
	<div class="taghd">Team Members of <?php echo $group_data->group_data->group_name; ?></div>
	<div>
	<ol>	
		<?php  foreach($group_data->team_members as $a_member){?>
				<li><?php echo $a_member->group_member_name; ?></li>
		<?php }?>
	</ol>
	</div>
	<?php 
	
}
	
if(count($group_data->group_members) > 0){
	?>
	<div class="taghd">Players of <?php echo $group_data->group_data->group_name; ?></div>
	<div>
	<ol>	
		<?php  foreach($group_data->group_members as $a_member){?>
				<li><?php echo $a_member->member_name; ?></li>
		<?php }?>
	</ol>
	</div>
	<?php 
	
}

if($write_div){ ?>
</div>
<?php 
}

ClubregHelper::write_footer();
