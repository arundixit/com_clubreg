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

class ClubRegViewworkflow extends JView
{

	function display($tpl = null){
			
		global $mainframe,$option,$Itemid;
		
		
			
		parent::display($tpl);
		
	
	}
	
	
	function _editflow($tpl){
				
		global $mainframe,$option,$Itemid;			
		parent::display($tpl);
		
		return;
	}
	
}