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
class ClubStatsHelper{
	
	static function return_headings(&$member_data){		
		
		$group_where =  $style_heading =  $style_class = array(); //used to store restrictions on the where clause

		$player_type = trim(JRequest::getVar('playertype','junior', 'request', 'string'));

		
		$all_headings["memberid"] = PLAYER." Id";
		$all_headings["memberlevel"] = PLAYER." Level";		
		$all_headings["surname"] = PLAYER." Name";	
		$all_headings["group"] = GROUPS;
		$all_headings["sgroup"] = SUBGROUPS;	
		$all_headings["year_registered"] = SEASON;

		switch($player_type){
			case "guardian":
			break;
			case "senior":
				$group_where[] = "params  like '%senior%'";
			break;
			case "junior":				
				$group_where[] = "params  like '%junior%'";
			break;
		}
		
		$group_where[] = sprintf(" a.group_id in (%s)",implode(",",$member_data->all_allowed_groups));
		$input_data["group_where"] = $group_where;
		
		
		
		$sorting_heading["comm_subject"]  = array("control"=>"grid.sort","sort_col"=>"a.comm_subject");
		$sorting_heading["created"]  = array("control"=>"grid.sort","sort_col"=>"a.created");
		$sorting_heading["senton"]  = array("control"=>"grid.sort","sort_col"=>"a.sent_date");
		
		
			
		$all_data["headings"] = $all_headings;
		$all_data["sorting"] = $sorting_heading;		
		$all_data["filters"] = self::get_filters_headings($input_data);
		$all_data["styles"] = $style_heading;
		$all_data["tdstyles"] = $style_class;
		
		$all_data["return_data"] =  array();
		
		
		return  $all_data;	
		
	}
	function get_filters_headings($input_data){
		
		$filter_heading = $group_where = array();
		
		$db		=& JFactory::getDBO();	
		
		$filter_heading["memberlevel"] = array("label"=>PLAYER." Level","control"=>"select.genericlist","other"=>"style='width:90px'","filter_col"=>"a.`memberlevel`");
		$filter_heading["surname"] = array("label"=>"Surname","control"=>"text","other"=>"style='width:90px'","filter_col"=>"a.`surname`");
		$filter_heading["group"] = array("label"=>GROUP,"control"=>"select.genericlist","other"=>"style='width:90px'","filter_col"=>"a.`group`");
		$filter_heading["sgroup"] = array("label"=>SUBGROUPS,"control"=>"select.genericlist","other"=>"style='width:90px'","filter_col"=>"a.`subgroup`");
		$filter_heading["gender"] = array("label"=>"Gender","control"=>"select.genericlist","other"=>"style='width:100px'","filter_col"=>"a.`gender`");
		$filter_heading["year_registered"] = array("label"=>SEASON,"control"=>"select.genericlist","other"=>"style='width:100px'","filter_col"=>"a.`year_registered`");
		
		$tmp_list = array();
		
		$query = sprintf("select -1 as value, '-".PLAYER." Level -' as text union  (select  `config_short` as value,`config_name` as text
				from %s as a where which_config = '%s' and publish = 1 order by text asc ) order by text asc ",
				CLUB_TEMPLATE_CONFIG_TABLE,CLUB_PLAYER_LEVEL);
		
		$db->setQuery( $query );
		$tmp_list = $db->loadObjectList('value');
		$filter_heading["memberlevel"]["values"] = $tmp_list;
		
		
		unset($tmp_list);
		
		$group_where = $input_data["group_where"];
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
		
		$filter_heading["year_registered"]["values"] = ClubregHelper::generate_seasonList();
		
		return $filter_heading;
		
	}
	function renderStatsList($stats_data,&$player_data){	
		$stats_headings = $stats_data["headings"];
		global $option,$Itemid;	?>
	<table class="smaller_table" width="100%" border=0 cellspacing=2  cellpadding=2>
		<tr>			
			<th width=150><?php echo STATS; ?> Date</th>
				<?php foreach($stats_headings as $a_key => $a_value) {?>
						<th width="10%"><?php echo $a_value; ?></th>
				<?php } ?>
				
		</tr>
		<?php 
		$stats_url = sprintf("index2.php?option=%s&c=stats&task=editstats&Itemid=%s&member_id=%s&no_html=0&path=1&%s=1",$option,$Itemid,$player_data->member_id,JUtility::getToken());
		
				$k= $i = 1; $cl_ = array("row0","row1"); $col_count = count($stats_headings);
			if(count($stats_data["stats_list"]) > 0 ){ 
				$heading_obj = $stats_data["headings_obj"];
				foreach($stats_data["stats_list"] as $a_stats){
					$a_stats->key_var = sprintf("&stats_date=%s",str_replace("/", ".", $a_stats->stats_date));
					?>
				<tr class="<?php echo $cl_[$k];?>" id="stats_<?php echo $a_stats->key_var; ?>">					
					<td class="<?php echo $cl_[$k];?>">
					<a href="javascript:void(0);" onclick="process_stats('<?php echo $a_stats->key_var; ?>','<?php echo JUtility::getToken()?>')">
						<?php echo JHTML::_('image', 'components/com_clubreg/assets/images/delete.png', JText::_( 'Delete' ), array('align' => 'right')); ?>
						</a>
					<a href="<?php echo $stats_url.$a_stats->key_var;?>" class="modal-button" rel="{handler: 'iframe', size: {x: 400, y: 400}}" style="font-weight:normal">
						<?php echo $a_stats->stats_date?>
					</a>	
					</td>					
						
					<?php 
						foreach ($stats_headings as $a_key => $a_value ){ ?>
							<td style="padding-left:10px;"><?php ClubRegModelStats::renderControl_view($heading_obj[$a_key],$a_stats);?></td>
							<?php 							
						}					
					?>
				</tr>
		<?php  $i++; $k= 1- $k; }
				}else{  ?>
			<tr>
				<td align="center" colspan="<?= $col_count+1; ?>" class="center isReq"><h3>No <?php echo STATS; ?> Results</h3></td>
			</tr>			
		<?php } ?>
	</table>
	<?php
	
	}
	
}