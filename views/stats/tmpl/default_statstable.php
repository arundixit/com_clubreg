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

$show_check = false;

$all_results = $this->all_results;
$all_headings = $this->all_headings;

$howmany = count($all_results);


$page = $all_headings["pageNav"];
$return_data = $all_headings["return_data"];
$list_headings = $all_headings["headings"];
$filter_heading = $all_headings["filters"];
$style_heading = $all_headings["styles"]  ;
$style_class = $all_headings["tdstyles"]  ;

$heading_obj = $all_headings["headings_obj"];
$col_count = count($list_headings)+1;

$stats_preg = "/stats_/";
?>
	<table class="art-data" border=1 cellspacing=0 style="border-collapse:collapse;">
		<thead>
		<tr>
			<th width="10"><?php echo JText::_( 'NUM' ); ?></th>
		<?php if($show_check){ ?>
			<th width="10" class="title">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $howmany; ?>);" />						
			</th>
		<?php } 
						
			$sorting_heading =  $all_headings["sorting"];		//$col_count = count($all_headings)+1;	
			foreach($list_headings as $t_key => $t_value){ 
				$t_style = isset($style_heading[$t_key])?$style_heading[$t_key]:"";
				?>
				<th class="title" <?php echo $t_style; ?>>						
						<?php 
							if(isset($sorting_heading[$t_key])){
								echo JHTML::_('grid.sort',   $t_value, $sorting_heading[$t_key]["sort_col"], @$lists['order_Dir'], @$lists['order'] );
							}else{
								echo JText::_( $t_value ); 
							}?>
				</th>				
			<?php	}	?>
		</tr>			
	</thead>
	<tfoot>
	<?php if(isset($return_data["jaykenzo"]) &&  $return_data["jaykenzo"]=="edit"){ ?>
	<tr>
		<td>-</td>
		<?php 
			foreach($list_headings as $t_key => $t_value){ 
				?>
				<td><?php 
					if(preg_match($stats_preg, $t_key)){
						if(isset($heading_obj[$t_key])){
							?><input type="button" class="button bt_stats" value="Save" rel='<?php echo $t_key; ?>'/><?php							
						}
					}				
				 ?>
				 </td>
				<?php 				
			}
		
		?>
	</tr>
	<?php } ?>
		<tr>
			<td colspan="<?= $col_count+1;?>">
				<?php echo $page->getListFooter(); ?>
			</td>
		</tr>				
	</tfoot>
<?php
	if($howmany > 0){		
				
		$k = 0;$i = 0;
		$cl_ = array("","row1");					
		$primary_key = "comm_id";				
					
			$edit_url = sprintf("index.php?option=%s&c=comms&task=editcomms&Itemid=%s&comm_id=",$option,$Itemid);
			foreach($all_results as $a_result){ ?>
		<tr class="<?= $cl_[$k]; ?>">
			<td><? $t_offset =  $page->getRowOffset( $i ); echo $t_offset; ?></td>
			<?php if($show_check){ ?><td><?php echo JHTML::_('grid.id', $i, $a_result->comm_id,false,$primary_key );  ?></td><?php }					
				
			
			foreach($list_headings as $t_key => $t_value){ 

				$td_class = isset($style_class[$t_key])?$style_class[$t_key]:"";
			?>
				<td <?php echo $td_class; ?>>
					<?php 					
						if(preg_match($stats_preg, $t_key)){
							if(isset($heading_obj[$t_key])){
								if(isset($return_data["jaykenzo"]) &&  $return_data["jaykenzo"]=="edit")
									ClubRegModelStats::renderControl($heading_obj[$t_key],$a_result);
								else								
									ClubRegModelStats::renderControl_view($heading_obj[$t_key],$a_result);
							}
						}else{
							echo ($a_result->$t_key == -1)?"-":$a_result->$t_key;
						}						
					?>
				</td>				
			<?php	}	?>		
	</tr>						
			<?php
				$k = 1 - $k; $i++;
			}					
			unset($status_list);
		}else{ ?>
			<tr>
				<td align="center" colspan="<?= $col_count+1; ?>" class="norecords">No Results</td>
			</tr>
			
		<?php } ?>		
	</tbody>	
</table>