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


class ClubRegViewcomms extends JView
{
	function display($tpl = null){
		
		JHTML::_('script', 'comms.js?'.time(), 'components/com_clubreg/assets/js/');
		JHTML::_('stylesheet', 'comms.css', $append .'components/com_clubreg/assets/css/');
		
		$user		= &JFactory::getUser();		
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		//$all_headings = ClubregHelper::return_headings_reg($member_data);
		
		$all_headings = ClubCommsHelper::return_headings_comms();
		
		
		$all_headings["member_data"] = $member_data;
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );	

		
		
		if($all_headings["member_params"]->get("sendcommunication") == "yes"){	

			$layout	= $this->getLayout();
				
			if( $layout == 'editcomms') {
				$this->_editcomms($tpl);
				return;
			}
			if( $layout == 'previewcomms') {
				$this->_previewcomms($tpl);
				return;
			}
			
			
			
			$all_string["n"] = 'a.*';
			$all_string["playertype"] = "ucase(a.playertype) as playertype";
			$all_string["member_name"] = "concat(a.`surname`,' ' ,a.`givenname`) as surname";
			$all_string["t_created_date"] = "date_format(a.created,'%d/%m/%Y') as t_created_date";
			$all_string["t_created_by"] = "e.name as t_created_by";
			$all_string["t_group"] = "b.group_name as `group`";
			
			$all_headings["sentto"] = "Sent To";
			$all_headings["comm_subject"] = "Subject";
			$all_headings["comm_message"] = "Message";
			$all_headings["created"] = "Created On";
			$all_headings["created_by"] = "Created By";
			$all_headings["senton"] = "Sent On";
			
			
			
			$tpl = "listcomms" ;		
			
			$templates	=  & JModel::getInstance('templates', 'ClubRegModel'); 
			$templates->getTemplates(true);
			
			$this->assign("all_headings",$all_headings); // get all the headings
			$this->assign("templates",$templates);
			
			
			
			
			
			
			parent::display($tpl);
			
		}else{
			$this->not_authorised();
		}
		
		
	}	
	function not_authorised(){
		JError::raiseWarning( 500, "You are not authorised to view the send communications" );
		return;
	}
	function _editcomms($tpl){
		
		$user		= &JFactory::getUser();
		$db		=& JFactory::getDBO();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$all_headings = ClubregHelper::return_headings_reg($member_data);
		$all_headings["member_data"] = $member_data;
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );		
		
		
		if($all_headings["member_params"]->get("sendcommunication") == "yes"){		

			$return_data['tmp_id'] = intval(JRequest::getVar('tmp_id','0', 'request', 'int'));
			
			$return_data['comm_id'] = intval(JRequest::getVar('comm_id','0', 'request', 'int'));
			
			if(isset($return_data['comm_id']) && $return_data['comm_id'] > 0){
				$templates	=  & JModel::getInstance('comms', 'ClubRegModel');
				$templates->getCommDetails($return_data['comm_id']);
			}else{			
				$templates	=  & JModel::getInstance('templates', 'ClubRegModel'); 
				$templates->getTemplateDetails($return_data['tmp_id']);
			}
			
			
			$tpl = "emails";
			
			
			$tmp_list = array();$group_where_str = "";
			$group_where[] = "publish=1";
			$group_where[] = "group_parent = 0";
			$group_where[] = sprintf(" a.group_id in (%s)",implode(",",$member_data->all_allowed_groups));			
			
			$group_where_str = "where ".implode(" and ", $group_where);
			
			$query = sprintf("select  `group_id` as value,`group_name` as text
										from %s as a %s  order by text asc  ",CLUB_GROUPS_TABLE,$group_where_str);
			
			$db->setQuery( $query );
			$all_headings["templategroups"] = $db->loadObjectList();
			
			$this->assign("all_headings",$all_headings); // get all the headings
			$this->assign("templates",$templates);			
			
			parent::display($tpl);
			
		}else{
			$this->not_authorised();
		}
		
	}
	
	function _previewcomms($tpl){
		$user		= &JFactory::getUser();
		$db		=& JFactory::getDBO();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$all_headings = ClubregHelper::return_headings_reg($member_data);
		$all_headings["member_data"] = $member_data;
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );		
		
		
		if($all_headings["member_params"]->get("sendcommunication") == "yes"){
			
			$all_headings['comm_id'] = intval(JRequest::getVar('comm_id','0', 'post', 'int'));		
			$all_headings['tmp_id'] = intval(JRequest::getVar('tmp_id','0', 'post', 'int'));
			$all_headings['message_subject'] = JRequest::getVar('comm_subject','', 'post', 'string');
			$all_headings['message_body'] = JRequest::getVar('comm_message','', 'post', 'string',4);				
			$tpl = "emails";	
			
				
			$this->assign("all_headings",$all_headings); // get all the headings				
			parent::display($tpl);
				
		}else{
			$this->not_authorised();
		}
		
	}
}	
