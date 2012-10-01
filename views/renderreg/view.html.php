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

jimport( 'joomla.application.component.view');

class ClubRegViewrenderreg extends JView
{

	function display($tpl = null){			
	
		global $mainframe,$option,$Itemid;
		error_reporting(-1);
		
		$edit_url = array();
		
		JHTML::_('script', 'group.js?'.time(), 'components/com_clubreg/assets/js/'); // add the subgroup filter javascript
		JHTML::_('script', 'registration.js?'.time(), 'components/com_clubreg/assets/js/');
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();	
		
		$session =& JFactory::getSession();
		$d_url_ = $session->get("com_clubreg.back_url");
		
		$back_url = sprintf("index.php?option=%s&c=userreg&task=loadregistered&Itemid=%d&%s",$option,$Itemid,@implode("&",$d_url_));
		
		
		$d_sql_ = $session->get("com_clubreg.back_sql");			
		
		$this->assign("back_url",$back_url);
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$return_data['member_id'] = intval(JRequest::getVar('member_id','0', 'request', 'int'));	

		
		$this->assign("edit_url",array());
		$return_data['ordinal'] = intval(JRequest::getVar('ordinal','0', 'request', 'int'));
		
		if($d_sql_ && $return_data['member_id'] > 0){
		
			$howmany = 3;
			$offset = 0;
				
			if(($return_data['ordinal'] - $offset) <= 1){
				$lm_start = 0;
				$howmany = 2;
		
				$check_counter = false; // when you are the begining of the list
			}else{
				$lm_start = $return_data['ordinal']-2;
				$check_counter = true; // when you are at the end of the list
			}
				
			$d_qry = sprintf("SET @pos=%d;",$lm_start);
			$db->setQuery($d_qry);
			$db->query();
				
			$d_sql_ = str_replace("a.*", "@pos:=@pos+1 as ordinal,a.*", $d_sql_);
				
			$db->setQuery( $d_sql_, $lm_start, $howmany  );
			$recordset = $db->loadObjectList();
				
			if($check_counter){
				if(count($recordset) == 2){
					$a_record = $recordset[0]; // there is no next
					$edit_url["prev"] =sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
				}else{
					$a_record = $recordset[0];
					$edit_url["prev"] = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
					$a_record = $recordset[2];
					$edit_url["next"] = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
				}
			}else{
				$a_record = $recordset[1]; // there is no previous
				$edit_url["next"] = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
			}		
				
		}	
		
		$this->assign("edit_url",$edit_url);
		$this->assign("ordinal", $return_data['ordinal']);
		
		$row	=& JTable::getInstance('clubregmembers', 'Table');
		$this->assign('last_update',null);
		
		if($return_data['member_id'] > 0){
			$row->load($return_data['member_id']);
				
			$contact_details 	=& JModel::getInstance('contact', 'ClubRegModel');
			$contact_details->getData($return_data['member_id']); // get the member data for current user
			$this->assign("contact_details",$contact_details);
				
				
			$d_qry = sprintf("select a.*, b.name from %s as a left join #__users as b on
					(a.createdby = b.id) where a.primary_id = %d and a.short_desc = 'updated %s' order by a.id desc limit 1; ", CLUB_AUDIT_TABLE,
					$return_data['member_id'], $row->playertype);
			$db->setQuery( $d_qry );
			$last_update= $db->loadObject();
		
			if($last_update){
				$t_date = $last_update->created_date." ".$last_update->created_time;
				$created_date =& JFactory::getDate($t_date);
				$last_update->created_date =  $created_date->toFormat('%d/%m/%Y %H:%M:%S');
				$this->assign('last_update',$last_update);
			}
				
			$d_qry = sprintf("select a.tag_id, a.tag_text,member_id from %s as a left join %s as b on (a.tag_id = b.tag_id)
					where member_id = %d order by a.tag_text asc",
					CLUB_TAG_TABLE,CLUB_TAGPLAYER_TABLE,$return_data['member_id']);
			$db->setQuery($d_qry);
			$tag_list = $db->loadObjectList();			
				
		}else{
			$next_action = isset($_REQUEST["next_action"])?trim(JRequest::getVar( "next_action", null, 'post', 'string' )):null;
			if($next_action){
		
			}else{
					
				foreach($row as $a_key => $t_value){
					if($a_key[0] == "_") continue;
					$t_key = "g_".$a_key;
						
					if($a_key == "dob"){
						// try reformating the date
						$t_explode = explode('/',JRequest::getVar( $t_key, '', 'post', 'string' ));
						if(count($t_explode) == 3)
							$row->$a_key = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
					}else{
						$row->$a_key = JRequest::getVar($t_key,'', 'request', 'string');
					}
						
				}
			}
			$row->playertype = trim(JRequest::getVar('playertype','', 'request', 'string'));
			$tag_list = array();
		}
		
		$row->tag_list = $tag_list;
		
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );
		
		$this->assign("all_headings",$all_headings);
		$this->assign("member_data",$row);
			
		
		$lists["year_registered_list"] = ClubregHelper::generate_seasonList();
		$lists["member_levels"] = ClubregHelper::getMemberLevels();
		
		
		if($all_headings["member_params"]->get("manageusers") == "yes"){
				
			
				
			switch($row->playertype){
				case "senior":
					//$tpl = "senior";
				
					break;
		
				case "junior":
						
					//$tpl = "junior";
					$parent_data = & JTable::getInstance('clubregmembers', 'Table');
					if($row->parent_id > 0){						
						$parent_data->load($row->parent_id);						
					}
				
					$this->assign("parent_data",$parent_data);		
						
					break;
				default:
					$tpl = "guardian";$lists["children"] =  array();
					if($row->member_id > 0){
						$d_qry = sprintf("select * from %s where parent_id = %d",CLUB_REGISTEREDMEMBERS_TABLE,$row->member_id);
						$db->setQuery( $d_qry );
						$all_children = $db->loadObjectList();
						$lists["children"] = $all_children;
					}		
						
					break;
			}
				
			/**
			 get allowed groups
			 */		
			
			$query = sprintf("select -1 as value, '-Select ".GROUPS."-' as text union  select  `group_id` as value,`group_name` as text
					from %s as a where %s
					order by text asc ",CLUB_GROUPS_TABLE,implode(" and ", $group_where));
		
			$db->setQuery( $query );
			$current_groups = $db->loadObjectList();
			$lists["current_groups"] = $current_groups;
				
			$group_data 	=& JModel::getInstance('groups', 'ClubRegModel');
			$lists["subgroups"] =  $group_data->load_subgrougs($row->group); // get the subgroups of the current group
				
				
			$this->assign("lists",$lists);
				
			if(isset($row->member_id) && intval($row->member_id) > 0){
				$this->assign("payment_list", ClubPaymentsHelper::getPaymentList($row));
				$this->assign("note_list", ClubNotesHelper::getNoteList($row));
		
				$stats_list	=  & JModel::getInstance('stats', 'ClubRegModel');
				$this->assign("stats_list", $stats_list->getPlayerStatsList($row));
			}
		
		parent::display($tpl);
		}
		
	}
}?>