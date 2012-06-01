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
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubTables.php');
class ClubRegControllerEoi extends JController
{
	
	function __construct()
	{
		global $mainframe;
		parent::__construct();
		
					// Register Extra tasks
			$this->registerTask("send_request","send_request");
			$this->registerTask("loadeoi","loadeoi");
			$this->registerTask("registereoi","registereoi");
			$this->registerTask("deleteeoi","deleteeoi");
		
	}
	
	
	function display(){			
		
		$current_view = JRequest::getVar('view',"eoi" , 'request', 'string');	
		$tpl = true;
		if($current_view == "senior"){
			$tpl = "seniorplayer";
			JRequest::setVar('tpl','seniorplayer');
		}
		JRequest::setVar('view','eoi');			
		
		parent::display($tpl);
		
		
	}
	function send_request(){	

		global $mainframe;
		
		JRequest::checkToken() or jexit( 'Invalid Token' );	
		$save_junior = false;		
		$post = JRequest::get('post');
		require_once(JPATH_COMPONENT.DS.'views'.DS.'eoi'.DS.'view.html.php');
		
		$pparams = &$mainframe->getParams('com_clubreg');
		
		if($pparams->get('userecaptcha')  && $pparams->get('userecaptcha') == 1 && $pparams->get('privkey')){
		
			$resp = recaptcha_check_answer($pparams->get('privkey'), 
			 $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			
			if (!$resp->is_valid) {
				// What happens when the CAPTCHA was entered incorrectly   			
				$e_string = "The reCAPTCHA wasn't entered correctly. Go back and try it again." ."(reCAPTCHA said: " . $resp->error . ")";
				JError::raiseWarning( 500, $e_string );					
				$this->display(); // Reload my page
				
				return;
			}
		
		}			
		
		$row	=& JTable::getInstance('clubmembers', 'Table');
		$created_when = date('Y-m-d H:i:s');
		
		$contact_array = array('surname','givenname','mobile','address','suburb','postcode',
		'phoneno','emailaddress','send_news','dob','group','as_above','gender','playertype');
		
		$must_supply = array('surname','givenname','emailaddress');
		
		$primary_contact = array();
		$at_least = array();
		foreach($contact_array as $a_key){
			$t_key = 'g_'.$a_key;
			$primary_contact[$a_key] = JRequest::getVar( $t_key, '-1', 'post', 'string' );
			
			if(in_array($a_key, $must_supply) && strlen($primary_contact[$a_key]) > 2){
				$at_least[] = true;
			}
			/*if(strlen($primary_contact[$a_key]) > 0 && $primary_contact[$a_key] != '-1' ){
				
			}	*/		
		}
		
		if(count($at_least) > 2){	
		
			if (!$row->bind( $primary_contact )) {
				JError::raiseError(500, $row->getError() );
			}
			$row->created = $created_when;
			$row->member_status = "eoi";		
			$row->store();		
			$parent_id = $row->member_id;
			
			$save_junior = true;
		
		}else{
			$e_string = "Not enough details provided";
			JError::raiseWarning( 500, $e_string );
			$save_junior = false;;
			$this->display(); // Reload my page
			return ;
		}
		
		if($save_junior){
		
		$prefix_key = "player_";		
		$div_counter = JRequest::getVar( 'div_counter', 0, 'post', 'int' );
		
		for($i = 0; $i < $div_counter ; $i++){
				$t_index = $prefix_key.$i;
				$at_least = array();
			foreach($contact_array as $a_key){
				$t_key = sprintf('p_%s',$a_key);
				
				$tmp_post = JRequest::getVar( $t_key, array(), 'post', 'array' );	
				$player_contact[$a_key] = JArrayHelper::getValue( $tmp_post, $t_index, '-1', 'string' );				
				
				if(strlen($player_contact[$a_key]) > 0 && $player_contact[$a_key] != '-1' ){
					$at_least[] = 1;
					if($a_key == "dob"){
						// try reformating the date
						$t_explode = explode('/',$player_contact[$a_key]);
						$player_contact[$a_key] = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );						
					}					
				}
				$tmp_post = array();
			}
			
			
			if(count($at_least) > 0){
				$player_row	=& JTable::getInstance('clubmembers', 'Table');
				$player_row->bind( $player_contact );
				
				$player_row->parent_id = $parent_id;
				$player_row->created = $created_when;
				$player_row->member_status = "eoi";
				$player_row->playertype = "junior";
				$player_row->store();				
			}	
			
		}	
		} // end save junior

				
			$t_view = new ClubRegVieweoi();
			$t_view->eoithanks(); // load the thanks page		
		
	}
	function loadeoi(){		
		JRequest::setVar('layout', 'loadeoi');		
		parent::display();
	}
	function registereoi(){
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		$member_params =  new JParameter( $member_data->user_data->params );
		
		if($member_params->get('registereoi' ) == "yes"){
		
				$eoi_member = JRequest::getVar( "eoi_member", array(), 'post', 'array' );
				$created_when = date('Y-m-d H:i:s');
				if(count($eoi_member) > 0 ){
					$all_eois = array();
					$member_ids = implode(",",$eoi_member);
					$d_qry = sprintf("select a.* from %s as a  
					where a.member_id in (%s)",CLUB_EOIMEMBERS_TABLE,$member_ids);
					$db->setQuery($d_qry);
					$all_eois = $db->loadObjectList();
				
					foreach($all_eois as $an_eoi){
						
						if($an_eoi->playertype == "guardian"){
							// get all my children,
							// if more than 0 then add then add me, then add them
							// 
							
							$row	=& JTable::getInstance('clubregmembers', 'Table');
							foreach($row as $t_key => $t_value){
								if($t_key[0] == "_") continue;
								$row->$t_key = $an_eoi->$t_key;
							}
							$row->eoi_id = $an_eoi->member_id;
								
							$row->created_by = $row->approved_by = $user->id;
							$row->created = $row->approved = $created_when;
							$row->member_status = "registered";
							$row->member_id = null;
								
							if($row->store()){
								$approved[] = $an_eoi->member_id;
							}
							
							$d_qry = sprintf("select a.* from %s as a  where a.parent_id in (%s)",CLUB_EOIMEMBERS_TABLE,$an_eoi->member_id);
							$db->setQuery($d_qry);
							$all_children = $db->loadObjectList();
							
							foreach($all_children as $a_child){
								$child_row	=& JTable::getInstance('clubregmembers', 'Table');
								foreach($child_row as $t_key => $t_value){
									if($t_key[0] == "_") continue;
									$child_row->$t_key = $a_child->$t_key;
								}
								$child_row->eoi_id = $a_child->member_id;
									
								$child_row->created_by = $child_row->approved_by = $user->id;
								$child_row->created = $child_row->approved = $created_when;
								$child_row->member_status = "registered";
								$child_row->member_id = null;
								$child_row->year_registered = date('Y');
								
								$child_row->parent_id = $row->member_id;
									
								if($child_row->store()){
									$approved[] = $a_child->member_id;
								}
								
							}
							
							
						}elseif($an_eoi->playertype == "senior"){
							$row	=& JTable::getInstance('clubregmembers', 'Table');
							foreach($row as $t_key => $t_value){
								if($t_key[0] == "_") continue;
								$row->$t_key = $an_eoi->$t_key;
							}
							$row->eoi_id = $an_eoi->member_id;
							
							$row->created_by = $row->approved_by = $user->id;							
							$row->created = $row->approved = $created_when;
							$row->member_status = "registered";
							$row->year_registered = date('Y');
							$row->member_id = null;
							
							if($row->store()){							
								$approved[] = $an_eoi->member_id;
							}							
						}
						
					}
					
					if(count($approved) > 0){
						$member_ids = implode(",",$approved);
						$d_qry = sprintf("update %s set member_status = 'approved' where member_id in (%s) or parent_id in (%s) ;",CLUB_EOIMEMBERS_TABLE,$member_ids,$member_ids);
						$db->setQuery( $d_qry );
						$db->query();
						
						//echo $d_qry;
					}
					
					
				}else{
					JError::raiseWarning( 500, "Please Select at least one member" );
				}
		}else{
			JError::raiseWarning( 500, "You are not authorised to Register EOI Members" );
		}
		JRequest::setVar('layout', 'loadeoi');
		parent::display();
	}
	
	function deleteeoi(){		
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user		
		$member_params =  new JParameter( $member_data->user_data->params );
		
		$eoi_member = JRequest::getVar( "eoi_member", array(), 'post', 'array' );
		
		if($member_params->get('deleteeoi' ) == "yes"){	
		
			if(count($eoi_member) > 0 ){
				$member_ids = implode(",",$eoi_member);
				$d_qry = sprintf("update %s set member_status = 'deleted' where member_id in (%s) or parent_id in (%s) ;",CLUB_EOIMEMBERS_TABLE,$member_ids,$member_ids);		
				$db->setQuery( $d_qry );
				$db->query();					
				JError::raiseWarning( 500, "EOI Members Deleted" );			
			}else{
				JError::raiseWarning( 500, "Please Select at least one member" );
			}
		
		}else{
			JError::raiseWarning( 500, "You are not authorised to delete an EOI Member" );
		}
		JRequest::setVar('layout', 'loadeoi');
		parent::display();
	}

}