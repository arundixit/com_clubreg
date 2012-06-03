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

$member_data = $this->member_data;

$page_title = (intval($member_data->member_id) > 0)?($member_data->surname." ".$member_data->givenname):"Registering New Details";

$member_params = $this->all_headings["member_params"];
ClubregHelper::generate_menu_tabs($member_params,$page_title );

JHTML::_('behavior.formvalidation');
JHTML::_('behavior.calendar');

$append = '';
$in_type = "hidden";
global $option,$Itemid;
$document =& JFactory::getDocument();
$document->setTitle($page_title );

$colon = "<span><b>:</b>&nbsp;&nbsp;&nbsp;</span>";
$show_key = array("surname","givenname","emailaddress","mobile","address","created"); // guardian
ob_start();
?>

var span_array = new Array('<?php  echo implode("','",$show_key); ?>');


	function simpleValidate(){
			$('normal_save').addEvent('click',function(){
					f = this.form;
				 if (document.formvalidator.isValid(f)) {
				 	f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
     	 			return true;				 
				 }else{			
					var msg = 'Some values are not acceptable.  Please retry.';	
					if($('g_surname').hasClass('invalid')){msg += '\n\t* Invalid Surname';}
					if($('g_givenname').hasClass('invalid')){msg += '\n\t* Invalid <?php echo GIVENNAME; ?>';}
					
					alert(msg);
				}		
				return false;
			});	
			
			$('g_parent_id').addEvent('change', function (){
				
				var parent_id = $('g_parent_id').get('value');
				
				var a = new Ajax( 'index2.php?option=<?php echo $option; ?>&Itemid=<?php echo $Itemid; ?>&c=userreg&task=jparent&no_html=1&jparent_id='+parent_id, {
				method: 'get',
				onRequest: function(){
					$('loading').addClass('loading');
				},
				onComplete: function(){
									
						 var data = Json.decode(this.response.text);	
						 
						 $('loading').removeClass('loading');		
						
						for(i = 0; i < span_array.length; i++){						
							$('span_'+span_array[i]).set('html',data[span_array[i]]);
						}
					}
				}).request();
				
				return false;			
			});
			
			
	};
	
	var tagWord = '<?php echo TAG ;?>';
	
	Window.onDomReady(simpleValidate);
<?php 
		$t_script = ob_get_contents();
		ob_end_clean();
	 	$document->addScriptDeclaration($t_script);
	 	
	 	$tab_id = isset($member_data->member_id)?$member_data->member_id:0;
	 	
	 	$cookie_key = 'pl_tabs_'.$tab_id;
	 	$pane_offset = isset($_COOKIE[$cookie_key])?$_COOKIE[$cookie_key]:0;
	 	
	 	jimport('joomla.html.pane');
	 	$pane	= &JPane::getInstance('tabs', array('useCookies'=>true,'startOffset'=>$pane_offset));
?>
<div class="top_buttons">
<div class="left_buttons"><input class="button" name='back_' id="back_url" type="button" onclick="document.location='<?php echo $this->back_url; ?>'"  value='<?php echo JText::_('Back To List'); ?>' /></div>
<?php $edit_url = $this->edit_url; ClubHiddenHelper::renderButtons($edit_url); ?>
</div><p class="cl"></p>
<?php 
$title = JText::_(PLAYER.' Details');
$title = tryUseCookies($title ,0,$tab_id);

echo $pane->startPane("player-pane");
echo $pane->startPanel($title, "detail-page1");
?>
<div class="h3">Junior <?= PLAYER ?> Details</div>	
<form action="index.php" method="post" name="adminForm"   id="adminForm"  class="form-validate">
<div class="fieldset">
	<?php ClubTagsHelper::renderAddTag($member_data->member_id); ?>
	
		<div class="n">
		<label class="lbcls" for="g_memberid">Member Id</label><?php echo $colon ;?><input type="text" class="intext" name="g_memberid" id="g_memberid" value="<?php echo $member_data->memberid;	?>"/>
		</div>
		<div class="n">
		<label class="lbcls" for="g_memberlevel">Membership Level</label><?php echo $colon ;
				$name = "g_memberlevel";	$id= "g_memberlevel";	$t_level = $member_data->memberlevel;
				echo JHTML::_('select.genericlist',  $this->lists['member_levels'], $name, 'class="intext" id="'.$id.'"  size="1" ', 'value', 'text', $t_level);?>	
		</div>
		<div class="n">
		<label class="lbcls" for="g_surname">Surname <span class="isReq">*</span></label><?php echo $colon ;?><input type="text" class="intext required" name="g_surname" id="g_surname" value="<?php echo $member_data->surname;	?>"/>
		</div>
		<div class="n">		
		<label class="lbcls" for="g_givenname"><?php echo GIVENNAME; ?> <span class="isReq">*</span></label><?php echo $colon ;?><input type="text" class="intext required" name="g_givenname" id="g_givenname" value="<?php echo $member_data->givenname;	?>"/>
		</div>
		<div class="n">
		<label class="lbcls" for="g_gender">Gender</label><?php echo $colon ;?>
		<?php $t_gender = $member_data->gender;	?>
			<input type="radio" name="g_gender" value="male" <?php echo ($t_gender == "male")?"checked":""; ?>/>Male <input type="radio" name="g_gender" value="female" <?php echo ($t_gender == "female")?"checked":""; ?>/>Female 
		<?php  $t_sendnews = ($member_data->send_news != -1)?"checked":""; ?>&nbsp;&nbsp;&nbsp;&nbsp;	
		</div>
		<div class="n">
		<label class="lbcls" for="g_dob">Date Of Birth</label><?php echo $colon ;?>
			<?php 
				$format = '%d/%m/%Y';
				$name = "g_dob";
				$id= "g_dob";$value = "";
				$value_ = explode("-",$member_data->dob);
				//
				if(count($value_) == 3 && !preg_match("/0000-00-00/",$member_data->dob)){					
					$value = sprintf("%s/%s/%s",$value_[2],$value_[1],$value_[0]);
				}else 
					$value ="";
				echo JHTML::_('calendar', $value, $name, $id, $format, array('class' => 'intext','style'=>'width:80px;','readonly'=>'readonly')); 
			?>
		</div>		
		<div class="taghd"><?php echo GROUP; ?> Details</div>
		<div class="n">
		<label class="lbcls" for="g_group"><?php echo GROUP; ?>&nbsp;:&nbsp;&nbsp; </label><?php echo $colon ;?><?php		
		$name = "g_group";	$id= "g_group";	$t_group = $member_data->group;
		echo JHTML::_('select.genericlist',  $this->lists['current_groups'], $name, 'class="intext" id="'.$id.'"  size="1" ', 'value', 'text', $t_group);
		?>
		</div>
		<div class="n">
		<label class="lbcls" for="g_subgroup"><?php echo SUBGROUP; ?></label><?php echo $colon ;
		$t_prop=" style=\"width:200px;\"";  $name = "g_subgroup";	$id= "g_subgroup"; 	$t_group = $member_data->subgroup;				
		echo JHTML::_('select.genericlist',  $this->lists['subgroups'], $name, 'class="intext" id="'.$id.'"  size="1" ', 'value', 'text', $t_group);
		?>
		</div>
		
		<div class="n">
	<label class="lbcls" for="g_year_registered">Season</label><?php echo $colon ;?><?php
		$t_prop=" style=\"width:80px;\"";
		$name = "g_year_registered";
		$id= "g_year_registered";
		$t_value = ($member_data->year_registered)?$member_data->year_registered:date('Y');
		echo JHTML::_('select.genericlist',  $this->lists['year_registered_list'], $name, 'class="inputbox" id="'.$id.'"  size="1" '.$t_prop, 'value', 'text', $t_value);
	?>
	</div>
	<div class="taghd">Guardian Details</div>
