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
 * Static class to hold controller functions for the component
 *
 * @static
 * @package		Joomla
 * @subpackage	Poll
 * @since		1.5
 */
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubStats.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubTables.php');

class ClubRegControllerStats extends JController
{
	
	function __construct()
	{
		global $mainframe;
		parent::__construct();	
		$this->registerTask("savestats","savestats");
		$this->registerTask("editstats","editstats");
		$this->registerTask("saveastats","saveastats");
		$this->registerTask("deletestats","deletestats");
	}
	
	
	function display(){	

		JRequest::setVar('view','stats');		
		parent::display($tpl);		
	}
	function savestats(){
	
		$next_action = false;
		$msg ="Unsuccessful";
				
		if(	JRequest::checkToken('post') ){	
		
			$db		=& JFactory::getDBO();
			$user		= &JFactory::getUser();
			
			$which_stats = trim(JRequest::getVar('which_stats','', 'post', 'string'));
			$stats_date = trim(JRequest::getVar('stats_date','', 'post', 'string'));	
			
			if(strlen($which_stats) > 0 && strlen(stats_date) > 0){
				
				$stats_value_ = JRequest::getVar( $which_stats, array(), 'post', 'array' );
				
				$t_explode = explode('/',$stats_date);
				$nstats_date = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
				
				foreach($stats_value_ as $a_key => $stats_value){
					$d_qry[] = sprintf("insert into %s set `member_id` = %d ,`stats_date`= %s,`stats_detail` = %s ,`stats_value` = %s on duplicate key update
							stats_value = values(stats_value);
							",CLUB_STATS_TABLE,$a_key,$db->Quote($nstats_date),$db->Quote($which_stats),$db->Quote($stats_value));
					
				}
				if(count($d_qry) > 0){
					$q_string = implode("",$d_qry);
					$db->setQuery($q_string);
					if(!$db->queryBatch()){
						$next_action = false;
						$msg = $db->getErrorMsg();
					}else{
						$next_action = true;
						$msg ="Stats Saved";
					}
				}
				unset($d_qry);			
			}			
		
		}else{
			$next_action = false;
		}
		echo json_encode(array("next_action"=> $next_action,"msg"=>$msg));		
	}
	function editstats(){
		
		global $option, $Itemid,$colon;
	
		JHTML::_('behavior.formvalidation');
	
		JRequest::checkToken("get") or jexit( 'Invalid Token' );
		
		$in_type = "hidden";
	
		$return_data['member_id'] = intval(JRequest::getVar('member_id','0', 'request', 'int'));
		$return_data['stats_date'] = trim(JRequest::getVar('stats_date','0', 'request', 'string'));		
		$return_data['stats_date'] =  str_replace(".", "/", $return_data['stats_date']);
	
		$row	=& JTable::getInstance('clubregmembers', 'Table');
		if($return_data['member_id'] > 0){
			$row->load($return_data['member_id']);
		}else{
			JError::raiseWarning( 500, "Invalid Stats Data" );
			return;
		}
			
		$stats_list	=  & JModel::getInstance('stats', 'ClubRegModel');
		$stats_list->setDate($return_data['stats_date']);
		$stat_headings =  $stats_list->getPlayerStatsList($row);
		
		$result = current($stat_headings["stats_list"]);		
		$heading_obj = $stat_headings["headings_obj"];		
	
		JHTML::_('script', 'astats.js?'.time(), 'components/com_clubreg/assets/js/');?>
		
		<form action="index2.php" method="post"  style="margin:2px;text-align:left;" name="stats_admin" id="stats_admin" class="form-validate">				
				<div class="h3"><?php echo STATS; ?> Details :: <?php echo ucwords($row->surname." ".$row->givenname) ?> </div>
				<div class="fieldset">	
				<div class="n"><label class="lbcls" for="stats_date"><?php echo STATS; ?> Date</label><?php echo $colon; ?>
			<?php 
				$format = '%d/%m/%Y';
				$name = "stats_date"; 	$id= "stats_date";			
				echo JHTML::_('calendar', $return_data['stats_date'], $name, $id, $format, array('class' => 'intext','style'=>'width:80px;','readonly'=>'readonly'));					
			?>			
			</div>				
				<?php if(count($stat_headings["headings"]) > 0 ){
						foreach($stat_headings["headings"] as $a_key => $a_value){ 	?>
						<div class="n"><label class="lbcls" for="<?php echo $a_key ?>"><?php echo $a_value ?> </label><?php echo $colon;?><?php echo ClubRegModelStats::renderControl($heading_obj[$a_key],$result); ?>
						<input type="hidden" name="stats_list[<?php echo $a_key; ?>]" value="<?php echo $a_key; ?>" />
						</div>			
					<?php }
					}?>			
			<div style="text-align:center;padding:3px;">
					<input class="button validate" name='normal_save' id="normal_save" type="submit" value='Save Details' />
			</div>			
			</div>				
				<input type="<?= $in_type ?>" name="member_id" value="<?php echo $row->member_id; ?>" />					
				<input type="<?= $in_type ?>" name="option" value="<?= $option ?>" />	
				<input type="<?= $in_type ?>" name="Itemid" value="<?= $Itemid ?>" />
				<input type="<?= $in_type ?>" name="task" value="saveastats" />	
				<input type="<?= $in_type ?>" name="c" value="stats" />
				<input type="<?= $in_type; ?>" name="no_html" value="1" />		
				<?php echo JHTML::_( 'form.token' ); ?>				
			</form>
		
		<?php 
	}
	function saveastats(){
		
		$user		= &JFactory::getUser();		
		JRequest::checkToken() or jexit( 'Invalid Token' );
			
		$stats_list_ = JRequest::getVar( "stats_list", array(), 'post', 'array' );		
		$stats_date = trim(JRequest::getVar('stats_date','', 'post', 'string'));
		$member_id = intval(JRequest::getVar('member_id',0, 'post', 'int'));
		
		$db		=& JFactory::getDBO();
		
		$d_qry = array();
		
		if(strlen(stats_date) > 0 && $member_id > 0){	

			$t_explode = explode('/',$stats_date);
			$nstats_date = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
			
			
			foreach($stats_list_ as $a_stats){				
				$stats_value = current(JRequest::getVar($a_stats,array(), 'post', 'array'));
				
				$d_qry[] = sprintf("insert into %s set `member_id` = %d ,`stats_date`= %s,`stats_detail` = %s ,`stats_value` = %s on duplicate key update
						stats_value = values(stats_value);
						",CLUB_STATS_TABLE,$member_id,$db->Quote($nstats_date),$db->Quote($a_stats),$db->Quote($stats_value));	 
				
			}
			
			if(count($d_qry) > 0){
				$q_string = implode("",$d_qry);
				$db->setQuery($q_string);
				if(!$db->queryBatch()){
					$next_action = false;
					$msg = $db->getErrorMsg();
				}else{
					$next_action = true;
					$msg ="Stats Saved";
				}
			}
			unset($d_qry);
			
			$row	=& JTable::getInstance('clubregmembers', 'Table');
			$row->load($member_id);		

			$stats_list	=  & JModel::getInstance('stats', 'ClubRegModel');
			$stats_list_ =  $stats_list->getPlayerStatsList($row);
			ClubStatsHelper::renderStatsList($stats_list_,$row);
		
		}
		
	}
	function deletestats(){
		$user		= &JFactory::getUser();
			
		$next_action = true;
		
		if(	JRequest::checkToken('get') ){
			
			$stats_date = trim(JRequest::getVar('stats_date','', 'get', 'string'));
			$member_id = intval(JRequest::getVar('member_id',0, 'get', 'int'));
			
			$db		=& JFactory::getDBO();
			
			if(strlen(stats_date) > 0 && $member_id > 0){
				
				$t_explode = explode('.',$stats_date);
				$nstats_date = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
					
				$d_qry = sprintf("select * from %s where stats_date = %s and member_id = '%d'",
						CLUB_STATS_TABLE,$db->Quote($nstats_date),$member_id);
				$db->setQuery($d_qry);
				$all_stats["old_data"] = $db->loadAssocList();		
				
				$final_data = (object)$all_stats;
				
				$other_details["primary_id"] = $member_id;
				$other_details["short_desc"] = "delete_stats";				
				
				$d_qry = sprintf("delete from %s where stats_date = %s and member_id = '%d'",
						CLUB_STATS_TABLE,$db->Quote($nstats_date),$member_id);
				$db->setQuery($d_qry);
				$db->query();
				
				if($db->getErrorNum() > 0){
					$next_action = false;
				}else{
					$next_action = true;
					ClubregHelper::save_old_data($final_data,$other_details);
				}				
			}
		}
		echo json_encode( $next_action );
	}

}