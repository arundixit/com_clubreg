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

	global $option, $Itemid,$colon;		
	
	JHTML::_('behavior.formvalidation');
	
	$stat_headings = $this->stat_headings;
	$result = $this->result;
	$heading_obj = $this->heading_obj;
	$row = $this->row;
	$return_data = $this->return_data;

	$in_type = "hidden";
		
?>
	<form action="index2.php" method="post"  style="margin:2px;text-align:left;" name="stats_admin" id="stats_admin" class="form-validate">				
				<div class="h3"><?php  ClubHtmlHelper::renderIcon(array('img'=>'stats.png','text'=>'Stats')); ?><?php echo STATS; ?> Details :: <?php echo ucwords($row->surname." ".$row->givenname) ?> </div>
				<div class="fieldset">	
				<div class="n"><label class="lbcls" for="stats_date"><?php echo STATS; ?> Date</label><?php echo $colon; ?>
			<?php 
				$format = '%d/%m/%Y';
				$name = "stats_date"; 	$id= "stats_date";			
				echo JHTML::_('calendar', $return_data['stats_date'], $name, $id, $format, array('class' => 'intext','style'=>'width:80px;','readonly'=>'readonly'));					
			?>			
			</div>				
				<?php if(count($stat_headings["headings"]) > 0 ){
						foreach($stat_headings["headings"] as $a_key => $a_value){ 	?>
						<div class="n"><label class="lbcls" for="<?php echo $a_key ?>"><?php echo $a_value ?> </label><?php echo $colon;?><?php echo ClubRegModelStats::renderControl($heading_obj[$a_key],$result); ?>
						<input type="hidden" name="stats_list[<?php echo $a_key; ?>]" value="<?php echo $a_key; ?>" />
						</div>			
					<?php }
					}?>			
			<div style="text-align:center;padding:3px;">
					<input class="button validate" name='normal_save' id="normal_save" type="submit" value='Save Details' />
			</div>			
			</div>				
				<input type="<?= $in_type ?>" name="member_id" value="<?php echo $row->member_id; ?>" />					
				<input type="<?= $in_type ?>" name="option" value="<?= $option ?>" />	
				<input type="<?= $in_type ?>" name="Itemid" value="<?= $Itemid ?>" />
				<input type="<?= $in_type ?>" name="task" value="saveastats" />	
				<input type="<?= $in_type ?>" name="c" value="stats" />
				<input type="<?= $in_type; ?>" name="no_html" value="1" />		
				<?php echo JHTML::_( 'form.token' ); ?>				
			</form>
		
