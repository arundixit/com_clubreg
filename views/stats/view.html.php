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


class ClubRegViewstats extends JView
{
	function display($tpl = null){
		
		global $mainframe,$append,$option,$Itemid;	
		
		global $mainframe,$append,$option,$Itemid;
		
		JHTML::_('script', 'stats.js?'.time(), 'components/com_clubreg/assets/js/');
		JHTML::_('stylesheet', 'stats.css', $append .'components/com_clubreg/assets/css/');
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$all_headings = array();//ClubCommsHelper::return_headings_comms();
		
		$all_headings["member_data"] = $member_data;
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );
		
		
		$this->assign("all_headings",$all_headings); // get all the headings
			
			
		parent::display($tpl);
			
		
		
		
	}	
	function not_authorised(){
		JError::raiseWarning( 500, "You are not authorised to view the stats" );
		return;
	}
	
}	
