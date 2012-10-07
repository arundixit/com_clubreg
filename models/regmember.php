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

jimport('joomla.application.component.model');

/**
 * Club Reg Component Communication Model
 *
 */
class ClubRegModelRegmember extends JModel
{
	var $contact_details = null;
	var $reg_details = null;
	var $parent_details = null;
	var $tag_list = null;
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getData($in_data){
	
		$db		=& JFactory::getDBO();
		$member_id = $in_data['member_id'];
		
		if($member_id > 0){
			$var_str[] = "member_id";
			$var_str[] = "memberid";
			$var_str[] = "memberlevel";
			
			$var_str[] = "surname";			
			$var_str[] = "givenname";
			
			$var_str[] = "concat(givenname,' ',surname) as fullname";
					
			
			$var_str[] = "address";
			$var_str[] = "suburb";			
			$var_str[] = "postcode";
			
			$var_str[] = " concat(address,'<br />',suburb,' ',postcode ) as faddress";
			
			
			$var_str[] = "mobile";
			$var_str[] = "phoneno";
			$var_str[] = "emailaddress";
			
			$var_str[] = "send_news";
			$var_str[] = "date_format(dob,'%d/%m/%Y') as dob";
			
			$var_str[] = "b.group_name";
			$var_str[] = "c.group_name as s_group_name";
			
			$var_str[] = "date_format(a.created,'%d/%m/%Y') as reg_created";
			$var_str[] = "d.name as createdby";
			
			$var_str[] = "year_registered";
			$var_str[] = "parent_id";
			$var_str[] = "playertype";
			$var_str[] = "created_by";				
			
			$var_string = implode(", ",$var_str);
			
			$d_qry = sprintf("select %s from %s as a 
			left join %s as b on (a.`group` = b.group_id)
			left join %s as c on (a.subgroup = c.group_id)
			left join #__users as d on (d.id = a.created_by)
			where a.member_id = %d",
			$var_string ,				
			CLUB_REGISTEREDMEMBERS_TABLE,
			CLUB_GROUPS_TABLE,
			CLUB_GROUPS_TABLE,
			$member_id);
			$db->setQuery($d_qry);
			$this->reg_details = $db->loadObject();

			if($this->reg_details->playertype == "junior"){
				//$junior_data["member_id"] = $this->reg_details->parent_id;
				//$this->parent_details = $this->getData($junior_data);				
			}
			
			$d_qry = sprintf("select a.`member_id`, a.`contact_detail`, a.`contact_value`
					from %s as a
					where member_id = %d",CLUB_CONTACT_TABLE,$member_id);
			$db->setQuery( $d_qry );
			$this->contact_details = $db->loadObjectList('contact_detail');	
			
			$d_qry = sprintf("select a.tag_id, a.tag_text,member_id from %s as a left join %s as b on (a.tag_id = b.tag_id)
					where member_id = %d order by a.tag_text asc",
					CLUB_TAG_TABLE,CLUB_TAGPLAYER_TABLE,$member_id);
			$db->setQuery($d_qry);
			$this->tag_list = $db->loadObjectList();
			
		}
		
	}
	
}