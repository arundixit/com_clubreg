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

 class ClubRegModelEoimembers extends JModel
 {
 	var $_id = null;
 	var $_data = null;
 
 	var $user_data = null;
 	var $group_leaders = null;
 	var $group_members = null;
 	var $member_detials = null;
 
 	function __construct()
 	{
 		parent::__construct();
 	}
 	function getChildren(&$all_headings,&$parent_data){
 		
 		//write_debug($all_headings);
 		
 		
 		$db		=& JFactory::getDBO(); 		
 	
 		
 		unset($all_headings["variable_string"]["howmany"]);
 		$var_str = implode(" , ", $all_headings["variable_string"]); 	

 		$where_[] = sprintf("a.parent_id = %s",$parent_data->member_id);  // ignore junior players
 		
 		$where_str = "";
 		
 		if(count($where_) > 0){
 			$where_str = " where ".implode(" and ",$where_ );
 		}
 		
 		$d_qry = sprintf("select %s from %s as a
 				left join %s as b on (a.group = b.group_id) 				
 				 %s %s %s", 
 		$var_str,
 		CLUB_EOIMEMBERS_TABLE,
 		CLUB_GROUPS_TABLE, 		
 		$where_str,$group_by,$orderby );
 		$db->setQuery( $d_qry );
 		$all_results = $db->loadObjectList();
 			
 		if($db->getErrorNum() > 0){
 			write_debug($db);
 		}
 		
 		//write_debug($all_results);
 		
 	}
 	
 }