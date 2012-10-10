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
	
		
		$edit_url = array();
		
		JHTML::_('script', 'group.js?'.time(), 'components/com_clubreg/assets/js/'); // add the subgroup filter javascript
		JHTML::_('script', 'registration.js?'.time(), 'components/com_clubreg/assets/js/');
		JHTML::_('stylesheet', 'render_reg.css', $append .'components/com_clubreg/assets/');
		
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
					$edit_url["prev"] =sprintf("index.php?option=%s&c=userreg&task=renderreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
				}else{
					$a_record = $recordset[0];
					$edit_url["prev"] = sprintf("index.php?option=%s&c=userreg&task=renderreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
					$a_record = $recordset[2];
					$edit_url["next"] = sprintf("index.php?option=%s&c=userreg&task=renderreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
				}
			}else{
				$a_record = $recordset[1]; // there is no previous
				$edit_url["next"] = sprintf("index.php?option=%s&c=userreg&task=renderreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
			}		
				
		}	
		
		$this->assign("edit_url",$edit_url);
		$this->assign("ordinal", $return_data['ordinal']);
		
		$member_details	=& JModel::getInstance('regmember', 'ClubRegModel'); //& JTable::getInstance('clubregmembers', 'Table');
		$this->assign('last_update',null);
		
		;
		if($return_data['member_id'] > 0){
			$member_details->getData($return_data);		
				
			$d_qry = sprintf("select a.*, b.name from %s as a left join #__users as b on
					(a.createdby = b.id) where a.primary_id = %d and a.short_desc = 'updated %s' order by a.id desc limit 1; ", CLUB_AUDIT_TABLE,
					$return_data['member_id'], $member_details->reg_details->playertype);
			$db->setQuery( $d_qry );
			$last_update= $db->loadObject();
		
			if($last_update){
				$t_date = $last_update->created_date." ".$last_update->created_time;
				$created_date =& JFactory::getDate($t_date);
				$last_update->created_date =  $created_date->toFormat('%d/%m/%Y %H:%M:%S');
				$this->assign('last_update',$last_update);
			}
				
		}
		
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );
		
		$this->assign("all_headings",$all_headings);
		$this->assign("member_data",$member_details);
		

		
		
		if($all_headings["member_params"]->get("manageusers") == "yes"){			
				
			switch($member_details->reg_details->playertype){
				case "senior":
					//$tpl = "senior";
				
					break;
		
				case "junior":
						
					//$tpl = "junior";
					$parent_details = & JModel::getInstance('regmember', 'ClubRegModel'); //& JTable::getInstance('clubregmembers', 'Table');
					if($member_details->reg_details->parent_id > 0){
						$r_data["member_id"] = $member_details->reg_details->parent_id;
						$parent_details->getData($r_data);						
					}
				
					$this->assign("parent_details",$parent_details->reg_details);		
						
					break;
				default:
					//$tpl = "guardian";
					$lists["children"] =  array();
					if($member_details->member_id > 0){
						$d_qry = sprintf("select * from %s where parent_id = %d",CLUB_REGISTEREDMEMBERS_TABLE,$member_details->reg_details->member_id);
						$db->setQuery( $d_qry );
						$all_children = $db->loadObjectList();
						$lists["children"] = $all_children;
					}		
						
					break;
			}
							
			$this->assign("lists",$lists);
				
			if(isset($member_details->reg_details->member_id) && intval($member_details->reg_details->member_id) > 0){
				$this->assign("payment_list", ClubPaymentsHelper::getPaymentList($member_details->reg_details));
				$this->assign("note_list", ClubNotesHelper::getNoteList($member_details->reg_details));
		
				$stats_list	=  & JModel::getInstance('stats', 'ClubRegModel');
				$this->assign("stats_list", $stats_list->getPlayerStatsList($member_details->reg_details));				
				
			}		
		
			
		parent::display($tpl);
		}
		
	}
}?>