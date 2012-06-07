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
		
		global $mainframe,$append,$option,$Itemid;
		
		JHTML::_('script', 'comms.js?'.time(), 'components/com_clubreg/assets/js/');
		JHTML::_('stylesheet', 'comms.css', $append .'components/com_clubreg/assets/css/');
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
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
			
			$where_ = array();
			
			$limit 		= $mainframe->getUserStateFromRequest( $option.'.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
			$limitstart 		= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
			
			$filter_order		= $mainframe->getUserStateFromRequest( $option.'.filter_order',		'filter_order',		'a.created ',	'cmd' );
			$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.filter_order_Dir',	'filter_order_Dir',	'',				'word' );
			$filter_state		= $mainframe->getUserStateFromRequest( $option.'.filter_state',		'filter_state',		'',				'word' );
								
			$all_string["n"] = 'a.*';
			$all_string["sentto"] = "group_concat(b.group_name) as sentto";
			$all_string["comm_subject"] = "a.comm_subject";
			$all_string["comm_message"] = "a.comm_message";
			$all_string["created"] = "date_format(a.created,'%d/%m/%Y') as created";
			$all_string["created_by"] = "c.name as created_by";
			$all_string["senton"] = "date_format(a.sent_date,'%d/%m/%Y') as senton";
			
			$all_string["comm_status"] = "a.comm_status";
			
			$session =& JFactory::getSession();
			$session->set("com_clubreg.back_url", $d_url_);// save the back url
			
			$table_join = sprintf(" left join %s as b on find_in_set(b.group_id,a.comm_groups) ",CLUB_GROUPS_TABLE);
			
			
			if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
				$filter_order_Dir = 'DESC';
			}
			
			$orderby 	= ' ORDER BY '.$filter_order.' '. $filter_order_Dir ;
			
			$where_str = "";
			
			if(count($where_) > 0){
				$where_str = " where ".implode(" and ",$where_ );
			}
			
			
			$d_qry = sprintf("select count(comm_id) as howmany from %s as a %s",CLUB_SAVEDCOMMS_TABLE, $where_str );
			$db->setQuery( $d_qry );
			$howmany = $db->loadResult();
			
			require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubPagination.php');
			$pageNav = new clubPagination( $howmany, $limitstart, $limit );
			
			
			$var_str = implode(" , ", $all_string);
			
			$group_by = "GROUP BY a.comm_id"; // count howmany are registered
			
			$d_qry = sprintf("select %s from %s as a								
					left join #__users as c on (a.created_by = c.id)
					%s					
					%s %s %s",
					$var_str,
					CLUB_SAVEDCOMMS_TABLE,						
					$table_join,					
					$where_str,$group_by,$orderby );
			
			$db->setQuery( $d_qry, $pageNav->limitstart, $pageNav->limit  );
			$all_results = $db->loadObjectList();			
			
			if($db->getErrorNum() > 0){
				write_debug($db);
			}
			
			
			$all_headings["pageNav"] =  $pageNav;
			$all_headings["variable_string"] = $all_string;
			
			$all_headings["filter_order_Dir"] =  $filter_order_Dir;
			$all_headings["filter_order"] =  $filter_order;			
			
			$this->assign('all_results',$all_results);
			
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
		
		global $option,$Itemid;
		
		$user		= &JFactory::getUser();
		$db		=& JFactory::getDBO();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$all_headings = ClubregHelper::return_headings_reg($member_data);
		$all_headings["member_data"] = $member_data;
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );	

		$session =& JFactory::getSession();
		$d_url_ = $session->get("com_clubreg.back_url");
		
		$back_url = sprintf("index.php?option=%s&c=comms&task=listcomms&Itemid=%d&%s",$option,$Itemid,@implode("&",$d_url_));
		$this->assign("back_url",$back_url);
		
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
			
			$all_headings['comm_groups'] = JRequest::getVar('comm_groups',array(), 'post', 'array');
			
			$d_qry = sprintf("select group_name from %s where group_id in (%s)",CLUB_GROUPS_TABLE,
					implode(",",$all_headings['comm_groups']));
			
			$db->setQuery($d_qry);
			$all_headings['message_groups']  = $db->loadResultArray();
			
			
			$tpl = "emails";	
			
				
			$this->assign("all_headings",$all_headings); // get all the headings				
			parent::display($tpl);
				
		}else{
			$this->not_authorised();
		}
		
	}
}	
