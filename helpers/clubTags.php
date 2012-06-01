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
class ClubTagsHelper{	
	
	static function renderAddTag($member_id){ global $colon;
		if($member_id > 0){
		?>
		<div style="font-size:10px;float:right;"><a href="javascript:void(0)" class="" id="tag_link" rel="<?php echo $member_id ?>">Add <?php echo TAGS ;?></a>
		<div id="tag_div" class="addtags">
			<label for="ntag" class="lbcls" style="width:auto;"><?php echo TAGS ;?></label><?php echo $colon ;?><input type="text" value="" style="width:100px;" name="newtag" id="newtag"/><br />
			<div align="center" style="padding:2px;"><input type="submit" value="Add <?php echo TAGS ;?>" class="button" id="addtag_bt" name="addtag_bt"/></div>
			<input type="hidden" name="m_id" id="m_id" value='<?php echo $member_id; ?>' />
			
			<div id="ntag_divlist" style="border-top:dashed 1px #434343"></div>
		</div>
		</div>
		
		<?php }
	}
	
	static function renderTagList($member_data){
		$tagModel	=& JModel::getInstance('tags', 'ClubRegModel');
		?>
		
		<div class="taghd"><?php echo TAGS ;?></div>
		<div id="tagList_<?php echo $member_data->member_id; ?>" style="padding:1px 0px 1px 5px;">
			<?php foreach($member_data->tag_list as $a_tag){$tagModel->renderTag($a_tag);} ?>
		</div>
		
		<?php 	
		}
	
	
}

class ClubContactHelper{
	
	static function getContactArray(){		
		
		$control_array["contact_items"] =  array("surname","givenname","emailaddress","phoneno","mobile","address","suburb","postcode");
		$special["em_"] = array("medical");
		$special["next_"] = array();
		$control_array["special"] = $special;
		return $control_array;
	}
}

?>