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
require_once (JPATH_COMPONENT.DS.'assets'.DS.'recaptcha'.DS.'recaptchalib.php');
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
		parent::display(&$tpl);		
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
		
		$comm_groups = JRequest::getVar('comm_groups',array(), 'post', 'array');
		
		if(count($comm_groups) > 0){
			
		}else{
			JError::raiseWarning( 500, sprintf("No Recipients "));
		}
		
		$this->editcomms();
		
		
		
	}
	function savecomms(){		
		
		$user		= &JFactory::getUser();
			
		JRequest::checkToken() or jexit( 'Invalid Token' );
			
		$post = JRequest::get('post');
			
		$comm_row	=& JTable::getInstance('clubcomms', 'Table');
		$comm_row->bind( $post );
		
		$comm_row->comm_message = JRequest::getVar( 'comm_message', '', 'post', 'string', JREQUEST_ALLOWRAW );  		
		$comm_row->template_id = intval(JRequest::getVar('tmp_id','0', 'post', 'int'));		
		$comm_row->comm_groups = implode(",",$comm_row->comm_groups);
		
		if(isset($note_row->note_id) && intval($note_row->note_id) > 0){
			
		}else{
			$comm_row->created = date("Y-m-d H:i:s");
			$comm_row->created_by = $user->id;		

			$comm_row->comm_status = '0';
			
		}
		
		$comm_row->store();
		
		$_REQUEST["comm_id"] = $comm_row->comm_id;
		
		$this->editcomms();
		
		
		
	}
	

}