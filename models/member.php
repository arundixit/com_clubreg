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
 * Club Reg Component Member Model
 *
 */
class ClubRegModelMember extends JModel
{
	var $_id = null;
	var $_data = null;
	
	var $user_data = null;
	var $group_leaders = null;
	var $group_members = null;	
	var $member_details = null;
	var $all_allowed_groups = null;
	var $group_paramslist = null;
	
	function __construct()
	{		
		parent::__construct();
	}
	function getData($joomla_id){
		
		$db		=& JFactory::getDBO();
		$group_leaders = array();
		$d_qry = sprintf("select id,name,email,b.params from 
		%s as b left join 
		#__users as a on b.joomla_id = a.id  where a.id = %d and b.status = 1",CLUB_MEMBERS_TABLE,$joomla_id);
		$db->setQuery($d_qry);
		$user_data = $db->loadObject();	

		if(isset($user_data) && $user_data->id > 0){
		
		$d_qry = sprintf("select a.group_id as value, a.group_name as text from %s as a  where a.group_leader = %d  and a.group_parent =  0 
		order by text",CLUB_GROUPS_TABLE,$joomla_id);
		$db->setQuery($d_qry);
		$group_leaders = $db->loadObjectList("value");
		
		$d_qry = sprintf("select a.group_id as value ,b.group_name as text from %s as a 
		left join %s as b on (a.group_id = b.group_id)
		where a.joomla_id = %d and a.status = 1 order by text",
		CLUB_MEMBERSGROUPS_TABLE, CLUB_GROUPS_TABLE, $joomla_id);
		$db->setQuery($d_qry);
		$group_members = $db->loadObjectList("value");	
		
		$d_qry = sprintf("select a.`joomla_id`, a.`member_detail`, a.`member_value`,b.config_name ,b.config_short
		from %s as a
		inner join %s as b on (a.`member_detail` = b.`config_short`)
		where joomla_id = %d order by b.ordering",CLUB_MEMBERSDETAILS_TABLE,CLUB_TEMPLATE_CONFIG_TABLE,$joomla_id);
		$db->setQuery( $d_qry );
		$member_details = $db->loadObjectList('config_short');
		
		$all_allowed_groups= @array_merge(@array_keys($group_members),@array_keys($group_leaders));

		if(count($all_allowed_groups) > 0){
		
			$d_qry= sprintf("select group_id , params from %s where group_id in (%s)",CLUB_GROUPS_TABLE,implode(",",$all_allowed_groups));
			$db->setQuery($d_qry);
			$group_paramslist = $db->loadObjectList("group_id"); // needed to determine if the group is a junior or senior
		}else{
			$group_paramslist = array();
			$all_allowed_groups = array(-1);
		}
		
		}else{
			$group_leaders = $group_members = $member_details = $all_allowed_groups= $group_paramslist = array();
			
			$this->authorised = false;
		}	
		
		$this->user_data = $user_data;
		$this->group_leaders = $group_leaders;
		$this->group_members = $group_members;	
		$this->member_details = $member_details;	
		$this->all_allowed_groups = $all_allowed_groups;
		$this->group_paramslist = $group_paramslist;
		
		return $this;
		
	}
	
	
}
?>