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
		
class ClubregHelper{
	
	static function generateMenuItems(){
		
		$tmp_array = array();
		return $tmp_array;		
	}	
	
	static function write_footer(){
		?>
		<small><?php echo JText::_("Designed By ")?><a href="http://<?php echo  OUR_WEBSITE; ?>">http://<?phP echo DESIGNED_BY ?></a></small>
		<?php 
	}
	
	
	static function generate_menu_tabs($member_params,$page_title){
		global $option,$Itemid;
		$c= trim(JRequest::getVar('c','', 'request', 'string'));
		?>
		<div style="font-weight:bold;font-size:1.5em;padding-left:4px;float:left;"><?php echo $page_title; ?></div><p class="cl"></p>
		<div  id="userNav_clubreg">		
			<ol>
			<?php 
				 if( $member_params->get( 'vieweoi' ) == "yes" ){ ?>
				 <li >
				 	<a href="index.php?option=<?php echo $option; ?>&view=eoi&layout=loadeoi&c=eoi&task=loadeoi&Itemid=<?php echo $Itemid; ?>" <?php echo  ($c=="eoi")?"class=\"acts\"":""; ?>><span>Manage Expression of Interest</span></a>
				</li>	
				<?php } 
					if( $member_params->get( 'manageusers' ) == "yes" ){ ?>					
					 <li>	
					 	<a href="index.php?option=<?php echo $option; ?>&c=userreg&task=loadregistered&Itemid=<?php echo $Itemid; ?>&limit=20" <?php echo  ($c=="userreg")?"class=\"acts\"":""; ?>><span>Registered <?php echo PLAYERS?></span></a>
					 </li>
					<?php } 
					if( $member_params->get( 'manageusers1' ) == "yes" ){ ?>
					 <li >
					 	<a href="index.php?option=<?php echo $option; ?>&c=workflow&task=loadworkflow&Itemid=<?php echo $Itemid; ?>" <?php echo  ($c=="workflow")?"class=\"acts\"":""; ?>><span>Manage Workflow</span></a>
					</li>	
					<?php } 
					if( $member_params->get( 'sendcommunication' ) == "yes" ){ ?>
					 <li class="last">	
					 	<a href="index.php?option=<?php echo $option; ?>&c=comms&task=listcomms&Itemid=<?php echo $Itemid; ?>" <?php echo  ($c=="comms")?"class=\"acts\"":""; ?>><span>Send Communications</span></a>
					 </li>
					<?php }  ?>			
			
			</ol>	
			</div>			
				
			<?php 		
		}
	/**
	 * 
	 * 	generate the headings to be rendered on the table
	 * @return string
	 */
	static function return_headings(){	
			
		$db		=& JFactory::getDBO();
		
		$all_headings["surname"] = "Name";		
		
		$player_type = trim(JRequest::getVar('playertype','junior', 'request', 'string'));
		$group_where = $style_heading = array();
		switch($player_type){
			case "guardian":
				$all_headings["address"] = "Address";
				$all_headings["suburb"] = "Town";
				$all_headings["postcode"] = "Postcode";
				$all_headings["emailaddress"] = "Email";
				$all_headings["phoneno"] = "Phone #";
				$all_headings["send_news"] = "Send Emails";				
				
				$all_headings["my_children"] = "Registered Juniors";
				$style_heading["my_children"] = " style='white-space:nowrap'";
				
				
			break;
			case "junior":
				$all_headings["guardian"] = "Guardian";
				$all_headings["gender"] = "Gender";
				$all_headings["dob"] = "DOB";
				$all_headings["group"] = GROUPS;
				
				$group_where[] = "params  like '%junior%'";
				
			break;
			case "senior":	
				$all_headings["address"] = "Address";
				$all_headings["suburb"] = "Town";
				$all_headings["postcode"] = "Postcode";
				$all_headings["emailaddress"] = "Email";
				$all_headings["phoneno"] = "Phone #";
				$all_headings["gender"] = "Gender";
				$all_headings["send_news"] = "Send Emails";
				$all_headings["group"] = GROUPS;
				
				$group_where[] = "params  like '%senior%'";
			break;
			default:
				$all_headings["playertype"] = PLAYER." Type";
				$all_headings["group"] = GROUPS;
			break;
			
		}
		
		$input_data["group_where"] = $group_where;
		
		$all_headings["t_created_date"] = "Date EOI";
		
		
		$sorting_heading["surname"]  	= array("control"=>"grid.sort","sort_col"=>"a.surname");	
		$sorting_heading["t_created_date"]  = array("control"=>"grid.sort","sort_col"=>"a.created");
		$sorting_heading["playertype"]  = array("control"=>"grid.sort","sort_col"=>"a.playertype");		
		
		
		$all_data["headings"] = $all_headings;
		$all_data["sorting"] = $sorting_heading;		
		$all_data["filters"] = self::get_filters_headings($input_data);
		$all_data["styles"] = $style_heading;
		
		unset($tmp_list);
			
		return $all_data;
	
	}
	/**
	 * 
	 *  get headings for registered users page
	 * @return string
	 */
	static function return_headings_reg($member_data){
			
		$db		=& JFactory::getDBO();
	
		$all_headings["memberid"] = PLAYER." Id";
		$all_headings["memberlevel"] = PLAYER." Level";
		
		$all_headings["surname"] = PLAYER." Name";
	
		$player_type = trim(JRequest::getVar('playertype','junior', 'request', 'string'));
		$group_where =  $style_heading =  $style_class = array(); //used to store restrictions on the where clause
		
		$style_class["surname"] = "style='white-space:nowrap;'";
		switch($player_type){
			case "guardian":
				unset($all_headings["memberid"]);
				$all_headings["address"] = "Address";
				$all_headings["suburb"] = "Suburb";
				$all_headings["postcode"] = "Postcode";
				$all_headings["emailaddress"] = "Email";
				$all_headings["phoneno"] = "Phone #";
				$all_headings["send_news"] = "Send Emails";
	
				$all_headings["my_children"] = "Registered Juniors";
				$style_heading["my_children"] = " style='white-space:nowrap'";	
				
				break;
			case "junior":
				
				$all_headings["guardian"] = "Guardian";
				
				$all_headings["gaddress"] = "Address";
				$all_headings["gsuburb"] = "Suburb";
				$all_headings["gpostcode"] = "Postcode";
				
				
				$all_headings["gender"] = "Gender";
				$all_headings["dob"] = "DOB";
				$all_headings["group"] = GROUPS;
				$all_headings["sgroup"] = SUBGROUPS;			
	
				$group_where[] = "params  like '%junior%'";	
				break;
			case "senior":
				
				$all_headings["address"] = "Address";
				$all_headings["suburb"] = "Suburb";
				$all_headings["postcode"] = "Postcode";
				$all_headings["emailaddress"] = "Email";
				$all_headings["phoneno"] = "Phone #";
				$all_headings["gender"] = "Gender";
				$all_headings["send_news"] = "Send Emails";
				$all_headings["group"] = GROUPS;
				$all_headings["sgroup"] = SUBGROUPS;
				
				//$style_class["group"] = " class='inplace'";
	
				$group_where[] = "params  like '%senior%'";
				break;
			default:
				$all_headings["playertype"] = PLAYER." Type";
				$all_headings["group"] = GROUPS;
			break;				
		}		
		
		$group_where[] = sprintf(" a.group_id in (%s)",implode(",",$member_data->all_allowed_groups));
		
		$input_data["group_where"] = $group_where; //
		
		$all_headings["year_registered"] = SEASON;
	
		$all_headings["t_created_date"] = "Date Registered";
		$all_headings["t_created_by"] = "Registered By";
	
	
		$sorting_heading["surname"]  	= array("control"=>"grid.sort","sort_col"=>"a.surname");
		$sorting_heading["t_created_date"]  = array("control"=>"grid.sort","sort_col"=>"a.created");
		$sorting_heading["playertype"]  = array("control"=>"grid.sort","sort_col"=>"a.playertype");		
		$sorting_heading["guardian"]  = array("control"=>"grid.sort","sort_col"=>"d.surname");		
	
		$all_data["headings"] = $all_headings;
		$all_data["sorting"] = $sorting_heading;
		$all_data["filters"] = self::get_filters_headings($input_data);
		$all_data["styles"] = $style_heading;
		$all_data["tdstyles"] = $style_class;
	
		unset($tmp_list);
			
		return $all_data;
	
	}
	static function get_filters_headings($input_data){
		
		$filter_heading = array();
		
		$db		=& JFactory::getDBO();
		
		$group_where = $input_data["group_where"];
		
		$filter_heading["surname"] = array("label"=>"Surname","control"=>"text","other"=>"style='width:90px'","filter_col"=>"a.`surname`");
		$filter_heading["address"] = array("label"=>"Address","control"=>"text","other"=>"style='width:90px'","filter_col"=>"a.`address`");
		$filter_heading["suburb"] = array("label"=>"Suburb","control"=>"text","other"=>"style='width:90px'","filter_col"=>"a.`suburb`");
		$filter_heading["postcode"] = array("label"=>"PostCode","control"=>"text","other"=>"style='width:40px'","filter_col"=>"a.`postcode`");
		
		//guardian details
		$filter_heading["gaddress"] = array("label"=>"Address","control"=>"text","other"=>"style='width:90px'","filter_col"=>"d.`address`");
		$filter_heading["gsuburb"] = array("label"=>"Suburb","control"=>"text","other"=>"style='width:90px'","filter_col"=>"d.`suburb`");
		$filter_heading["gpostcode"] = array("label"=>"PostCode","control"=>"text","other"=>"style='width:40px'","filter_col"=>"d.`postcode`");
		
		$filter_heading["emailaddress"] = array("label"=>"Email","control"=>"text","other"=>"style='width:100px'","filter_col"=>"a.`emailaddress`");
		$filter_heading["memberlevel"] = array("label"=>PLAYER." Level","control"=>"select.genericlist","other"=>"style='width:90px'","filter_col"=>"a.`memberlevel`");
		
		
		$filter_heading["group"] = array("label"=>GROUP,"control"=>"select.genericlist","other"=>"style='width:90px'","filter_col"=>"a.`group`");
		
		$filter_heading["sgroup"] = array("label"=>SUBGROUPS,"control"=>"select.genericlist","other"=>"style='width:90px'","filter_col"=>"a.`subgroup`");
		
		$filter_heading["gender"] = array("label"=>"Gender","control"=>"select.genericlist","other"=>"style='width:100px'","filter_col"=>"a.`gender`");
				
		$filter_heading["t_created_date"] = array("label"=>"Date Rng","control"=>"select.genericlist","other"=>"style='width:100px'","filter_col"=>"a.`created`");
		$filter_heading["year_registered"] = array("label"=>SEASON,"control"=>"select.genericlist","other"=>"style='width:100px'","filter_col"=>"a.`year_registered`");
				
		$query = sprintf("select -1 as value, '-".PLAYER." Level -' as text union  (select  `config_short` as value,`config_name` as text
				from %s as a where which_config = '%s' and publish = 1 order by text asc ) order by text asc ",
				CLUB_TEMPLATE_CONFIG_TABLE,CLUB_PLAYER_LEVEL);
		
