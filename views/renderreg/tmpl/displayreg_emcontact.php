<?php
$contact_key = $this->contact_details->contact_key;
$tmp_contact_array = ClubContactHelper::getContactArrayRenderer();

$final_contact_array = array_merge($tmp_contact_array["contact_items"],$tmp_contact_array["special"][$contact_key]);
$property_array = $tmp_contact_array["property"];
$contact_details = $this->member_data->contact_details;
?>
<div class="div_table">
		<table class="reg_details" >		
		<?php foreach($final_contact_array as $tkey => $t_value){?>
			<tr>
				<td class="render_label" ><?php echo ucwords($final_contact_array[$tkey]); ?></td><td class="reg_colon"><?php echo $colon ;?></td>
				<td><?php if(isset($property_array[$tkey])){
						//write_debug($property_array[$tkey]);
						foreach($property_array[$tkey] as $prop_key){
							$nkey = $contact_key.$prop_key;
							echo isset($contact_details[$nkey])?trim(ucwords($contact_details[$nkey]->contact_value))." ":"-" ;
						}
				
				}else{
						$nkey = $contact_key.$tkey;
						echo isset($contact_details[$nkey])?trim($contact_details[$nkey]->contact_value):"-";
					} ?></td>
			</tr>
		<?php } ?>
		</table>
</div>
<?php 