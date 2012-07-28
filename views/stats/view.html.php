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

//error_reporting(-1);

class ClubRegViewstats extends JView
{
	function display($tpl = null){
		
		global $mainframe,$append,$option,$Itemid;	
		
		global $mainframe,$append,$option,$Itemid;
		
		JHTML::_('script', 'stats.js?'.time(), 'components/com_clubreg/assets/js/');
		JHTML::_('stylesheet', 'stats.css', $append .'components/com_clubreg/assets/css/');
		
		$layout	= $this->getLayout();
		
		if( $layout == 'editmemberstats') {
			$this->_editmemberstats($tpl);
			return;
		}
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$all_headings = array();//ClubCommsHelper::return_headings_comms();	
		$where_ = array();
		
		$limit 		= $mainframe->getUserStateFromRequest( $option.'.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart 		= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.filter_order',		'filter_order',		'a.created ',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $option.'.filter_state',		'filter_state',		'',				'word' );
		
		$return_data['playertype'] = trim(JRequest::getVar('playertype','junior', 'request', 'string'));				
		$return_data['stats_date'] = trim(JRequest::getVar('stats_date',date('d/m/Y'), 'request', 'string'));
		$return_data['jaykenzo'] = trim(JRequest::getVar('jaykenzo',"viewonly", 'request', 'string')); // readonly or edit
		
		$all_headings = ClubStatsHelper::return_headings($member_data);
		$all_headings["member_data"] = $member_data;
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );
		
		$all_headings["headings_obj"] = array();
		
		$all_headings["switcher"]["viewonly"] = array("edit", "Edit ".STATS);
		$all_headings["switcher"]["edit"] = array("viewonly","View Only");
		
		$table_join ="";
		$table_join_ = array(); $xtra_var_string_ = array();
		
