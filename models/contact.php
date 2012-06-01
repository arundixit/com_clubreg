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

jimport('joomla.application.component.model');

/**
 * Club Reg Component Member Model
 *
 */
class ClubRegModelContact extends JModel
{
	var $_id = null;
	var $_data = null;

	var $contact_details = null;
	
	
	function __construct()
	{		
		parent::__construct();
	}
	function getData($member_id){
		
		$db		=& JFactory::getDBO();

		if(isset($member_id) && $member_id > 0){		
			$d_qry = sprintf("select a.`member_id`, a.`contact_detail`, a.`contact_value`
			from %s as a		
			where member_id = %d",CLUB_CONTACT_TABLE,$member_id);
			$db->setQuery( $d_qry );
			$contact_details = $db->loadObjectList('contact_detail');		
		}else{		
			$this->authorised = false;
		}	
	
		$this->contact_details = $contact_details;	
		
		return $this;
		
	}
	
	
}?>