		$db->setQuery( $query );
		$tmp_list = $db->loadObjectList('value');
		$filter_heading["memberlevel"]["values"] = $tmp_list;
		
		unset($tmp_list);
		$tmp_list = array();$group_where_str = "";
		$group_where[] = "publish=1";
		$group_where[] = "group_parent = 0";
		
		$group_where_str = "where ".implode(" and ", $group_where);
		
		$query = sprintf("select -1 as value, '-".GROUPS."-' as text union  (select  `group_id` as value,`group_name` as text
							from %s as a %s  order by text asc ) order by text asc ",CLUB_GROUPS_TABLE,$group_where_str);
		
		$db->setQuery( $query );
		$tmp_list = $db->loadObjectList();
		$filter_heading["group"]["values"] = $tmp_list;	
		
		unset($tmp_list);
		unset($group_where);		
		
		$tmp_list = array();$group_where_str = "";
		$group_where[] = "publish=1";
		$group_where[] = "group_parent != 0";
		
		$group_where_str = "where ".implode(" and ", $group_where);
		
		$query = sprintf("select -1 as value, '-".SUBGROUPS."-' as text union  (select  `group_id` as value,`group_name` as text
									from %s as a %s  order by text asc ) order by text asc ",CLUB_GROUPS_TABLE,$group_where_str);
		
