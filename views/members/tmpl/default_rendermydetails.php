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

global $option,$Itemid,$colon,$append;
JHTML::_('script', 'menu.js', $append .'components/com_clubreg/assets/');
if ( $this->params->get( 'show_page_title' ) ) : 
	$page_title = $this->params->get( 'page_title' );
 endif; 

 $member_params = $this->member_params;
ClubregHelper::generate_menu_tabs($this->member_params,$page_title );




$member_details = $this->lists["member"] ;
$user_data = $member_details->user_data;
$my_details = $member_details->member_details;
$in_type = "hidden";?>
<table>
<tr>
<td>
<?php
if(count($this->headings)> 0){?>
<form action="index.php" method="post" name="adminForm"  class="form-validate">
<div class="h3">Personal Details</div>
<div class="fieldset">
<?php $i =0; $is_date = "/date/"; 
	foreach($this->headings as $hd_key => $hd_value){
		$t_value = isset($my_details[$hd_key])?$my_details[$hd_key]->member_value:"";
		
		$t_width = (preg_match($is_date, $hd_key))?"120px":"300px";		
		
		$control_params = new JParameter( $hd_value->params );
		$ctrlname =sprintf("user_details[%s]",$hd_key);
		
		
		?><div class="n">
			<label class="lbcls" for="<?php echo $ctrlname; ?>"><?php echo  $hd_value->config_name; ?></label><?php echo $colon ;?>			
			
			<?php switch($control_params->get("control_type")){				
				case "textarea": $t_width =($control_params->get("control_width"))?$control_params->get("control_width"):$t_width;	?>
					<textarea style="width:<?php echo $t_width; ?>" rows=5 name="<?php echo $ctrlname; ?>" ><?php echo $t_value; ?></textarea>					
			<?php 
				break;
				default:?>				
				<input type="text" style="width:<?php echo $t_width; ?>;padding:1px;" value="<?php echo $t_value; ?>" name="<?php echo $ctrlname ;?>" />
				<?php 					
				break;				
			}?>			
					
		</div>
<?php  $i= 1- $i;	} ?>
<br />
</div>
<br />
<div align=center>
		<input type="button" class="button" onclick="submitbutton( 'save_userdetails' );return false;" value="Save Details">
</div>

	<input type="<?= $in_type ?>" name="uid" value="<?= $user_data->id;?>" />	
	<input type="<?= $in_type ?>" name="option" value="<?= $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" value="<?= $Itemid ?>" />
	<input type="<?= $in_type ?>" name="task" value="save_userdetails" />	
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php } ?>
</td>
<td valign=top style="padding-left:10px;" width=300>
<?php 
	if($member_params->get( 'vieweoi' ) == "yes"){
		
		$d_url = sprintf("index.php?option=%s&view=eoi&layout=loadeoi&c=eoi&task=loadeoi&Itemid=%s&playertype=",
		$option,$Itemid	);?>
		<div class="h3"><img alt="" src="components/<?php echo $option; ?>/assets/images/groups.png" align=middle hspace=2 width="24">Unapproved EOIs</div>
		<div class="fieldset">
			<ul>
				<li><a href="<?php echo $d_url ;?>guardian">Guardian of Junior Players (<?php echo $this->howmany_guardian; ?>)</a></li>
				<li><a href="<?php echo $d_url ;?>senior">Senior Players (<?php echo $this->howmany_senior; ?>)</a></li>
			</ul>
		
		</div>
<?php 
		
	}



		$my_groups = $member_details->group_leaders;
		$d_url = sprintf("index.php?option=%s&c=userreg&task=loadregistered&Itemid=%s&limit=20&playertype=[playertype]&filter_group=",$option,$Itemid);
		
		if(count($my_groups) > 0){?>
		
		<div class="h3"><img alt="" src="components/<?php echo $option; ?>/assets/images/groups.png" align=middle hspace=2 width="24">Leader of  <?php echo GROUPS; ?></div>
		<div class="fieldset">
		<?php 
		
		echo "<ul>";
			foreach($my_groups as $a_details){	

				if( $member_params->get( 'manageusers' ) == "yes" ){ 
					$group_type = "";
					if(isset($member_details->group_paramslist[$a_details->value])){
						$current_group = $member_details->group_paramslist[$a_details->value];
						$group_params = new JParameter( $current_group->params );
						$group_type = $group_params->get("grouptype");
					}				
					$start = sprintf("<a href='%s%s'>",$d_url,$a_details->value);
					$start = str_replace("[playertype]", $group_type, $start);
					
					$end = "</a>";
				}else{
					$start = $end = "";
				}
				echo "<li>".$start.nl2br($a_details->text).$end."</li>";
			}
		echo "</ul>";
		}
		?></div><?php 
		$my_groups = array();
		$my_groups = $member_details->group_members;
		
		if(count($my_groups) > 0){ 	?>
			
			
			<div class="h3"><img alt="" src="components/<?php echo $option; ?>/assets/images/groups.png" align=middle hspace=2  width="24">My <?php echo GROUPS; ?></div>
			<div class="fieldset">
			<?php 
		echo "<ul>";
			foreach($my_groups as $a_details){	
				
				if( $member_params->get( 'manageusers' ) == "yes" ){
				
				
					$group_type = "";
					if(isset($member_details->group_paramslist[$a_details->value])){
						$current_group = $member_details->group_paramslist[$a_details->value];
						$group_params = new JParameter( $current_group->params );
						$group_type = $group_params->get("grouptype");
					}
					$start = sprintf("<a href='%s%s'>",$d_url,$a_details->value);
					$start = str_replace("[playertype]", $group_type, $start);
					
					$end = "</a>";		
				}else{
					$start = $end = "";
				}
				
				echo "<li>".$start.nl2br($a_details->text).$end."</li>";
			}
		echo "</ul>";
		?></div><?php
		}
?>
</td>
</tr>
</table>
<?php
ClubregHelper::write_footer();
