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

class ClubRegViewuserreg extends JView
{

	function display($tpl = null){
			
	global $mainframe,$option,$Itemid;
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		JHTML::_('script', 'inplace.js?'.time(), 'components/com_clubreg/assets/js/');
		
		//$tpl = "default";	
		
			$layout	= $this->getLayout();			
			
			if( $layout == 'editreg') {
				$this->_editregistered($tpl);
				return;
			}
		
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user	
		
		$where_ = array();
		
		$limit 		= $mainframe->getUserStateFromRequest( $option.'.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart 		= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );	
			
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.filter_order',		'filter_order',		'a.created ',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $option.'.filter_state',		'filter_state',		'',				'word' );
		
		$return_data['playertype'] = trim(JRequest::getVar('playertype','junior', 'request', 'string'));
		$return_data['vtype'] = trim(JRequest::getVar('vtype','table', 'request', 'string'));
		
		
		
		$all_headings = ClubregHelper::return_headings_reg($member_data);
		$all_headings["member_data"] = $member_data;		
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );		
		
		if($all_headings["member_params"]->get("manageusers") == "yes"){
		
		$filters = $all_headings["filters"];
		$filters_keys = array_keys($all_headings["filters"]);		
		

		$where_[] = " a.member_status = 'registered' ";  // Only registered Members
		
		$d_url_ = array();
		
		$d_url_[] = "playertype=".$return_data['playertype'];	
		$d_url_[] = "vtype=".$return_data['vtype'];
	
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
							$d_url_[] = sprintf("%s=%s",$t_key,$return_data[$t_key]);
						}
						
					break;
				}
			}
		}
		
		$tag_subquery = sprintf(" left join ( SELECT jctp.member_id, 
		group_concat( jct.tag_text ORDER BY jct.`tag_text` ASC SEPARATOR ', ' ) AS member_tags 
		FROM %s AS jct 
		LEFT JOIN %s AS jctp ON ( jct.tag_id = jctp.tag_id ) 	
		WHERE jct.published =1 
		GROUP BY jctp.member_id ) as tmp_tags on a.member_id = tmp_tags.member_id 
		",CLUB_TAG_TABLE,CLUB_TAGPLAYER_TABLE );		
		
		$tag_vars = "  tmp_tags.member_tags ";		
		
		$all_string["member_tags"] = $tag_vars;
		
		$session =& JFactory::getSession();		
		$session->set("com_clubreg.back_url", $d_url_);// save the back url		
		
		
		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
			$filter_order_Dir = 'DESC';
		}
		
		$orderby 	= ' ORDER BY '.$filter_order.' '. $filter_order_Dir ;
		
		if(isset($return_data['playertype']) && strlen($return_data['playertype']) > 0){
			$where_[] = sprintf(" a.`playertype` = '%s'",$db->getEscaped($return_data['playertype']));
		}else{
			//
		}
		
		if(isset($all_headings["headings"]["group"])){
			$where_[] = sprintf(" ( a.`group` in (%s) or a.`group` in (0,-1))",implode(",",$member_data->all_allowed_groups)); // only grouups I am allowed to see
		}	
		
		$where_str = "";
		
		if(count($where_) > 0){
			$where_str = " where ".implode(" and ",$where_ );
		}	
		
		
		$d_qry = sprintf("select count(member_id) as howmany from %s as a %s",CLUB_REGISTEREDMEMBERS_TABLE, $where_str );
		$db->setQuery( $d_qry );
		$howmany_eois = $db->loadResult();
			
		//jimport('joomla.html.pagination');		
		//$pageNav = new JPagination( $howmany_eois, $limitstart, $limit );
		require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubPagination.php');
		$pageNav = new clubPagination( $howmany_eois, $limitstart, $limit );
		
		
		$all_string["n"] = 'a.*';
		$all_string["playertype"] = "ucase(a.playertype) as playertype";
		$all_string["member_name"] = "concat(a.`surname`,' ' ,a.`givenname`) as surname";		
		$all_string["t_created_date"] = "date_format(a.created,'%d/%m/%Y') as t_created_date";
		$all_string["t_created_by"] = "e.name as t_created_by";
		$all_string["t_group"] = "b.group_name as `group`";
		$all_string["t_sgroup"] = "sg.group_name as `sgroup`";
		$all_string["dob"] = " (if(a.dob = '0000-00-00' , '-' , date_format(a.dob,'%d/%m/%Y'))) as dob";
		$all_string["howmany"] = " count( c.member_id )  as `howmany`";
		$all_string["year_registered"] = " a.year_registered ";	
		$all_string["my_children"] = " concat('<ol class=\"intable\">',group_concat(concat('<li>',c.`surname`,' ' ,c.`givenname`,'</li>') ORDER BY c.`surname` ASC SEPARATOR '' ),'</ol>')  as `my_children`";
		$all_string["member_tag"] = $tag_vars;
		
		
		$table_join ="";
		if($return_data['playertype'] == "junior"){
			$all_string["guardian"] = "concat(d.`surname`,' ' ,d.`givenname`) as guardian";
			
			$all_string["gaddress"] = "d.address as gaddress";
			$all_string["gsuburb"] = "d.suburb as gsuburb";
			$all_string["gpostcode"] = "d.postcode as gpostcode";
			
			$table_join = "left join  ".CLUB_REGISTEREDMEMBERS_TABLE." as d on ( a.parent_id = d.member_id) ";
		}
		
		
		$var_str = implode(" , ", $all_string);
		
		$group_by = "GROUP BY a.member_id"; // count howmany are registered		
		
		$d_qry = sprintf("select %s from %s as a  
		left join %s as b on (a.group = b.group_id)
		left join %s as sg on (a.subgroup = sg.group_id)
		left join %s as c on ( a.member_id = c.parent_id)
		left join #__users as e on (a.created_by = e.id)		
		%s		
		%s		
		 %s %s %s", 
		$var_str, 
		CLUB_REGISTEREDMEMBERS_TABLE,
		CLUB_GROUPS_TABLE,	CLUB_GROUPS_TABLE,
		CLUB_REGISTEREDMEMBERS_TABLE,
		
		$table_join,		
		$tag_subquery,		
		$where_str,$group_by,$orderby );
		
		$db->setQuery( $d_qry, $pageNav->limitstart, $pageNav->limit  );
		$all_results = $db->loadObjectList();
		
		if($db->getErrorNum() > 0){
			write_debug($db);
		}		
		
		$all_headings["variable_string"] = $all_string;
		$this->assign('all_results',$all_results);		
		
		$session->set("com_clubreg.back_sql", $d_qry);// save the back url
		
		
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
		
		$t_array = array();
		
		$t_object = new stdClass() ; $t_object->value = 'table'; $t_object->text = 'Table View';
		$t_array[] = $t_object;
		$t_object = new stdClass() ; $t_object->value = 'detail'; $t_object->text = 'Detail View';
		$t_array[] = $t_object;		
		$this->assign('vtype_list',$t_array );
		
		
		unset($t_object);
		$all_headings["return_data"] = $return_data;	
		$all_headings["pageNav"] =  $pageNav;		
		$all_headings["filter_order_Dir"] =  $filter_order_Dir;
		$all_headings["filter_order"] =  $filter_order;		
		$all_headings["page_type"] =  "registered";
		
		$all_headings["where_clause"] = $where_;
		
		
		$this->assign("all_headings",$all_headings); // get all the headings			
		parent::display($tpl);
		
		}else{		
			$this->not_authorised();
		}
	
	}
	
	function not_authorised(){
		JError::raiseWarning( 500, "You are not authorised to view the Members list" );
		return;
	}
	function _editregistered($tpl){
	
		
		global $mainframe,$option,$Itemid;
		
		$edit_url = array();
		
		JHTML::_('script', 'group.js?'.time(), 'components/com_clubreg/assets/js/'); // add the subgroup filter javascript
		JHTML::_('script', 'registration.js?'.time(), 'components/com_clubreg/assets/js/');
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();	
		
		$session =& JFactory::getSession();
		$d_url_ = $session->get("com_clubreg.back_url");
		
		$back_url = sprintf("index.php?option=%s&c=userreg&task=loadregistered&Itemid=%d&%s",$option,$Itemid,@implode("&",$d_url_));
		
		
		$d_sql_ = $session->get("com_clubreg.back_sql");			
		
		$this->assign("back_url",$back_url);
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		
		$return_data['member_id'] = intval(JRequest::getVar('member_id','0', 'request', 'int'));	

		
		$this->assign("edit_url",array());
		$return_data['ordinal'] = intval(JRequest::getVar('ordinal','0', 'request', 'int'));
			
		if($d_sql_ && $return_data['member_id'] > 0){	
				
			$howmany = 3; 
			$offset = 0;
			
			if(($return_data['ordinal'] - $offset) <= 1){
				$lm_start = 0;
				$howmany = 2;
				
				$check_counter = false; // when you are the begining of the list
			}else{
				$lm_start = $return_data['ordinal']-2;				
				$check_counter = true; // when you are at the end of the list
			}	
			
			$d_qry = sprintf("SET @pos=%d;",$lm_start);
			$db->setQuery($d_qry);
			$db->query();		
			
			$d_sql_ = str_replace("a.*", "@pos:=@pos+1 as ordinal,a.*", $d_sql_);
			
			$db->setQuery( $d_sql_, $lm_start, $howmany  );
			$recordset = $db->loadObjectList();			
			
			if($check_counter){
				if(count($recordset) == 2){
					$a_record = $recordset[0]; // there is no next
					$edit_url["prev"] =sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
				}else{
					$a_record = $recordset[0];
					$edit_url["prev"] = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
					$a_record = $recordset[2];
					$edit_url["next"] = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);
				}				
			}else{
				$a_record = $recordset[1]; // there is no previous
				$edit_url["next"] = sprintf("index.php?option=%s&c=userreg&task=editreg&Itemid=%s&member_id=%d&ordinal=%d",$option,$Itemid,$a_record->member_id,$a_record->ordinal);				
			}	
			
			
			foreach($recordset as $a_record){
				//echo $a_record->ordinal," - ",$a_record->surname,"<br />";
			}
			
			
		}
		$this->assign("edit_url",$edit_url);
		$this->assign("ordinal", $return_data['ordinal']);
		
		
		$row	=& JTable::getInstance('clubregmembers', 'Table');	
		$this->assign('last_update',null);		
		
		if($return_data['member_id'] > 0){
			$row->load($return_data['member_id']);
			
			$contact_details 	=& JModel::getInstance('contact', 'ClubRegModel');
			$contact_details->getData($return_data['member_id']); // get the member data for current user
			$this->assign("contact_details",$contact_details);	
			
			
			$d_qry = sprintf("select a.*, b.name from %s as a left join #__users as b on 
			(a.createdby = b.id) where a.primary_id = %d and a.short_desc = 'updated %s' order by a.id desc limit 1; ", CLUB_AUDIT_TABLE,
			$return_data['member_id'], $row->playertype);
			$db->setQuery( $d_qry );
			$last_update= $db->loadObject();

			if($last_update){						
				$t_date = $last_update->created_date." ".$last_update->created_time;
				$created_date =& JFactory::getDate($t_date);
				$last_update->created_date =  $created_date->toFormat('%d/%m/%Y %H:%M:%S');				
				$this->assign('last_update',$last_update);
			}
			
			$d_qry = sprintf("select a.tag_id, a.tag_text,member_id from %s as a left join %s as b on (a.tag_id = b.tag_id) 
			where member_id = %d order by a.tag_text asc",
			CLUB_TAG_TABLE,CLUB_TAGPLAYER_TABLE,$return_data['member_id']);
			$db->setQuery($d_qry);
			$tag_list = $db->loadObjectList();			
		}else{
			$next_action = isset($_REQUEST["next_action"])?trim(JRequest::getVar( "next_action", null, 'post', 'string' )):null;
			if($next_action){
				
			}else{
			
				foreach($row as $a_key => $t_value){
					if($a_key[0] == "_") continue;
					$t_key = "g_".$a_key;
					
					if($a_key == "dob"){
						// try reformating the date
						$t_explode = explode('/',JRequest::getVar( $t_key, '', 'post', 'string' ));
						if(count($t_explode) == 3)
							$row->$a_key = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
					}else{				
						$row->$a_key = JRequest::getVar($t_key,'', 'request', 'string');
					}
					
				}
			}
			$row->playertype = trim(JRequest::getVar('playertype','', 'request', 'string'));
			$tag_list = array();
		}		
		
		$row->tag_list = $tag_list;
		
		$all_headings["member_params"] =  new JParameter( $member_data->user_data->params );		
		
		$this->assign("all_headings",$all_headings);
		$this->assign("member_data",$row);	
			
		
		$lists["year_registered_list"] = ClubregHelper::generate_seasonList();
		
		$lists["member_levels"] = ClubregHelper::getMemberLevels();		
		
		
		if($all_headings["member_params"]->get("manageusers") == "yes"){
			
			$group_where[] = "publish=1";
			$group_where[] = "group_parent = 0 ";
			$group_where[] = " a.group_id in (".implode(",",$member_data->all_allowed_groups).")";			
			
			switch($row->playertype){
				case "senior":
					$tpl = "senior";					
					$group_where[] = "(params not like '%%grouptype=junior%%' or params  like '%%grouptype=senior%%')";
					break;
				
				case "junior":
					
					$tpl = "junior";
					if($row->parent_id > 0){
						$parent_data = & JTable::getInstance('clubregmembers', 'Table');	
						$parent_data->load($row->parent_id);						
					
						$this->assign("parent_data",$parent_data);
					}
						$d_qry = sprintf("select -1 as value, '- Select Guardian -' as text union 
						(select member_id as value, concat(`surname`,' ' ,`givenname`) as text from %s 
						where playertype ='guardian' order by 
						surname asc) ",CLUB_REGISTEREDMEMBERS_TABLE);
						$db->setQuery($d_qry);
						$all_parents = $db->loadObjectList();						
						
						$this->assign("parent_list",$all_parents);					
					
					$group_where[] = "params like '%%grouptype=junior%%'";
					
				break;
				default:	
					$tpl = "guardian";$lists["children"] =  array();
					if($row->member_id > 0){
						$d_qry = sprintf("select * from %s where parent_id = %d",CLUB_REGISTEREDMEMBERS_TABLE,$row->member_id);
						$db->setQuery( $d_qry );
						$all_children = $db->loadObjectList();
						$lists["children"] = $all_children;						
					}		
					
					$group_where[] = "params like '%%grouptype=junior%%'";				
					
				break;
			}		
			
			/**
				get allowed groups
			 */
			$query = sprintf("select -1 as value, '-Select ".GROUPS."-' as text union  (select  `group_id` as value,`group_name` as text
												from %s as a where %s
												 order by text asc ) 
												order by text asc ",CLUB_GROUPS_TABLE,implode(" and ", $group_where));
		
			$db->setQuery( $query );
			$current_groups = $db->loadObjectList();
			$lists["current_groups"] = $current_groups;	
			
			$group_data 	=& JModel::getInstance('groups', 'ClubRegModel');		
			$lists["subgroups"] =  $group_data->load_subgrougs($row->group); // get the subgroups of the current group
			
			
			$this->assign("lists",$lists);		
			
			if(isset($row->member_id) && intval($row->member_id) > 0){
				$this->assign("payment_list", ClubPaymentsHelper::getPaymentList($row));				
				$this->assign("note_list", ClubNotesHelper::getNoteList($row));
			}
			
			parent::display($tpl);
		}else{		
			JError::raiseWarning( 500, "You are not authorised to Edit the Member Details" );
		}
		
		return;
	}
	
function _render_extra_details(&$pane){
	
	$title = JText::_('Extra '.PLAYER.'Details');
	$title = tryUseCookies($title ,0,$tab_id);
	echo $pane->startPanel($title, "detail-page1");
	//echo $this->loadTemplate("extradetails")	;
	echo $pane->endPanel();
	
}
	
}

function wContact(&$conact_array,$contact_key,$index){	
	$index = $contact_key.$index;
	return isset($conact_array[$index])?$conact_array[$index]->contact_value:"";	
}