		$db->setQuery( $query );
		$tmp_list = $db->loadObjectList();
		$filter_heading["sgroup"]["values"] = $tmp_list;
		
		unset($tmp_list);
		unset($group_where);		
		
		$tmp_list = array();
		$tmp_list['-1'] = JHTML::_('select.option',  '-1', JText::_( '-Gender-' ) );
		$tmp_list['male'] = JHTML::_('select.option',  'male', JText::_( 'Male' ) );
		$tmp_list['female'] = JHTML::_('select.option',  'female', JText::_( 'Female' ) );
		
		$filter_heading["gender"]["values"] = $tmp_list;
		
		unset($tmp_list);
		
		$tmp_list = array();
		$tmp_list['-1'] = JHTML::_('select.option',  '-1', JText::_( '-Dates-' ) );
		$tmp_list['today'] = JHTML::_('select.option',  'today', JText::_( 'Today' ) );
		$tmp_list['7days'] = JHTML::_('select.option',  '7days', JText::_( 'Last 7 Days' ) );
		$tmp_list['month'] = JHTML::_('select.option',  'month', JText::_( 'This Month' ) );
		$tmp_list['lastmonth'] = JHTML::_('select.option',  'lastmonth', JText::_( 'Last Month' ) );
		
		$filter_heading["t_created_date"]["values"] = $tmp_list;
		
