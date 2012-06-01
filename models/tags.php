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
 * Club Reg Component Tag Model
 *
 */
class ClubRegModelTags extends JModel
{
	
	
	function __construct()
	{		
		parent::__construct();
	}
	function getTags($member_id){
		
		$db		=& JFactory::getDBO();
		
		$d_qry = sprintf("select a.tag_id, a.tag_text,member_id from %s as a left join %s as b on (a.tag_id = b.tag_id)
							where member_id = %d order by a.tag_text asc",
		CLUB_TAG_TABLE,CLUB_TAGPLAYER_TABLE,$member_id);
		$db->setQuery($d_qry);
		
		
		return $db->loadObjectList();	
		
	}
	function renderTag($a_tag){		
		$rel = sprintf("%s_%s",$a_tag->tag_id,$a_tag->member_id); ?>
			<span id="tag_<?php echo $rel; ?>" class="spn_tag">
				<img src="components/com_clubreg/assets/images/delete.png" onclick="process_tag('<?php echo $rel; ?>','<?php echo JUtility::getToken()?>')"/><?php echo $a_tag->tag_text; ?>
			</span>
	<?php
	}
	function getNotTag($member_id){ // get tags that have not been added to this member
		
		$db		=& JFactory::getDBO();		
				
		$d_qry = sprintf("select a.tag_id, a.tag_text from %s as a 
		where a.tag_id not in (select tag_id from  %s where member_id = %d) order by a.tag_text asc",
		CLUB_TAG_TABLE,CLUB_TAGPLAYER_TABLE,$member_id);
		$db->setQuery($d_qry);	
		
		return $db->loadObjectList();		
	}
	
}
?>