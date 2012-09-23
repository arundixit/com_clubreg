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
class ClubHiddenHelper{
	
	static function renderButtons($edit_url){
		?><div class="right_buttons"><?php 
		if(isset($edit_url["prev"]) || isset($edit_url["next"]) ){ ?>
		
		<?php if(isset($edit_url["prev"])) { 
			echo sprintf("<a href=\"%s\" class=\"e_prev\">Prev</a>|",$edit_url["prev"]);	
		}
		if(isset($edit_url["next"])){
			echo sprintf("<a href=\"%s\" class=\"e_next\">Next </a>",$edit_url["next"]);
		}
		?>
		
	<?php }else { echo "&nbsp;"; } ?></div><?php 
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $a_result
	 * @param unknown_type $sex_list
	 * @return unknown
	 */
	static function renderUser(&$a_result,&$sex_list){
		global $option,$Itemid;
		ob_start();
		$edit_url = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=",$option,$Itemid);
		
		//write_debug($a_result->playertype);
		//JUNIOR
		//GUARDIAN
		//SENIOR
		$w_email = in_array($a_result->playertype, array("GUARDIAN","SENIOR"));
		$w_season = in_array($a_result->playertype, array("JUNIOR","SENIOR"));	$lprop = "class=\"tbl_label\"";
		?>		
		<table cellpadding=0 cellspacing=2 width=100% class="<?php echo $a_result->class; ?>">
			<tr>				
				<td colspan=2 class="fline" >
					<?php echo $a_result->offset ;?>
					<?php echo $a_result->show_check;?>
					<a href="<?php echo $edit_url.$a_result->member_id ; ?>&ordinal=<?php echo $a_result->offset; ?>" >
						<?php echo $a_result->surname ;?></a>
				</td>								
			</tr>
			<?php if($w_email) {?>
			<tr><?php w_td("Email", $lprop); w_td($a_result->emailaddress, $tprop) ?></tr>	
			<?php } 
			if($a_result->guardian){ ?>		
			<tr>
				<?php 	
					$t_str = "Guardian";
					w_td($t_str, $lprop);						
					$t_str = $a_result->guardian;
					w_td($t_str);						
				?>	
			</tr>
			<tr>
				<?php 
					$t_str = "Address";
					w_td($t_str, $lprop);
					if($a_result->gaddress)		$c_[] = $a_result->gaddress;
					if($a_result->gsuburb)		$c_[] = $a_result->gsuburb;
					if($a_result->gpostcode) 	$c_[] = $a_result->gpostcode;
					$t_str = implode(", ", $c_);
					w_td($t_str, $tprop);
				?>	
			</tr>
			
			<?php }else{ ?>
				<tr>
					<?php 
					$t_str = "Address";
					w_td($t_str, $lprop);
					if($a_result->address)		$c_[] = $a_result->address;
					if($a_result->suburb)		$c_[] = $a_result->suburb;
					if($a_result->postcode)		$c_[] = $a_result->postcode;
					$t_str = implode(", ", $c_);
					w_td($t_str, $tprop);
				?>				
				</tr>		
			<?php } 
			
			if($w_season){	?>
				<tr>
					<?php
					$t_str = "Gender";
					w_td($t_str, $lprop);
					
					$t_str = $sex_list[$a_result->gender]->text;
					w_td($t_str, $tprop);					
					?>
				</tr>
				<?php if($a_result->playertype == "JUNIOR"){ ?>
				<tr>
					<?php
					$t_str = "DOB";
					w_td($t_str, $lprop);
					
					$t_str = $a_result->dob;
					w_td($t_str, $tprop);					
					?>				
				</tr>			
				<?php } ?>
				<tr>
				<?php
					$t_str = SEASON;
					w_td($t_str, $lprop);
					
					$t_str = $a_result->year_registered ;
					w_td($t_str, $tprop);
				?>
				</tr>	
				<tr>
				<?php
					$t_str = GROUP;
					w_td($t_str, $lprop);
					
					$t_str = $a_result->group ;
					w_td($t_str, $tprop);
				?>				
				</tr>
				<tr>
				<?php 
					$t_str = SUBGROUP;
					w_td($t_str, $lprop);
						
					$t_str = $a_result->sgroup ;
					w_td($t_str, $tprop);					
				?>				
				</tr>				
				<tr>
				<?php 
					$t_str = TAGS;
					w_td($t_str, $lprop);
						
					$t_str = ucwords($a_result->member_tags) ;
					w_td($t_str, $tprop);					
				?>				
				</tr>			
			<?php }else{ ?>
				<tr>
				<?php 
					$t_str = sprintf("Junior  %s",PLAYERS);
					w_td($t_str, $lprop);
						
					$t_str = $a_result->my_children ;
					w_td($t_str, $tprop);	
				?>
				</tr>			
			<?php } ?>			
			<tr>
				<?php 
					$tprop = 'class="reg" colspan=2 ';
					$t_str = sprintf("Date Registered : %s Registered By : %s ", $a_result->t_created_date,$a_result->t_created_by);
					w_td($t_str, $tprop);
				?>			
			</tr>		
		</table>			
			<?php 
			$t_string = ob_get_contents();
			ob_end_clean();
			echo $t_string;
			return $t_string;
		}
		
		static function renderUser_(&$a_result,&$sex_list){
			global $option,$Itemid;
			ob_start();
			$edit_url = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=",$option,$Itemid);
		
			//write_debug($a_result->playertype);
			//JUNIOR
			//GUARDIAN
			//SENIOR
			$w_email = in_array($a_result->playertype, array("GUARDIAN","SENIOR"));
			$w_season = in_array($a_result->playertype, array("JUNIOR","SENIOR"));
			?>
					<div class="<?php echo $a_result->class; ?>">
					<span class="fline"><?php echo $a_result->offset ;?><?php echo $a_result->show_check;?><a href="<?php echo $edit_url.$a_result->member_id ; ?>&ordinal=<?php echo $a_result->offset; ?>" ><?php echo $a_result->surname ;?></a></span> <br />
					<?php 
					if($w_email){?><label>Email</label> : <?php echo $a_result->emailaddress; ?><br /><?php }
					if($a_result->guardian){?>
					<label>Guardian</label> : <?php echo $a_result->guardian; ?><br />
					<label>Address</label> : <?php echo $a_result->gaddress; ?> , <?php echo $a_result->gsuburb; ?> , <?php echo $a_result->gpostcode; ?><br />
					<?php }else{ ?>
					<label>Address</label> : <?php echo $a_result->address; ?> , <?php echo $a_result->suburb; ?> , <?php echo $a_result->postcode; ?><br />
					<?php } 			
					if($w_season){	?>			
					<label>Gender</label> : <?php echo $sex_list[$a_result->gender]->text; if($a_result->playertype == "JUNIOR"){ ?> <label class="mv_left">DOB</label> : <?php echo $a_result->dob;  ?><?php } ?> <br />
					<label ><?php echo SEASON; ?></label> : <?php echo $a_result->year_registered ;?>
					<label class="mv_left"><?php echo GROUP ?></label> : <?php echo $a_result->group;  ?> <label class="mv_left"><?php echo SUBGROUP ?></label> : <?php echo $a_result->sgroup;  ?><br />
					<label><?php echo TAGS ?></label>: <?php echo ucwords($a_result->member_tags);  ?><br />
					<?php }else{ ?>
					<label style="text-decoration:underline">Juniors</label> <?php echo $a_result->my_children; ?>
					<?php }?>
					<div class="reg">Date Registered : <?php echo $a_result->t_created_date ?> Registered By :<?php echo $a_result->t_created_by?></div>
					</div> 
					<?php 
					$t_string = ob_get_contents();
					ob_end_clean();
					echo $t_string;
					return $t_string;
		}
}

?>