		$filter_heading["year_registered"]["values"] = self::generate_seasonList();
		
		unset($tmp_list);
		
		return $filter_heading;
		
	}
	
	static function save_old_data($old_data,$other_details){
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		foreach($old_data as $t_key => $t_value){
			if($t_key[0] == "_") continue;
			$new_data->$t_key = $old_data->$t_key;
		}
		$audit_data = serialize($new_data);		
		
		$created = date("Y-m-d H:i:s");
		
		$d_qry = sprintf("insert into %s set primary_id = '%d', short_desc=%s, audit_details = %s, 
		created_date ='%s',created_time= '%s',createdby = '%d'",CLUB_AUDIT_TABLE,
		$other_details["primary_id"],$db->Quote($other_details["short_desc"]),$db->Quote($audit_data),
		$created,$created,$user->id);
		$db->setQuery($d_qry);
		$db->query();
		
	}
	static function renderLastUpdate($last_update){
		if($last_update){
			?>
			<div align=right style="color:#006699;font-size:11px;">
				Last Updated by <?php echo $last_update->name ; ?>  on <?php echo $last_update->created_date; ?>
			</div>	
		<?php 					
		}		
	}
	static function generate_seasonList(){
		$cy = date('Y');$t_array = array();
		$t_object = new stdClass() ; $t_object->value = '0'; $t_object->text = '-'.SEASON.'-';
		$t_array[] = $t_object;
		for($i = $cy -5 ; $i < $cy+5 ; $i++ ){
			$t_object = new stdClass() ; $t_object->value = $i; $t_object->text = $i;
			$t_array[] = $t_object;
		}
		return $t_array;
		
	}
	static function getMemberLevels(){
		$t_array = array();
		
		$t_object = new stdClass() ; $t_object->value = '0'; $t_object->text = ' - '.PLAYER.' Level - ';
		$t_array['0'] = $t_object;
		
		
		$d_qry = sprintf("select config_short as value, config_name as text from %s where which_config = '%s' and publish = 1 order by config_name",CLUB_TEMPLATE_CONFIG_TABLE,CLUB_PLAYER_LEVEL);
		$t_array = array_merge($t_array,get_a_list($d_qry,'value'));
		
		return $t_array;
		
	}
	static function getPlayerExtraDetails(){
		$t_array = array();
	
		$t_object = new stdClass() ; $t_object->value = '0'; $t_object->text = ' - '.PLAYER.' Level - ';
		$t_array['0'] = $t_object;
	
	
		$d_qry = sprintf("select * from %s where which_config = '%s' and publish = 1 order by ordering",
				CLUB_TEMPLATE_CONFIG_TABLE,CLUB_PLAYER_DETAILS);
		
		$t_array = get_a_list($d_qry,'config_short');//array_merge($t_array,get_a_list($d_qry,'value'));
	
		return $t_array;
	
	}
	
} 
class ClubPaymentsHelper{
	
	static function getPaymentMethods(){		
		
		$t_array = array();
		
		$t_object = new stdClass() ; $t_object->value = '0'; $t_object->text = ' - Payment Method - ';
		$t_array['0'] = $t_object;
		
		
		$d_qry = sprintf("select config_short as value, config_name as text from %s where which_config = 'club_payment_method' and publish = 1 order by ordering",CLUB_TEMPLATE_CONFIG_TABLE);
		$t_array = array_merge($t_array,get_a_list($d_qry,'value'));		
		
		return $t_array;
		
	}
	static function getPaymentStatus(){
		
		$t_array = array();
		
		$t_object = new stdClass() ; $t_object->value = '0'; $t_object->text = ' - Payment Status - ';
		$t_array['0'] = $t_object;
		
		
		$d_qry = sprintf("select config_short as value, config_name as text from %s where which_config = 'club_payment_status' and publish = 1 order by ordering",CLUB_TEMPLATE_CONFIG_TABLE);
		$t_array = array_merge($t_array,get_a_list($d_qry,'value'));		
		
		return $t_array;
		
	}
	
