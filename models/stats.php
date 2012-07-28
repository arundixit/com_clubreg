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
class ClubRegModelStats extends JModel
{
	
	
	
	function __construct()
	{		
		parent::__construct();
	}

	function getPlayerStatsHeaders($stats_date = null){
	
		$headings = array(); $join_ = array(); $var_string = array(); $headings_objects = array();
		$nstats_date = "";
	
		$db	=& JFactory::getDBO();
	
		$d_qry = sprintf("select concat('stats_',config_short) as value,config_name as text,params,config_text from %s
				where  which_config = '%s' and publish = 1",
				CLUB_TEMPLATE_CONFIG_TABLE,
				CLUB_PLAYER_STATS);
		$db->setQuery($d_qry);
	
		$tmp_list = $db->loadObjectList('value');// append the stats_ to the config name
		
		//$stats_date = trim(JRequest::getVar('stats_date','', 'post', 'string'));
		$t_explode = explode('/',$stats_date);
		if($stats_date && count($t_explode) == 3){
			$nstats_date = sprintf(' and cct.stats_date = \'%s-%s-%s\'',$t_explode[2],$t_explode[1],$t_explode[0] );	
		}
		foreach($tmp_list as $a_key => $a_value){
			
			$extra_key = sprintf("%s",$a_key);
			$control_params = new JParameter( $a_value->params );
			$headings[$a_key] = $a_value->text;
			if($control_params->get("control_type") == "monthyear" ){
				$key_month = $extra_key."_month";
				$key_year = $extra_key."_year";
	
				$var_string[$a_key] = sprintf("
						concat(
						group_concat(if(cct.stats_detail = '%s',stats_value,NULL))
						,' / ',
						group_concat(if(cct.stats_detail = '%s',stats_value,NULL))
				) as
						%s",
						$key_month,$key_year,$a_key);
	
			}else{
				$var_string[$a_key] = sprintf("group_concat(if(cct.stats_detail = '%s',stats_value,NULL)) as %s",
						$a_key,$a_key);
			}
				
			$a_value->control_params =  $control_params->toArray();;
			$headings_objects[$a_key] = $a_value;
		}
	
		$join_['cct'] = sprintf(" left join %s as cct on (cct.member_id = a.member_id %s )", CLUB_STATS_TABLE,$nstats_date);
	
	
		return array("var_str" => $var_string,
				'join_'=>$join_,
				'headings'=>$headings,
				'headings_obj'=>$headings_objects
		);
	
	}
	
	
	
	static function renderControl(&$control_object,&$t_result){

		$t_key = $control_object->value;		
		$t_value = $t_result->$t_key;
		
		$ctrlname =sprintf("%s[%s]",$control_object->value,$t_result->member_id);
		$control_params = $control_object->control_params;
		
		$t_style = $control_params['control_width'];
		
		if(preg_match("/px/",$t_style)){
			$t_style =" style='".$t_style."'";
		}else{
			if(isset($t_style) && strlen($t_style) > 0){
				$t_style =" class='".$t_style."'";
			}else{
				$t_style =" style='width:40px;'";
			}
		}
		
		switch($control_params["control_type"]){
				case "select":
					$t_values = explode("\n",$control_object->config_text);
					$t_array= array();
					$t_object = new stdClass() ; $t_object->value = '-1'; $t_object->text = '- Select- ';$t_array[] = $t_object;
					foreach($t_values as $a_value){
						$a_value = trim($a_value);
							
						if(strlen($a_value) > 0){
							$t_object = new stdClass() ; $t_object->value = $a_value; $t_object->text = ucwords($a_value);
							$t_array[] = $t_object;
						}
					}
					echo JHTML::_('select.genericlist',  $t_array, $ctrlname, $t_style.' id="'.$ctrlname.'"  size="1" ', 'value', 'text', $t_value);
					break;
				case "textarea":?>
					<textarea <?php echo $t_style; ?> rows=5 name="<?php echo $ctrlname; ?>" ><?php echo $t_value; ?></textarea>					
			<?php 
				break;
				case "date":
				$format = '%d/%m/%Y';
				echo JHTML::_('calendar', $t_value, $ctrlname, $ctrlname, $format, array('class' => 'intext half','readonly'=>'readonly'));
				break;
				case "monthyear":
					echo 'N/A';
				break;

				case "email":
				default:?>				
				<input type="text" <?php echo $t_style; ?> value="<?php echo $t_value; ?>" name="<?php echo $ctrlname ;?>" />
				<?php 					
				break;				
			}?>			

<?php 
	}
	static function renderControl_view(&$control_object,&$t_result){

		$t_key = $control_object->value;		
		$t_value = $t_result->$t_key;
		
		
		$control_params = $control_object->control_params;	
		
		switch($control_params["control_type"]){
				case "select":				
					echo ($t_value == "-1")?"-":ucwords($t_value);
					break;
				case "textarea":
					echo nl2br($t_value); 
				break;
				
				case "monthyear":
					echo 'N/A';
				break;
				case "date":
				case "email":
				default: echo $t_value; 
				break;				
			}		
	}
	function getPlayerStatsList($player_data){
		
		$db		=& JFactory::getDBO();
			
		$where_[] = sprintf(" member_id = %d",$player_data->member_id) ;
		
		$where_str = "where ". implode(" and ", $where_);
		
		$stats_heading = self::getPlayerStatsHeaders();
		
		$var_str[] = " date_format(cct.stats_date, '%d/%m/%Y') as stats_date ";
		
		if(count($stats_heading["var_str"]) > 0){
			$var_str = array_merge($var_str, $stats_heading["var_str"]);
		}
		
		$var_string  = implode(",", $var_str);		
		
		$d_qry = sprintf("select %s from %s as cct 
				%s
				group by cct. stats_date
				order by cct.stats_date
				",
		$var_string	,CLUB_STATS_TABLE, $where_str);
		
		$db->setQuery($d_qry);		
		$stats_heading["stats_list"] = $db->loadObjectList();	
		
		return $stats_heading;
	}
	
	
	
}
?>