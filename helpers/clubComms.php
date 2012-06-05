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
		//$all_data["filters"] = self::get_filters_headings($input_data);
		$all_data["styles"] = $style_heading;
		$all_data["tdstyles"] = $style_class;
		
		$all_data["return_data"] = $all_data["filters"] = array();
		
		
		return  $all_data;
		
		
		
	}
}