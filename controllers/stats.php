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
		//$this->registerTask("saveastats","savestats");
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
	
		$document =& JFactory::getDocument();
	
		JRequest::checkToken("get") or jexit( 'Invalid Token' );
	
		$return_data['member_id'] = intval(JRequest::getVar('member_id','0', 'request', 'int'));
		$return_data['stats_date'] = trim(JRequest::getVar('stats_date','0', 'request', 'string'));
	
		$row	=& JTable::getInstance('clubregmembers', 'Table');
		if($return_data['member_id'] > 0){
			$row->load($return_data['member_id']);
		}else{
			JError::raiseWarning( 500, "Invalid Stats Data" );
			return;
		}
			
	
		JHTML::_('script', 'stats.js?'.time(), 'components/com_clubreg/assets/js/');?>
		
		<form action="index2.php" method="post"  style="margin:2px;text-align:left;" name="stats_admin" id="stats_admin" class="form-validate">				
				<div class="h3">Stats Details For <?php echo $row->surname," ",$row->givenname ?> </div>
				<div class="fieldset">	
				
				</div>
				
			</form>
		
		<?php 
	}

}