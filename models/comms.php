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
 * Club Reg Component Communication Model
 *
 */
class ClubRegModelComms extends JModel
{
	
	var $templateDetails = null;

	
	function __construct()
	{		
		parent::__construct();
	}
	function getCommDetails($comm_id){
		
		$db		=& JFactory::getDBO();
		/**
		 * the sql var names have to be template_subject and template_text
		 */
		
		if($comm_id > 0){
			/*$where_[] = " b.template_access = 'everyone'";
			$where_[] = " b.published = 1 ";
			$where_[] = " b.template_id =  ".$template_id;*/
			
			$where_[] = " a.comm_id =  ".$comm_id;
			
			$where_str = sprintf(" where %s", implode(" and ", $where_));
			$d_qry = sprintf("select a.* ,a.comm_subject as template_subject, a.comm_message as template_text from %s as a  %s ;",CLUB_SAVEDCOMMS_TABLE, $where_str);
			$db->setQuery($d_qry);			
			$this->templateDetails = $db->loadObject();	
			
		}else{
			$this->templateDetails = new stdClass();
		}
		
		return $this;
		
	}
	
}
?>