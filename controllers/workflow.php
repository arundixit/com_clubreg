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
require_once (JPATH_COMPONENT.DS.'assets'.DS.'recaptcha'.DS.'recaptchalib.php');
class ClubRegControllerWorkflow extends JController
{
	
	function __construct()
	{
		global $mainframe;
		parent::__construct();
		
		// Register Extra tasks
		$this->registerTask("editflow","editflow");
		$this->registerTask("save_details","save_details");
		
		
	}
	
	
	function display($tpl = null){		
		
		JRequest::setVar('view','workflow');
		switch($t){
			
		}
		parent::display($tpl);	
		
	}
	function editflow(){
		
		JRequest::setVar('layout', 'editflow');
		JRequest::setVar('view','workflow');
		parent::display();
	}
	function save_details(){	
		
		global $mainframe;
		
		$user		= &JFactory::getUser();
		$app = JFactory::getApplication();
		
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$save_junior = false;
		$post = JRequest::get('post');
		
		$player_type = trim(JRequest::getVar( "g_playertype", '', 'post', 'string' ));
		$member_id = intval(JRequest::getVar( "member_id", 0, 'post', 'int' ));
		$row	=& JTable::getInstance('clubregmembers', 'Table');
		
		$row_old	=& JTable::getInstance('clubregmembers', 'Table');
		if(intval($member_id)>0){ $row_old->load($member_id); }else{
			$row->member_status = 'registered';
			$row->created = date('Y-m-d H:i:s');
			$row->created_by = $user->id;
			JRequest::setVar('playertype', $player_type);
		}
		
		switch($player_type){
			case "junior":
				$contact_array = array('surname','givenname','dob','group','gender','playertype','parent_id');				
				$must_supply = array('surname','givenname');
				
				$row->member_id = $member_id;
				$atleast = array();
				
				foreach($contact_array as $a_key){
					$t_key = "g_".$a_key;
					

					if($a_key == "dob"){
						// try reformating the date
						$t_explode = explode('/',JRequest::getVar( $t_key, '-1', 'post', 'string' ));
						$row->$a_key = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
					}else{						
						$row->$a_key = trim(JRequest::getVar( $t_key, '-1', 'post', 'string' ));
						if(in_array($a_key, $must_supply)){
							if(strlen($row->$a_key) > 2){
								$atleast[] = true;
							}
						}
					}					
				}	
				if(count($atleast) == 2){	
					if($member_id > 0){
						$other_details["primary_id"] = $member_id;
						$other_details["short_desc"] = "updated ".$player_type;
						ClubregHelper::save_old_data($row_old,$other_details);
					}		
					$row->store();
					JRequest::setVar('member_id', $row->member_id);
					$app->enqueueMessage("Details Updated for Registered User");
				}else{
					JError::raiseWarning( 500, "Incomplete Player Details :: Player Names Must be more than 2 characters" );
				}			
				
			break;
			case "senior":
				
				jimport('joomla.mail.helper');
				
				$contact_array = array('surname','givenname','group',
				'emailaddress','phoneno','mobile',
				'address','suburb','postcode','gender','send_news','playertype');
				
				$must_supply = array('surname','givenname');
				$is_email = array('emailaddress');
				
				$row->member_id = $member_id;
				$msg = array();			
				
				foreach($contact_array as $a_key){
					$t_key = "g_".$a_key;
						
					$row->$a_key = trim(JRequest::getVar( $t_key, '-1', 'post', 'string' ));
					if(in_array($a_key, $is_email)){
						if(JMailHelper::isEmailAddress($row->$a_key) === FALSE ){
							$row->$a_key = NULL;
							$msg[] = "Email Address Invalid";
						}else{
							$atleast[] = true;
						}
					}else{
						
						if(in_array($a_key, $must_supply)){
							if(strlen($row->$a_key) > 2){
								$atleast[] = true;
							}else{
								$msg[] = sprintf("%s Must be more than 2 characters",ucfirst($a_key));
							}
						}
					}
				}
				
				if(count($atleast) == 3){
					if($member_id > 0){
						$other_details["primary_id"] = $member_id;
						$other_details["short_desc"] = "updated ".$player_type;						
						ClubregHelper::save_old_data($row_old,$other_details);
					}
					
					$row->store();
					JRequest::setVar('member_id', $row->member_id);	
					$app->enqueueMessage("Details Updated for Registered User");
				}else{
					JError::raiseWarning( 500, sprintf("Incomplete Player Details :: %s",implode(", ",$msg)));
					
				}			
				
			break;
			case "guardian":
				jimport('joomla.mail.helper');
				
				$contact_array = array('surname','givenname',
								'emailaddress','phoneno','mobile',
								'address','suburb','postcode','send_news','playertype');
				
				$must_supply = array('surname','givenname');
				$is_email = array('emailaddress');
				
				$row->member_id = $member_id;
				$msg = array();
				
				foreach($contact_array as $a_key){
					$t_key = "g_".$a_key;
				
					$row->$a_key = trim(JRequest::getVar( $t_key, '-1', 'post', 'string' ));
					if(in_array($a_key, $is_email)){
						if(JMailHelper::isEmailAddress($row->$a_key) === FALSE ){
							$row->$a_key = NULL;
							$msg[] = "Email Address Invalid";
						}else{
							$atleast[] = true;
						}
					}else{
				
						if(in_array($a_key, $must_supply)){
							if(strlen($row->$a_key) > 2){
								$atleast[] = true;
							}else{
								$msg[] = sprintf("%s Must be more than 2 characters",ucfirst($a_key));
							}
						}
					}
				}
				
				if(count($atleast) == 3){
					if($member_id > 0){
						$other_details["primary_id"] = $member_id;
						$other_details["short_desc"] = "updated ".$player_type;
						ClubregHelper::save_old_data($row_old,$other_details);
					}
						
					$row->store();
					JRequest::setVar('member_id', $row->member_id);
					$app->enqueueMessage("Details Updated for Registered User");
				}else{
					JError::raiseWarning( 500, sprintf("Incomplete Player Details :: %s",implode(", ",$msg)));
						
				}
				
				
			break;
			
		}
		$next_action = isset($_POST["saveNnew"])?JRequest::getVar( "saveNnew", null, 'post', 'string' ):null;
		if(isset($next_action)){
			JRequest::setVar('member_id', 0);
			JRequest::setVar('next_action', "saveNnew");
		}
		
		$this->editflow();
		return;
		
	}

}