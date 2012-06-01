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

class ClubRegVieweoi extends JView
{
	function display($tpl = null)
	{		

		global $mainframe;
		
		$user		= &JFactory::getUser();
		$pathway	= &$mainframe->getPathway();
		$document	= & JFactory::getDocument();	
		$db		=& JFactory::getDBO();
		
		// Get the parameters of the active menu item
		$menus	= &JSite::getMenu();
		$menu    = $menus->getActive(); // current menu
		
		$pparams = &$mainframe->getParams('com_clubreg');		
		
		
		
		$menu_params = new JParameter( $menu->params );
		
		$layout	= $this->getLayout();
		if( $layout == 'loadeoi') {			
			$this->_displayLoadeoi($tpl);
			return;
		}
		
		
		$tpl_ = JRequest::getVar('tpl','', 'request', 'string');		
		$member_id = JRequest::getVar('id',0 , 'request', 'int');
		
		
		
		switch($tpl_){
			case "seniorplayer":
				$query = sprintf("select -1 as value, '-Select ".GROUPS."-' as text union  (select  `group_id` as value,`group_name` as text
						from %s as a where publish=1 and group_parent = 0  and 
						(params not like '%%grouptype=junior%%' or params  like '%%grouptype=senior%%') order by text asc ) order by text asc ",CLUB_GROUPS_TABLE);
			break;
			default:
				$query = sprintf("select -1 as value, '-Select ".GROUPS."-' as text union  (select  `group_id` as value,`group_name` as text
						from %s as a where publish=1 and group_parent = 0 and params like '%%grouptype=junior%%'  order by text asc ) order by text asc ",CLUB_GROUPS_TABLE);
			break;
		}
		
		
		
		
		$db->setQuery( $query );
		$current_groups = $db->loadObjectList();		
		$lists["current_groups"] = $current_groups;	
		
		$this->assignRef('lists',		$lists);
		
		// Set the document page title
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		
		if (is_object( $menu ) && 
			isset($menu->query['view']) && 
			$menu->query['view'] == 'eoi'
			) {
		
			if (!$menu_params->get( 'page_title')) {
				$pparams->set('page_title',	$menu->name);
			}
			
		} 
		$document->setTitle( $pparams->get( 'page_title' ) );
		
		//set breadcrumbs
		if (isset( $menu ) && isset($menu->query['view']) && $menu->query['view'] != 'member'){
			//$pathway->addItem($member->user_data->name, '');
		}	
		
		$this->assignRef('params', $pparams);
		
		
		
		
		if($tpl_){ $tpl = $tpl_; }
		
		parent::display($tpl);	
					
	}
	
	function eoithanks(){
		$tpl = "eoithanks" ;
		
		$eoi_template = null;
		$db		=& JFactory::getDBO();
		$d_qry = sprintf("select * from %s where template_name like '%%Expression Of Interest%%' and template_access = 'everyone' and published  = '1' ",
		CLUB_TEMPLATE_TABLE	);
		$db->setQuery($d_qry);
		$eoi_template = $db->loadObject();
		
		$this->assignRef('eoi_template', $eoi_template);
		
		parent::display($tpl);
		
	}
	function _displayLoadeoi(){
			

		global $mainframe,$option,$Itemid;
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$tpl = "default";
		
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
			
		
		$where_ = array();
		
		$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart 		= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
			
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.filter_order',		'filter_order',		'a.created ',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $option.'.filter_state',		'filter_state',		'',				'word' );
		
		$return_data['playertype'] = trim(JRequest::getVar('playertype','junior', 'request', 'string'));
		
		$all_headings = ClubregHelper::return_headings();
		$all_headings["member_data"] = $member_data;
		
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );
		
		
		if($all_headings["member_params"]->get("vieweoi") == "yes"){
		
		$filters = $all_headings["filters"];
		$filters_keys = array_keys($all_headings["filters"]);		
		

		$where_[] = " a.member_status = 'eoi' ";  // Only Eoi Members
		
		foreach($filters_keys as $a_filter){
			$t_key = "filter_".$a_filter;
			$return_data[$t_key] = trim(JRequest::getVar($t_key,null, 'request', 'string'));	
			
			
			if($return_data[$t_key]){
				
				switch($filters[$a_filter]["control"]){					
					case "text":
						$t_value = sprintf('%%%s%%',$return_data[$t_key]);
						$where_[] =  sprintf("%s like %s", $filters[$a_filter]["filter_col"],$db->Quote( $t_value));
					break;
					case "select.genericlist":	
						if(intval($return_data[$t_key]) != -1){		
							if($t_key == "filter_t_created_date"){
							
								switch($return_data[$t_key]){
									case "today":
										$where_[] = sprintf("date_format(a.created,'%%Y-%%m-%%d') = '%s'",date('Y-m-d'));
										break;
									case "7days":
										$where_[] = " a.created >= DATE_ADD(CURDATE(), INTERVAL -7 DAY) ";
										$where_[] = " a.created <= CURDATE() ";
										break;
									case "month":
										$where_[] = sprintf("date_format(a.created,'%%Y-%%m') = '%s'",date('Y-m'));
										break;
									case "lastmonth":
										$where_[] = " date_format(a.created, '%Y-%m') = date_format(CURDATE() - INTERVAL 1 MONTH, '%Y-%m') ";
										break;
								}
									
									
							}else{
								$where_[] = sprintf("%s = %s", $filters[$a_filter]["filter_col"],$db->Quote( $return_data[$t_key]));
							}
							
						}
					break;
				}
			}
		}
		
		
		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
			$filter_order_Dir = 'DESC';
		}
		