	static function getPaymentDescription(){
		
		$t_array = array();
		
		$t_object = new stdClass() ; $t_object->value = '0'; $t_object->text = ' - Description - ';
		$t_array['0'] = $t_object;
		
		
		$d_qry = sprintf("select config_short as value, config_name as text from %s where which_config = 'club_payment_desc' and publish = 1 order by ordering",CLUB_TEMPLATE_CONFIG_TABLE);
		$t_array = array_merge($t_array,get_a_list($d_qry,'value'));		
		
		return $t_array;
	}
	static function getPaymentList($player_data){
		$db		=& JFactory::getDBO();
		
		$d_qry = sprintf("select a.*,
		date_format(a.payment_date, '%%d/%%m/%%Y') as payment_date,
		date_format(a.created, '%%d/%%m/%%Y %%H:%%i:%%s') as created, b.name from %s as a left join #__users as b on 
		a.created_by = b.id
		 where member_id = %d order by a.created desc ",CLUB_PAYMENTS_TABLE,$player_data->member_id);
		$db->setQuery($d_qry);
		$all_payments = $db->loadObjectList();
		return $all_payments;
		
	}
	static function renderPaymentList($all_payments,&$player_data){
		global $option,$Itemid;
		?>
		<table class="art-data" width="100%" border=1 cellspacing=0 style="border-collapse:collapse;">
		<tr>
		<th width=10>#</th>
		<th>Payment method</th>
		<th>Transaction #</th>
		<th><?php echo SEASON; ?></th>
		<th>Status</th>
		<th>Payment Date</th>
		<th>Item Desc</th>
		<th>Notes</th>
		<th>Amount</th>
		<th>Created </th>		
		</tr>
		<tr>
		<?php $k= $i = 1;
		
			$payment_method = self::getPaymentMethods();
			$payment_status = self::getPaymentStatus();
			$payment_desc = self::getPaymentDescription(); $cl_ = array("row0","row1");
			
			
			$payment_url = sprintf("index2.php?option=%s&c=userreg&task=editpayment&Itemid=%s&member_id=%s&no_html=0&path=&%s=1&payment_id=",$option,$Itemid,$player_data->member_id,JUtility::getToken());
				if(count($all_payments) > 0 ){
			foreach($all_payments as $a_payment){ $ttotal += $a_payment->payment_amount; ?>
		<tr class="<?php echo $cl_[$k];?>">
			<td><?php echo $i ; ?></td>
			<td>
				<a href="<?php echo $payment_url.$a_payment->payment_id;?>" class="modal-button" rel="{handler: 'iframe', size: {x: 400, y: 400}}" style="font-weight:normal">			
					<?php echo isset($payment_method[$a_payment->payment_method])?$payment_method[$a_payment->payment_method]->text:""; ?>
				</a>
			</td>
			<td><?php echo $a_payment->payment_transact_no;  ?></td>
			<td><?php echo $a_payment->payment_season;  ?></td>
			<td><?php echo isset($payment_status[$a_payment->payment_status])?$payment_status[$a_payment->payment_status]->text:""; ?></td>
			<td><?php echo $a_payment->payment_date; ?></td>
			<td><?php echo isset($payment_desc[$a_payment->payment_desc])?$payment_desc[$a_payment->payment_desc]->text:""; ?></td>
			<td><?php echo nl2br(stripslashes($a_payment->payment_notes)); ?></td>
			<td align=right><?php echo self::write_money($a_payment->payment_amount * 0.01); ?> </td>			
			<td><?php echo $a_payment->name; ?> On<br /> <?php echo $a_payment->created; ?></td>	
		</tr>
		
		<?php $i++; $k= 1- $k; } 
				}else{
				?>
				<tr>
					<td colspan="10" class="center isReq"><h3>No Payments Yet</h3></td>
				</tr>
				<?php 	
				}
		
		?>
		
		</table>
		<br />
		
		<?php 
	}
	static function write_money($d_value){
		return number_format($d_value, 2, '.', ',');
	}	
	
}
class ClubContactHelper{

	static function getContactArray(){

		$control_array["contact_items"] =  array("surname","givenname","emailaddress","phoneno","mobile","address","suburb","postcode");
		$special["em_"] = array("medical");
		$special["next_"] = array();
		$control_array["special"] = $special;
		return $control_array;
	}
}
?>