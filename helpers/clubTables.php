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

class clubTables{
	function renderTables(&$all_results,&$all_headings){
	
		global $option,$Itemid;
	
		$player_row	=& JModel::getInstance('eoimembers', 'ClubRegModel'); ?>
			
			<?php			
			$howmany = count($all_results);
			$page = $all_headings["pageNav"];
			$return_data = $all_headings["return_data"];
			$list_headings = $all_headings["headings"];
			$filter_heading = $all_headings["filters"]; 		
			$style_heading = $all_headings["styles"]  ;	
			$style_class = $all_headings["tdstyles"]  ;
			
			
			$values_list = $all_headings["filters"];
			
			$lists['order_Dir']	=  $all_headings["filter_order_Dir"];
			$lists['order']		=  $all_headings["filter_order"];		
			
			$col_count = count($list_headings)+1;
			
			$show_check = false;
			if(in_array($return_data["playertype"], array("junior1","senior","guardian"))){
				$show_check = true;
				$col_count++;
			}
			
			if(isset($all_headings["page_type"]) && $all_headings["page_type"] == "registered"){						
					$show_check = true;
					$col_count++;				
			}
			?>
			<table class="flttbl" width=100% id="filter_table">
				<tr>
				<?php 
					foreach($list_headings as $t_key => $t_value){ 
						$filter_name = "";
						
						if(isset($filter_heading[$t_key])){	?>				
					
							<td class="title">		
								<?php  		
										if(isset($filter_heading[$t_key]["label"])){
											echo "<span class=\"fltlbl\">",$filter_heading[$t_key]["label"],"</span><br />";
										}else{
											echo "<br />";	
										}
										$filter_name = "filter_".$t_key;						
										switch($filter_heading[$t_key]["control"]){
											case "text":
												$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'';?>
													<input type="text" name="<?php echo $filter_name ;?>"  <?php echo isset($filter_heading[$t_key]["other"])?$filter_heading[$t_key]["other"]:""; ?> value="<?php echo $filter_value;?>" class="smallinput"/>
												<?php 
											break;
											case "select.genericlist":
												$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'-1';								
												echo JHTML::_('select.genericlist',  $filter_heading[$t_key]["values"], $filter_name, 'class="inputbox"', 'value', 'text', $filter_value);
											break;							
										}						
														
								?>
							</td>				
					<?php }		
						}	?>			
				</tr>
			
			</table>
			<table class="art-data" width="100%" border=1 cellspacing=0 style="border-collapse:collapse;">
			<thead>
			<tr>
						<th width="10">
							<?php echo JText::_( 'NUM' ); ?>
						</th>
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
					<tr>
						<td colspan="<?= $col_count;?>">
							<?php echo $page->getListFooter(); ?>
						</td>
					</tr>				
				</tfoot>
				<tbody>
					<?php
			if($howmany > 0){		
				
					$k = 0;$i = 0;
					$cl_ = array("","row1");		
					
					if(isset($all_headings["page_type"]) && $all_headings["page_type"] == "registered" ){
						$primary_key = "reg_members";
					}else{
						$primary_key = "eoi_member";
					}
					
					$edit_url = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=",$option,$Itemid);
					foreach($all_results as $a_result){ ?>
					<tr class="<?= $cl_[$k]; ?>">
						<td><? $t_offset =  $page->getRowOffset( $i ); echo $t_offset; ?></td>
						<?php if($show_check){ ?><td><?php echo JHTML::_('grid.id', $i, $a_result->member_id,false,$primary_key );  ?></td><?php }					
						foreach($list_headings as $t_key => $t_value){ 
	
							$td_class = isset($style_class[$t_key])?$style_class[$t_key]:"";
						?>
							<td <?php echo $td_class; ?>>
								<?php switch($t_key){
										case "gender":											
										case "memberlevel":
											echo (isset($values_list[$t_key]) && isset($values_list[$t_key]["values"][$a_result->$t_key]))?$values_list[$t_key]["values"][$a_result->$t_key]->text:"<b>-</b>";
										break;
										case "send_news":
												echo (intval($a_result->$t_key) == 1)?"<b>X</b>":"&#10004;";
										break;
										case "surname":
											if(isset($all_headings["page_type"]) && $all_headings["page_type"] == "registered" && strlen($return_data["playertype"]) > 2 ){
												?>
												<a href="<?php echo $edit_url.$a_result->member_id ; ?>&ordinal=<?php echo $t_offset; ?>"><?php echo ($a_result->$t_key == -1)?"-":$a_result->$t_key ;?></a>
												<?php 
											}else{
												echo ($a_result->$t_key == -1)?"-":$a_result->$t_key;
											}
										break;
										default:																				
											echo ($a_result->$t_key == -1)?"-":$a_result->$t_key;											
										break;
										}?>
							</td>				
						<?php	}	?>		
					</tr>						
					<?php
						$k = 1 - $k; $i++;
					}	
					
					unset($sex_list);
					unset($status_list);
				}else{ ?>
					<tr>
						<td align="center" colspan="<?= $col_count; ?>">No Results</td>
					</tr>
					
				<?php } ?>		
					</tbody>					
				</table>
	<?php 		
		}
		function renderTables_divs(&$all_results,&$all_headings){
		
			global $option,$Itemid,$append;
		
			//JHTML::_('stylesheet', 'div.css', $append .'components/com_clubreg/assets/');
			JHTML::_('stylesheet', 'tbl.css', $append .'components/com_clubreg/assets/');
			$player_row	=& JModel::getInstance('eoimembers', 'ClubRegModel'); ?>
					
					<?php			
					$howmany = count($all_results);
					$page = $all_headings["pageNav"];
					$return_data = $all_headings["return_data"];
					$list_headings = $all_headings["headings"];
					$filter_heading = $all_headings["filters"]; 		
					$style_heading = $all_headings["styles"]  ;	
					$style_class = $all_headings["tdstyles"]  ;
					
					
					$sex_list = $all_headings["filters"]["gender"]["values"];
					
					$lists['order_Dir']	=  $all_headings["filter_order_Dir"];
					$lists['order']		=  $all_headings["filter_order"];		
					
					$col_count = count($list_headings)+1;
					
					$show_check = false;
					if(in_array($return_data["playertype"], array("junior1","senior","guardian"))){
						$show_check = true;
						$col_count++;
					}
					
					if(isset($all_headings["page_type"]) && $all_headings["page_type"] == "registered"){						
							$show_check = true;
							$col_count++;				
					}
					?>
				
				<table width="100%">
					<tr>
						<td valign="top" width="160">				
							<table class="flttbl" width=100% id="filter_table_1" cellspacing=1 cellpadding=1>						
								<?php 
									 if($show_check){ ?>
										<tr>
											<td>				
												<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $howmany; ?>);" /><span class="fltlbl1">Check All</span>
											</td>
										</tr>											
								<?php } 
									foreach($list_headings as $t_key => $t_value){ 
										$filter_name = "";								
										if(isset($filter_heading[$t_key])){	?>				
											<tr>
											<td class="title">		
												<?php  		
														if(isset($filter_heading[$t_key]["label"])){
															echo "<span class=\"fltlbl1\">",$filter_heading[$t_key]["label"],"</span><br />";
														}else{
															echo "<br />";	
														}
														$filter_name = "filter_".$t_key;						
														switch($filter_heading[$t_key]["control"]){
															case "text":
																$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'';?>
																	<input type="text" name="<?php echo $filter_name ;?>"  value="<?php echo $filter_value;?>" class="smallinput"/>
																<?php 
															break;
															case "select.genericlist":
																$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'-1';								
																echo JHTML::_('select.genericlist',  $filter_heading[$t_key]["values"], $filter_name, 'class="inputbox"', 'value', 'text', $filter_value);
															break;							
														}						
																		
												?>
											</td>
											</tr>
									<?php }		
										}	?>
										<tr>
											<td>&nbsp;</td>
										</tr>							
							</table>
					</td>
					<td valign=top class="listing">													
						<?php						
						if(isset($all_headings["sorting"]) && count($all_headings["sorting"]) > 0 ){							
							$sorting_heading =  $all_headings["sorting"];
							echo "<div class=\"f_div\"><span class=\"f_label\">Sort By:</span>";
							foreach($list_headings as $t_key => $t_value){
								$t_style = isset($style_heading[$t_key])?$style_heading[$t_key]:"";
																
								if(isset($sorting_heading[$t_key])){?>
									<span class="filters" <?php echo $t_style; ?>>
									<?php echo JHTML::_('grid.sort',   $t_value, $sorting_heading[$t_key]["sort_col"], @$lists['order_Dir'], @$lists['order'] );?>
									</span><?php 													
								}
												
							}
							echo "</div>";	
						}
														
