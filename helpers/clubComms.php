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
		
		$all_headings["sentto"] = "Sent To";
		$all_headings["comm_subject"] = "Subject";
		$all_headings["comm_message"] = "Message";
		$all_headings["created"] = "Created On";
		$all_headings["created_by"] = "Created By";
		$all_headings["senton"] = "Sent On";
		
		$all_headings["status"] = "Status";
		
		$all_data["headings"] = $all_headings;
		return  $all_data;
		
		
		
	}
}