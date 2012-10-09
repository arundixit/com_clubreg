<?php 
global $colon; 
$reg_details = $this->member_data->reg_details;
?>
<div class="n">
	<div class="taghd"><?php echo GROUP; ?> Details</div>
		<div class="div_table">
			<table class="reg_details" >
				<tr>	
					<td class="render_label" ><?php echo GROUP; ?></td><td class="reg_colon"><?php echo $colon ;?></td><td><?php echo $reg_details->group_name ?></td>
				</tr>
				<tr>
					<td class="render_label" ><?php echo SUBGROUP;?></td><td class="reg_colon"><?php echo $colon ;?></td><td><?php echo $reg_details->s_group_name ?></td>
				</tr>
				<tr>
					<td class="render_label" ><?php echo SEASON; ?></td><td class="reg_colon"><?php echo $colon ;?></td><td ><?php echo $reg_details->year_registered ?></td>
				</tr>
		</table>
		</div>
</div>	
<p class="cl"></p>