<?php  	
		$name = $id = "g_parent_id";
		 $t_prop = " style='width:200px;'";
		?><div class="n"><label class="lbcls">Guardian List </label><?php echo $colon ;?><?php 
		$current_parent = isset($this->parent_data)?$this->parent_data->member_id:-1;
		echo JHTML::_('select.genericlist',  $this->parent_list, $name, 'class="intext" id="'.$id.'"  size="1" ', 'value', 'text',$current_parent );
		?><span class="" id="loading"> &nbsp; </span>
		</div>
		<?php 		
			foreach($show_key  as $tkey ){
				if($tkey[0] == "_") continue;
				?><div class="n">
					<label class="lbcls"><?php echo ucwords($tkey); ?> </label><?php echo $colon ;?><span id="span_<?php echo $tkey ?>"><?php echo isset($this->parent_data)?trim($this->parent_data->$tkey):""; ?></span>
				</div>				
		<?php 	} ?>		
	<div class="taghd"><?php echo EMERGENCY; ?> Details</div>
	<?php 	
	$this->contact_details->contact_key = "em_" ;
	echo $this->loadTemplate("emcontact");
	if( isset($member_data->member_id) && $member_data->member_id > 0){
		ClubTagsHelper::renderTagList($member_data);
		$save_tag = "Update Details";
	}else{
		$save_tag = "Save Details";
	}
	
	ClubregHelper::renderLastUpdate($this->last_update);
	
	
	?>	
</div> <!-- field set -->
<br />
<div class="center">
<input class="button" name='back_' id="back_url" type="button" onclick="document.location='<?php echo $this->back_url; ?>'"  value='<?php echo JText::_('Back To List'); ?>' />
<input class="button validate" name='normal_save' id="normal_save" type="submit"  value='<?php echo JText::_($save_tag); ?>' />
<?php if(!$member_data->member_id) {?>
<input class="button validate" name='saveNnew' id="normal_save" type="submit"  value='<?php echo JText::_('Save and Add Another'); ?>' />
<?php } ?>
</div>
	
	<input type="<?= $in_type ?>" name="member_id" value="<?php echo $member_data->member_id; ?>" />	
	<input type="<?= $in_type ?>" name="g_playertype" value="junior" />
	<input type="<?= $in_type ?>" name="option" id="option" value="<?= $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" id="Itemid" value="<?= $Itemid ?>" />	
	<input type="<?= $in_type ?>" name="task" value="save_details" />	
	<input type="<?= $in_type ?>" name="c" value="userreg" />
	<input type="<?= $in_type ?>" name="ordinal" value="<?php echo $this->ordinal; ?>" />
	<?php 	
	echo JHTML::_( 'form.token' );
?>
</form>
<?php 
	echo $pane->endPanel();
	if( isset($member_data->member_id) && $member_data->member_id > 0){
		global $use_tab;
		$use_tab = true;
		
		$this->_render_extra_details($pane,$tab_id);
		
		$title = JText::_('Payment Details');
		$title = tryUseCookies($title ,2,$tab_id);
		echo $pane->startPanel($title, "detail-page3");
		echo $this->loadTemplate("payments")	;
		echo $pane->endPanel();
		
		$title = JText::_('Notes');
		$title = tryUseCookies($title ,3,$tab_id);
		echo $pane->startPanel($title, "detail-page4");
		echo $this->loadTemplate("notes")	;
		echo $pane->endPanel();
		
	}	
	 ?>

<?php 
echo $pane->endPane();

ClubregHelper::write_footer(); ?>
