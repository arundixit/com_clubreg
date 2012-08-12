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
class ClubRegModelTemplates extends JModel
{
	
	var $template_list = null;
	var $template_list_text = null;

	
	function __construct()
	{		
		parent::__construct();
	}
	function getTemplates($write_text = false){
		
		$db		=& JFactory::getDBO();
		global $Itemid,$option;
		
		$where_[] = " template_access = 'everyone'";
		$where_[] = " published = 1 ";
		
		$where_str = sprintf(" where %s", implode(" and ", $where_));
		$d_qry = sprintf("select '0' as template_id, 'New Message' as template_name  union  
				select template_id, template_name from %s %s order by template_name ;",CLUB_TEMPLATE_TABLE, $where_str);
		$db->setQuery($d_qry);
		$this->template_list = $db->loadObjectList();	
		
		if(count($this->template_list) > 0 && $write_text){
			
			$loc_url = sprintf("index.php?option=%s&Itemid=%s&c=comms&task=editcomms&tmp_id=",$option,$Itemid);
			ob_start();
			?><select id="template_list" class="shading1" onchange="document.location='<?php echo $loc_url;?>'+this.value">
			<option value="-1">- Select -</option>
			<?php 
			foreach($this->template_list as $a_template){
				$d_url = sprintf("index.php?option=%s&Itemid=%s&c=comms&task=editcomms&tmp_id=%d",$option,$Itemid,$a_template->template_id);
				?>
				<option value="<?php echo $a_template->template_id; ?>"><?php echo $a_template->template_name ?></option>
				<?php 
			}
			?>
			</select>
			<?php 
		}
		$t_string = ob_get_contents();
		ob_end_clean();
		
		$this->template_list_text = $t_string;
		return $this;	
		
	}
	function getTemplateDetails($template_id){
		
		$db		=& JFactory::getDBO();
		
		if($template_id > 0){
			$where_[] = " template_access = 'everyone'";
			$where_[] = " published = 1 ";
			$where_[] = " template_id =  ".$template_id;
			
			$where_str = sprintf(" where %s", implode(" and ", $where_));
			$d_qry = sprintf("select * from %s %s order by template_name;",CLUB_TEMPLATE_TABLE, $where_str);
			$db->setQuery($d_qry);
			$this->templateDetails = $db->loadObject();
		}else{
			$this->templateDetails = new stdClass();
		}
		
		return $this;
		
	}
	
}
?>