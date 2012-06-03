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

class ClubRegController extends JController
{
	function __construct()
	{
		parent::__construct();
	
		// Register Extra tasks
		$this->registerTask("save_userdetails","save_userdetails");
	}
	function display()
	{		
		
		$t_view = JRequest::getVar('view',"members" , 'request', 'string');	
		
		switch($t_view){			
			case "groups":
				
			break;			
			default:
				
				// Get the parameters of the active menu item
				$menus	= &JSite::getMenu();
				$menu    = $menus->getActive();
				
				$user = &JFactory::getUser();
				$member_id = JRequest::getVar('id',0 , 'request', 'int');
				
				//$this->check_login_permissions("members");
				JRequest::setVar('view','members');
				
				//write_debug($menu);//|| ($menu->query['id'] == 0)
				if(is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'members' ){
					
					if((isset($menu->query['id']) && $menu->query['id'] == $member_id) ){
						
					}else
						JRequest::setVar('view','nomembers');
					
				}else
					JRequest::setVar('view','nomembers');
				
								
				if($member_id == 0){$member_id = $user->id;	} // force this to my view
				JRequest::setVar('id',$member_id);			
				
				$model = &$this->getModel('member'); // get a member model		
				$current_view = &$this->getView("members","html");			  	
			  	$current_view->setModel($model);  // this is the only way to pass the model to the view.
			 
	  	
			break;
		}					
			parent::display(true);	
		
		
		
	}
	function members(){		
		$this->check_login_permissions("members");			
		JRequest::setVar('view','members');			
		parent::display(true);	
	}
		
	function check_login_permissions($view){
		$user = &JFactory::getUser();
		$allowed = array();		
		/**
		 * gid = 25 super admin
		 */
		
		
		if($user->id > 0){
			return true;
		}else{
			$msg = JText::_('Please Login');
			$this->setRedirect(JRoute::_('index.php?option=com_user&view=login&Itemid=28', false), $msg);
			return;
		}
	}
	function write_logout(){
		?><a href="index.php?option=com_user&task=logout&return=aW5kZXgucGhw">Logout</a><?php
	}
	function save_userdetails(){
		
		global $option,$Itemid;
		
		$this->check_login_permissions("members");
		
		$user = &JFactory::getUser();
		$db		=& JFactory::getDBO();
		$post = JRequest::get( 'post' );
		
		$all_user_details = JRequest::getVar('user_details',array(), 'post', 'array');
		$member_id = JRequest::getVar('uid',0 , 'post', 'int');
		
		$d_qry = array();
		
		if(count($all_user_details) > 0 && $member_id > 0 && $member_id == $user->id){
			$d_qry[] = sprintf("delete from %s where joomla_id = %s ;",
			CLUB_MEMBERSDETAILS_TABLE,$member_id);
			foreach($all_user_details as $a_key => $a_value){
				
				if(strlen(trim($a_key)) > 0 && strlen(trim($all_user_details[$a_key]))> 0){
				$d_qry[] = sprintf("insert into %s set joomla_id = %s, `member_detail`=%s, `member_value`=%s 
				on duplicate key update `member_value` = values(`member_value`);",
				CLUB_MEMBERSDETAILS_TABLE,$member_id,$db->Quote($a_key), $db->Quote($all_user_details[$a_key]));
				}
				
			} // loop	
		} // user id
			
		if(count($d_qry) > 0){
			$q_string = implode("",$d_qry);
			$db->setQuery($q_string);
			if(!$db->queryBatch()){
				return JError::raiseError(500, $db->getErrorMsg() );
			}
		}
		$msg = "Details Updated";
		$d_url = sprintf("index.php?option=%s&view=members&id=0&Itemid=%d",
		$option,$Itemid);
		$this->setRedirect($d_url,$msg);
	}
}
?>