						if($howmany > 0){		
						
							$k = 0;$i = 0;
							$cl_ = array("row0","row1");		
							
							if(isset($all_headings["page_type"]) && $all_headings["page_type"] == "registered" ){
								$primary_key = "reg_members";
							}else{
								$primary_key = "eoi_member";
							}
							
							
							foreach($all_results as $a_result){ 
								$a_result->offset = $page->getRowOffset( $i );	
								
								 if($show_check){ $a_result->show_check = JHTML::_('grid.id', $i, $a_result->member_id,false,$primary_key );   }else { $a_result->show_check ="";} ;								
								 $a_result->class= $cl_[$k];
								 ClubHiddenHelper::renderUser($a_result,$sex_list); 
							
								$k = 1 - $k; $i++;
							}	
							
							unset($sex_list);
							unset($status_list);
						}else{ ?>							
								<div align="center">No Results</div>							
						<?php } ?>
						
							<div align=center style="padding-top:5px;"><?php echo $page->getListFooter(); ?></div>				
						</td>
					</tr>
				</table>
			<?php 		
	}
	function renderTables_comms(&$all_results,&$all_headings){
	
		global $option,$Itemid;
		
		$howmany = count($all_results);
		$page = $all_headings["pageNav"];
		$return_data = $all_headings["return_data"];
		$list_headings = $all_headings["headings"];
		$filter_heading = $all_headings["filters"];
		$style_heading = $all_headings["styles"]  ;
		$style_class = $all_headings["tdstyles"]  ;
		
		$lists['order_Dir']	=  $all_headings["filter_order_Dir"];
		$lists['order']		=  $all_headings["filter_order"];
		
		$col_count = count($list_headings)+1;
		
		$show_check = true;
		?>
		<table class="flttbl" width=100% id="filter_table">
				<tr>
				<?php 
					foreach($list_headings as $t_key => $t_value){ 
						$filter_name = "";
						
						if(isset($filter_heading[$t_key])){	?>				
					
							<td class="title">		
								<?php  		
										if(isset($filter_heading[$t_key]["label"])){
											echo "<span class=\"fltlbl\">",$filter_heading[$t_key]["label"],"</span><br />";
										}else{
											echo "<br />";	
										}
										$filter_name = "filter_".$t_key;						
										switch($filter_heading[$t_key]["control"]){
											case "text":
												$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'';?>
													<input type="text" name="<?php echo $filter_name ;?>"  <?php echo isset($filter_heading[$t_key]["other"])?$filter_heading[$t_key]["other"]:""; ?> value="<?php echo $filter_value;?>" class="smallinput"/>
												<?php 
											break;
											case "select.genericlist":
												$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'-1';								
												echo JHTML::_('select.genericlist',  $filter_heading[$t_key]["values"], $filter_name, 'class="inputbox"', 'value', 'text', $filter_value);
											break;							
										}						
														
								?>
							</td>				
					<?php }		
						}	?>			
				</tr>
			
			</table>
			<table class="art-data" width="100%" border=1 cellspacing=0 style="border-collapse:collapse;">
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
								<?php switch($t_key){																			
									case "comm_subject": ?>
											<a href="<?php echo $edit_url.$a_result->comm_id ; ?>&ordinal=<?php echo $t_offset; ?>"><?php echo ($a_result->$t_key == -1)?"-":$a_result->$t_key ;?></a>
									<?php											
									break;
									default:																				
										echo ($a_result->$t_key == -1)?"-":$a_result->$t_key;
									break;
									}?>
							</td>				
						<?php	}	?>		
					</tr>						
					<?php
						$k = 1 - $k; $i++;
					}					
					unset($status_list);
				}else{ ?>
					<tr>
						<td align="center" colspan="<?= $col_count+1; ?>">No Results</td>
					</tr>
					
				<?php } ?>		
					</tbody>	
				</table>
				<?php 		
	}
	function renderfilters(&$all_headings){
	
		global $option,$Itemid;
		
		$page = $all_headings["pageNav"];
		$return_data = $all_headings["return_data"];
		$list_headings = $all_headings["headings"];
		$filter_heading = $all_headings["filters"];
		$style_heading = $all_headings["styles"]  ;
		$style_class = $all_headings["tdstyles"]  ;
		
		$lists['order_Dir']	=  $all_headings["filter_order_Dir"];
		$lists['order']		=  $all_headings["filter_order"];
		
		?>
		<table class="flttbl" width=100% id="filter_table">
		<tr>
		<?php
	foreach($list_headings as $t_key => $t_value){
		$filter_name = "";
		
		if(isset($filter_heading[$t_key])){	?>		
		<td class="title">
		<?php
			if(isset($filter_heading[$t_key]["label"])){
				echo "<span class=\"fltlbl\">",$filter_heading[$t_key]["label"],"</span><br />";
			}else{
				echo "<br />";
			}
		$filter_name = "filter_".$t_key;
		switch($filter_heading[$t_key]["control"]){
			case "text":
				$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'';?>
				<input type="text" name="<?php echo $filter_name ;?>"  <?php echo isset($filter_heading[$t_key]["other"])?$filter_heading[$t_key]["other"]:""; ?> value="<?php echo $filter_value;?>" class="smallinput"/>
		<?php
			break;
			case "select.genericlist":
				$filter_value = isset($return_data[$filter_name])?$return_data[$filter_name]:'-1';
				echo JHTML::_('select.genericlist',  $filter_heading[$t_key]["values"], $filter_name, 'class="inputbox"', 'value', 'text', $filter_value);
			break;
			}		
		?>
	</td>
	<?php }
	}	?>
	</tr>		
</table>
	<?php 		
	}
	
}
?>