		$orderby 	= ' ORDER BY '.$filter_order.' '. $filter_order_Dir ;
		
		if(isset($return_data['playertype']) && strlen($return_data['playertype']) > 0){
			$where_[] = sprintf(" a.`playertype` = '%s'",$db->getEscaped($return_data['playertype']));
		}else{
			
		}
			
		
		
		$where_str = "";
		
		if(count($where_) > 0){
			$where_str = " where ".implode(" and ",$where_ );
		}	
		
		
		$d_qry = sprintf("select count(member_id) as howmany from %s as a %s",CLUB_EOIMEMBERS_TABLE, $where_str );
		$db->setQuery( $d_qry );
		$howmany_eois = $db->loadResult();
			
		//jimport('joomla.html.pagination');		
		//$pageNav = new JPagination( $howmany_eois, $limitstart, $limit );
		require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubPagination.php');
		$pageNav = new clubPagination( $howmany_eois, $limitstart, $limit );
		
		
		$all_string["n"] = 'a.*';
		
		$all_string["member_name"] = "concat(a.`surname`,' ' ,a.`givenname`) as surname";		
		$all_string["t_created_date"] = "date_format(a.created,'%d/%m/%Y') as t_created_date";
		$all_string["t_group"] = "b.group_name as `group`";
		$all_string["dob"] = " (if(a.dob = '0000-00-00' , '-' , date_format(a.dob,'%d/%m/%Y'))) as dob";
		$all_string["howmany"] = " count( c.member_id )  as `howmany`";
		
		$all_string["my_children"] = " concat('<ol class=\"intable\">',group_concat(concat('<li>',c.`surname`,' ' ,c.`givenname`,'</li>') ORDER BY c.`surname` ASC SEPARATOR '' ),'</ol>')  as `my_children`";
		
		$table_join ="";
		if($return_data['playertype'] == "junior"){
			$all_string["guardian"] = "concat(d.`surname`,' ' ,d.`givenname`) as guardian";
			$table_join = "left join  ".CLUB_EOIMEMBERS_TABLE." as d on ( a.parent_id = d.member_id) ";
		}
		
		
		$var_str = implode(" , ", $all_string);
		
		$group_by = "GROUP BY a.member_id"; // count howmany are registered		
		
		$d_qry = sprintf("select %s from %s as a  
		left join %s as b on (a.group = b.group_id)
		left join %s as c on ( a.member_id = c.parent_id)
		%s
		 %s %s %s", 
		$var_str, 
		CLUB_EOIMEMBERS_TABLE,
		CLUB_GROUPS_TABLE,	
		CLUB_EOIMEMBERS_TABLE,
		$table_join,
		$where_str,$group_by,$orderby );
		$db->setQuery( $d_qry, $pageNav->limitstart, $pageNav->limit  );
		$all_results = $db->loadObjectList();
			
		if($db->getErrorNum() > 0){
			write_debug($db);
		}		
		
		
		//write_debug($db);
		
		
		$all_headings["variable_string"] = $all_string;
		$this->assign('all_results',$all_results);		
		
		
		$t_array = array();	
		
		unset($t_object);
		
		$t_array = array();
		$t_object = new stdClass() ; $t_object->value = ''; $t_object->text = '<b>Player Type</b>';
		$t_array[] = $t_object;
		$t_object = new stdClass() ; $t_object->value = 'junior'; $t_object->text = 'Junior Players';
		$t_array[] = $t_object;
		$t_object = new stdClass() ; $t_object->value = 'senior'; $t_object->text = 'Senior Players';
		$t_array[] = $t_object;
		$t_object = new stdClass() ; $t_object->value = 'guardian'; $t_object->text = 'Guardian of Junior Players';
		$t_array[] = $t_object;
		$this->assign('playertype_list',$t_array );
		
		
		unset($t_object);
		$all_headings["return_data"] = $return_data;	
		$all_headings["pageNav"] =  $pageNav;		
		$all_headings["filter_order_Dir"] =  $filter_order_Dir;
		$all_headings["filter_order"] =  $filter_order;			
		
		
		$this->assign("all_headings",$all_headings); // get all the headings		
			
		parent::display($tpl);
		}else{		
			$this->not_authorised();
		}
		
		
	}
	function not_authorised(){		
		JError::raiseWarning( 500, "You are not authorised to view the EOI Members list" );
		return;
	}
	
}