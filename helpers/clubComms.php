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
class ClubCommsHelper{
	
	static function return_headings_comms(){		
		
		$group_where =  $style_heading =  $style_class = array(); //used to store restrictions on the where clause		
		
		$all_headings["sentto"] = "Sent To";
		$all_headings["comm_subject"] = "Subject";
		$all_headings["comm_message"] = "Message";
		$all_headings["created"] = "Created On";
		$all_headings["created_by"] = "Created By";
		$all_headings["senton"] = "Sent On";		
		$all_headings["comm_status"] = "Status";		
		
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
		
		$filter_heading["comm_subject"] = array("label"=>"Subject","control"=>"text","other"=>"style='width:150px'","filter_col"=>"a.`comm_subject`");
		$filter_heading["comm_message"] = array("label"=>"Message","control"=>"text","other"=>"style='width:150px'","filter_col"=>"a.`comm_message`");
		$filter_heading["sentto"] = array("label"=>'Sent To',"control"=>"select.genericlist","other"=>"style='width:120px'","filter_col"=>"a.`comm_groups`");
		$filter_heading["comm_status"] = array("label"=>'Comm Status',"control"=>"select.genericlist","other"=>"style='width:100px'","filter_col"=>"a.`comm_status`");
		
		
		$tmp_list = array();
		$tmp_list['-1'] = JHTML::_('select.option',  '-1', JText::_( '-select-' ) );
		$tmp_list['0'] = JHTML::_('select.option',  '0', JText::_( 'Not Sent' ) );
		$tmp_list['1'] = JHTML::_('select.option',  '1', JText::_( 'Sent' ) );
		$tmp_list['99'] = JHTML::_('select.option',  '99', JText::_( 'Deleted' ) );
		
		$filter_heading["comm_status"]["values"] = $tmp_list;
		
		unset($tmp_list);
		
		$tmp_list = array();$group_where_str = "";
		$group_where[] = "publish=1";
		$group_where[] = "group_parent = 0";
		//$group_where[] = " group_id in (".implode(",",$member_data->all_allowed_groups).")";
			
		
		$group_where_str = "where ".implode(" and ", $group_where);
		
		$query = sprintf("select -1 as value, '-".GROUPS."-' as text union  (select  `group_id` as value,`group_name` as text
				from %s as a %s  order by text asc ) order by text asc ",CLUB_GROUPS_TABLE,$group_where_str);
		
		$db->setQuery( $query );
		$tmp_list = $db->loadObjectList();
		$filter_heading["sentto"]["values"] = $tmp_list;
		
		unset($tmp_list);
		
		return $filter_heading;
		
	}
}