		if($all_headings["member_params"]->get("manageusers") == "yes"){	
			
			$stats_list	=  & JModel::getInstance('stats', 'ClubRegModel');
			$stat_headings = $stats_list->getPlayerStatsHeaders($return_data['stats_date']);
			
			if(count($stat_headings["headings"]) > 0){
				$all_headings["headings_obj"] = array_merge($all_headings["headings_obj"],$stat_headings["headings_obj"]);
			}
			
			if(count($stat_headings["headings"]) > 0){
				$all_headings["headings"] = array_merge($all_headings["headings"],$stat_headings["headings"]);
			}
			
			if(count($stat_headings["join_"]) > 0){
				$table_join_ = array_merge($table_join_, $stat_headings["join_"]);			
			}
			
			if(count($stat_headings["var_str"]) > 0){
				$xtra_var_string_ = array_merge($xtra_var_string_, $stat_headings["var_str"]);
			}
		
		$all_headings["filter_order_Dir"] =  $filter_order_Dir;
		$all_headings["filter_order"] =  $filter_order;		
		
		
		$filters = $all_headings["filters"];
		$filters_keys = array_keys($filters);
		
		if(isset($return_data['playertype']) && strlen($return_data['playertype']) > 0){
			$where_[] = sprintf(" a.`playertype` = '%s'",$db->getEscaped($return_data['playertype']));
		}		
		
		$where_[] = " a.member_status = 'registered' ";  // Only registered Members		
		
		if(isset($all_headings["headings"]["group"])){
			$where_[] = sprintf(" ( a.`group` in (%s) or a.`group` in (0,-1))",implode(",",$member_data->all_allowed_groups)); // only grouups I am allowed to see
		}
		
		$d_url_ = array();
		
		$d_url_[] = "stats_date=".$return_data['stats_date'];
		$d_url_[] = "jaykenzo=".$return_data['jaykenzo'];
		
		
		foreach($filters_keys as $a_filter){
			$t_key = "filter_".$a_filter;
			$return_data[$t_key] = trim(JRequest::getVar($t_key,null, 'request', 'string'));
		
			if($return_data[$t_key]){		
				switch($filters[$a_filter]["control"]){
					case "text":
						$t_value = sprintf('%%%s%%',$return_data[$t_key]);
						$where_[] =  sprintf("%s like %s", $filters[$a_filter]["filter_col"],$db->Quote( $t_value));
						$d_url_[] = sprintf("%s=%s",$t_key,$return_data[$t_key]);
					break;
					case "select.genericlist":
						if(intval($return_data[$t_key]) != -1){						
							$where_[] = sprintf("%s = %s", $filters[$a_filter]["filter_col"],$db->Quote( $return_data[$t_key]));
							$d_url_[] = sprintf("%s=%s",$t_key,$return_data[$t_key]);
						}		
					break;
				}
			}
		}		
		
		$where_str = "";
		
		if(count($where_) > 0){
			$where_str = " where ".implode(" and ",$where_ );
		}
		
		$session =& JFactory::getSession();
		$session->set("com_clubreg.back_url", $d_url_);// save the back url
		
		
		$d_qry = sprintf("select count(member_id) as howmany from %s as a %s",CLUB_REGISTEREDMEMBERS_TABLE, $where_str );
		$db->setQuery( $d_qry );
		$howmany_eois = $db->loadResult();
		
		require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubPagination.php');
		$pageNav = new clubPagination( $howmany_eois, $limitstart, $limit );
				
		$all_string["n"] = 'a.*';
		$all_string["memberlevel"] = "a.memberlevel";
		$all_string["playertype"] = "ucase(a.playertype) as playertype";
		$all_string["member_name"] = "concat(a.`surname`,' ' ,a.`givenname`) as surname";
		$all_string["t_created_date"] = "date_format(a.created,'%d/%m/%Y') as t_created_date";
		$all_string["t_created_by"] = "e.name as t_created_by";
		$all_string["t_group"] = "b.group_name as `group`";
		$all_string["t_sgroup"] = "sg.group_name as `sgroup`";
		
		$all_string["year_registered"] = " a.year_registered ";
		
		$all_string = array_merge($all_string,$xtra_var_string_);
		
				
		$var_str = implode(" , ", $all_string);
		
		$group_by = "GROUP BY a.member_id"; // count howmany are registered
		
		$table_join =  implode("\n",$table_join_);
		
		$d_qry = sprintf("select %s from %s as a
				left join %s as b on (a.group = b.group_id)
				left join %s as sg on (a.subgroup = sg.group_id)				
				left join #__users as e on (a.created_by = e.id)
				%s
				%s
				%s %s %s",
				$var_str,
				CLUB_REGISTEREDMEMBERS_TABLE,
				CLUB_GROUPS_TABLE,	CLUB_GROUPS_TABLE,		
		
				$table_join,
				$tag_subquery,
				$where_str,$group_by,$orderby );
		
		$db->setQuery( $d_qry, $pageNav->limitstart, $pageNav->limit  );
		$all_results = $db->loadObjectList();
		
		if($db->getErrorNum() > 0){
			write_debug($db);
		}
				
		$all_headings["return_data"] = $return_data;		
		$all_headings["pageNav"] = $pageNav;
		$all_headings["variable_string"] = $all_string;
		$this->assign('all_results',$all_results);		
			
		unset($t_object); 	$t_array = array();
		
		$this->assign("all_headings",$all_headings); // get all the headings
		
		$t_object = new stdClass() ; $t_object->value = ''; $t_object->text = '<b>'.PLAYER.' Type</b>';
		$t_array[] = $t_object;
		$t_object = new stdClass() ; $t_object->value = 'junior'; $t_object->text = 'Junior '.PLAYER;
		$t_array[] = $t_object;
		$t_object = new stdClass() ; $t_object->value = 'senior'; $t_object->text = 'Senior '.PLAYER;
		$t_array[] = $t_object;		
		$this->assign('playertype_list',$t_array );
		
		unset($t_object);
			
		//unset($member_data);
		$tpl = "renderstats";
		parent::display($tpl);	
		}else{
			$this->not_authorised();
		}	
	}	
	function not_authorised(){
		JError::raiseWarning( 500, "You are not authorised to view the stats" );
		return;
	}
	function _editmemberstats(){
		
		
		write_debug($_REQUEST);
		parent::display($tpl);
	}
	
}	
