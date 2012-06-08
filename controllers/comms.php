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

jimport('joomla.application.component.controller');

/**
 * Static class to hold controller functions for the Poll component
 *
 * @static
 * @package		Joomla
 * @subpackage	Poll
 * @since		1.5
 */
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubComms.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubTables.php');

class ClubRegControllerComms extends JController
{
	
	function __construct()
	{
		global $mainframe;
		parent::__construct();			
			$this->registerTask("listcomms","listcomms");	
			$this->registerTask("editcomms","editcomms");
			$this->registerTask("previewcomms","previewcomms");
			$this->registerTask("sendcomms","sendcomms");
			
			$this->registerTask("savecomms","savecomms");
		
	}
	
	
	function display(){	

		JRequest::setVar('view','comms');		
		parent::display($tpl);		
	}
	
	function listcomms(){		
		//JRequest::setVar('layout', 'listcomms');
		JRequest::setVar('view','comms');
		parent::display();
	}
	function editcomms(){
		
		JRequest::setVar('view','comms');
		JRequest::setVar('layout', 'editcomms');
		parent::display();
	}
	function previewcomms(){		
		
		JRequest::checkToken() or jexit( 'Invalid Token' );		
		
		JRequest::setVar('view','comms');
		JRequest::setVar('layout', 'previewcomms');
		parent::display();		
		
	}
	function sendcomms(){
		global $mainframe;
		
		$user		= &JFactory::getUser();
		$db		=& JFactory::getDBO();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$all_headings = ClubregHelper::return_headings_reg($member_data);
		$all_headings["member_data"] = $member_data;
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );
		
		
		if($all_headings["member_params"]->get("sendcommunication") == "yes"){
		
		
		$all_headings['comm_groups'] = JRequest::getVar('comm_groups',array(), 'post', 'array');
		$all_headings['comm_id'] = intval(JRequest::getVar('comm_id','0', 'post', 'int'));
		
		if(count($all_headings['comm_groups']) > 0 && ($all_headings['comm_id']) > 0){
			
			$SiteName 	= $mainframe->getCfg('sitename');
			$MailFrom 	= $mainframe->getCfg('mailfrom');
			$FromName 	= $mainframe->getCfg('fromname');
			
			jimport( 'joomla.mail.helper' );
			
			
			$all_headings['tmp_id'] = intval(JRequest::getVar('tmp_id','0', 'post', 'int'));
			$all_headings['message_subject'] = JMailHelper::cleanSubject(JRequest::getVar('comm_subject','', 'post', 'string'));
			$all_headings['message_body'] = JMailHelper::cleanBody(JRequest::getVar('comm_message','', 'post', 'string',4));
						
			$d_qry = sprintf("select group_name from %s where group_id in (%s)",CLUB_GROUPS_TABLE,
					implode(",",$all_headings['comm_groups']));
			
			$db->setQuery($d_qry);
			$all_headings['message_groups']  = $db->loadResultArray();
			
			$group_str = implode(",",$all_headings['comm_groups']);
			
			$d_qry = sprintf("select a.emailaddress,concat(a.surname,' ',a.givenname) as sending_name,a.parent_id,a.playertype,
					if(a.parent_id > 0,b.emailaddress ,a.emailaddress) as sending_email
					from %s as a  
					left join %s as b on (a.parent_id = b.member_id)
					where a.`group` in (%s) or a.`subgroup` in (%s);",
			CLUB_REGISTEREDMEMBERS_TABLE,CLUB_REGISTEREDMEMBERS_TABLE, $group_str,$group_str);
			$db->setQuery($d_qry);
			$all_recipients = $db->loadObjectList();
			
			
			foreach($all_recipients as $a_recp){
				if(JMailHelper::isEmailAddress($a_recp->sending_email)){
					$valid_address[] = $a_recp->sending_email;
				}else{
					$msg_log[] = sprintf("%s %s",$a_recp->sending_name,$a_recp->sending_email);
				}
			}

			if(count($valid_address) > 0){
				if ( JUtility::sendMail($MailFrom, $SiteName, $user->email, $all_headings['message_subject'], $all_headings['message_body'],1,null,$valid_address) !== true )
				{
					JError::raiseNotice( 500, JText:: _ ('Email Not Sent' ));
					$this->editcomms();
				}else{
					$d_qry = sprintf("update %s set sent_date = now(), comm_status = '1', sent_by = '%s' where
							comm_id = %d ", CLUB_SAVEDCOMMS_TABLE,$user->id,$all_headings["comm_id"]);
					$db->setQuery($d_qry);
					$db->query();
				}
			}
			
		}else{
			if(count($all_headings["comm_groups"]) == 0)
					$msg = "No Recipients.\n";
				
			
			if(!isset($all_headings["comm_id"]) || $all_headings["comm_id"] <= 0)
					$msg .= "\nEmail needs to be saved at least once.";
				
				JError::raiseWarning( 500, $msg);
		}
		
		$this->editcomms();
		}else{
			JError::raiseWarning( 500, "You are not authorised to send communications" );
			return;
		}
		
		
		
	}
	function savecomms(){		
		
		$user		= &JFactory::getUser();
			
		JRequest::checkToken() or jexit( 'Invalid Token' );
			
		$post = JRequest::get('post');
			
		$comm_row	=& JTable::getInstance('clubcomms', 'Table');
		$comm_row->bind( $post );
		
		$comm_row->comm_id = intval($comm_row->comm_id);
		
		$comm_row->comm_message = JRequest::getVar( 'comm_message', '', 'post', 'string', JREQUEST_ALLOWRAW );  		
		$comm_row->template_id = intval(JRequest::getVar('tmp_id','0', 'post', 'int'));		
		$comm_row->comm_groups = implode(",",$comm_row->comm_groups);
		
		if(isset($comm_row->comm_id) && intval($comm_row->comm_id) > 0){
			
		}else{
			$comm_row->created = date("Y-m-d H:i:s");
			$comm_row->created_by = $user->id;		

			$comm_row->comm_status = '0';
			
		}
		
		if(strlen($comm_row->comm_subject) > 0){
			$comm_row->store();
		}else{
			$msg[] = "";
			JError::raiseWarning( 500, sprintf("Incomplete Communication Details :: %s",implode(", ",$msg)));
					
			
		}
		$_REQUEST["comm_id"] = $comm_row->comm_id;
		
		$this->editcomms();
		
		
		
	}
	

}