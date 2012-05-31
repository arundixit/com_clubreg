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

class ClubRegViewgroups extends JView
{
	function display($tpl = null)
	{		
		
		
		$return_data['group_id'] = intval(JRequest::getVar('id','0', 'request', 'string'));
		$return_data['reg_year'] = trim(JRequest::getVar('reg_year','2011', 'request', 'string'));
		
		$menus	= &JSite::getMenu();
		$menu    = $menus->getActive();	
		
		
		if($return_data['group_id'] > 0 && $menu->query['id'] == $return_data['group_id'] ){
			
			$group_data 	=& JModel::getInstance('groups', 'ClubRegModel');
			$group_data->getData($return_data); // get the group data for current group
			
			$this->assign("group_data", $group_data);
			
		}else{
			
			echo "Invalid Access";
		}
		
		parent::display($tpl);			
	}
}