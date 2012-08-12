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
 * Club Reg Component Groups Model
 *
 */
class ClubRegModelGroups extends JModel
{
	var $_id = null;
	var $_data = null;
	
	
	var $group_data = null;
	var $group_members = null;	
	var $team_members = null;
	
	var $member_details = null;
	var $all_allowed_groups = null;
	var $group_paramslist = null;
	
	function __construct()
	{		
		parent::__construct();
	}
	function getData($in_data){
		
		$db		=& JFactory::getDBO();
		
		$group_id = $in_data['group_id'];	
		
		if($group_id > 0 ){
		
			$d_qry = sprintf("select a.group_id, a.group_name,a.group_text,a.params,
			a.group_leader, b.name as group_leader_name from %s as a left join #__users as b on (a.group_leader = b.id)
			
			where group_id = %d",CLUB_GROUPS_TABLE,$group_id);
			
			$db->setQuery($d_qry);
			$group_data = $db->loadObject();		
				
			$d_qry = sprintf("select a.joomla_id, b.name as group_member_name from %s as a  left join #__users as b on (a.joomla_id = b.id)
			where  group_id = %d and  a.status = 1 and a.joomla_id != %d ",CLUB_MEMBERSGROUPS_TABLE,$group_id, $group_data->group_leader );
			
			$db->setQuery($d_qry);
			$team_members = $db->loadObjectList("joomla_id");		
			
			
			$d_qry = sprintf("select concat(a.surname,' ', a.givenname) as member_name,member_id from %s as a where `group` = %d and year_registered = '%s'
			order by concat(a.surname,' ', a.givenname)	",
			CLUB_REGISTEREDMEMBERS_TABLE,$group_id,$in_data['reg_year'] );
			
			$db->setQuery($d_qry);
			$group_members = $db->loadObjectList("member_id");		
		}else{
			$group_data = $group_members = $team_members = array();
		}
		
		$this->group_data = $group_data;		
		$this->team_members = $team_members;
		$this->group_members = $group_members;	
		
		return $this;
		
	}
	function load_subgrougs($group_id){
		
		$db		=& JFactory::getDBO();
		$subgroups = array();
		
		$d_qry = sprintf("select -1 as value, '- Select ".SUBGROUP." -' as text union 
		 select group_id as value, group_name as text from %s where group_parent = %d  and  group_parent != 0 and publish = 1 order by group_name ",
		CLUB_GROUPS_TABLE, $group_id);
		$db->setQuery($d_qry);
		$subgroups = $db->loadObjectList();
			
		return $subgroups;
		
	}
	
	
}
?>