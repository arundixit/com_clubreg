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

global $colon;

$document =& JFactory::getDocument();

$in_type = "hidden";
$page_title = "Manage Communications";
$document->setTitle($page_title );

JHTML::_('behavior.formvalidation');


$member_params = $this->all_headings["member_params"];
$filter_heading = $this->all_headings["filters"];
ClubregHelper::generate_menu_tabs($member_params,$page_title );
$templateDetails = $this->templates->templateDetails;

$comm_groups = array();
$templateDetails->comm_groups = trim($templateDetails->comm_groups);
if(isset($templateDetails->comm_groups) && strlen($templateDetails->comm_groups) >0){
	$comm_groups = explode(",",$templateDetails->comm_groups);
}

ob_start();
?>
	var recipient_count = <?php echo intval(count($comm_groups)); ?>;
	
	function simpleValidate(){
	
		$$('.validate').addEvent('click',function(){
		
			var action = $(this).get('rel');
			
			f = this.form;
					
			if (document.formvalidator.isValid(f) && recipient_count > 0) {
				 	f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
				 	
				 	
				 	if(action == "previewcomms"){
						document.adminForm.task.value='previewcomms';
						document.adminForm.target='_blank'
					}else{
						document.adminForm.task.value= action;
						document.adminForm.target='';
					}				 	
				 	
     	 			return true;				 
			 }else{			
				var msg = 'Some values are not acceptable.  Please retry.';	
				if($('comm_subject').hasClass('invalid')){msg += '\n\t* Invalid Subject';}
				if(recipient_count == 0){msg += '\n\t* At least one recipient group required.';}
		
				alert(msg);
				return false;
			}			
		
		});			
	};
	
	
	
	Window.onDomReady(simpleValidate);
<?php 
		$t_script = ob_get_contents();
		ob_end_clean();
	 	$document->addScriptDeclaration($t_script);

 ob_start();
 
$comm_groups_str = array();
 ?>

<div class="toggler" id="toggler_div" style="background-color:#95C5DE;padding:5px;">
<div style="font-weight:bold;font-size:1.3em;">Recipients</div>
	<div class="recipients" >
		<?php 
		if(count($this->all_headings["templategroups"])> 0){
			echo "<ol>";
			foreach($this->all_headings["templategroups"] as $a_group){
				if(in_array($a_group->value, $comm_groups)){
					$comm_groups_str[] = sprintf("<span class='recipients_span' id='recipients_span_c%s'>%s</span>",$a_group->value,$a_group->text);
				}
				echo "<li>"	?><input type="checkbox" name="comm_groups[]" value="<?php echo $a_group->value?>" <?php echo in_array($a_group->value,$comm_groups)?"checked":""; ?> class='recipients_check'/><?php echo sprintf("<span id=\"recipients_span_%d\">",$a_group->value);echo $a_group->text; echo "</span></li>";
			}
			echo "</ol>";		
		}
		
		?>
	</div>
</div>
<?php 
		$recp_div = ob_get_contents();
		ob_end_clean();
		
		ob_start(); ?>

		<div class="center">
			<input type="submit" class="button validate" value="Save For Later"  rel='savecomms' />
			<input type="submit" class="button validate" value="Send Now" rel='sendcomms' />
			<input type="submit" class="button validate" value="Preview"  rel='previewcomms' />		
		</div>
		
<?php 
		$button_div = ob_get_contents();
		ob_end_clean();
	
?>
<form action="index.php" method="post" name="adminForm"   id="adminForm"  class="form-validate">
<div class="top_buttons">
<div class="left_buttons"><input class="button" name='back_' id="back_url" type="button" onclick="document.location='<?php echo $this->back_url; ?>'"  value='<?php echo JText::_('Back To Messages'); ?>' />&nbsp;&nbsp;</div>
<?php echo str_replace('class="center"', 'class="left_buttons"', $button_div)?>
<div class="right_buttons">&nbsp;</div>
</div><p class="cl"></p>
<div class="h3">Message Details</div>	
<div class="fieldset">
<table border=0 width="90%" >
	<tr>
		<td valign="top">		
			<label class="lbcls" for="comm_groups"><input type="button" style="padding:0px 2px 0px 2px;;background:#EFEFEF;font-size:0.9em" value="To" id="toButton"> <span class="isReq">*</span></label><?php echo $colon ;?>
		</td>
		<td id="to_content"><?php echo implode("",$comm_groups_str); ?></td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo $recp_div; ?></td>
	</tr>
	<tr>
		<td valign="top">		
			<label class="lbcls" for="comm_subject">Subject <span class="isReq">*</span></label><?php echo $colon ;?>
		</td>
		<td>
			<input type="text" value="<?php echo isset($templateDetails->template_subject)?stripslashes($templateDetails->template_subject):""; ?>" id="comm_subject" name="comm_subject" class="intext required" style="width:590px;"/></td>
	</tr>
	<tr>
		<td valign="top">		
			<label class="lbcls" for="comm_message">Message <span class="isReq">*</span></label><?php echo $colon ;?>
			</td>
			<td>
		<?php
		       $editor =& JFactory::getEditor(); $templateDetails->template_text = isset($templateDetails->template_text)?$templateDetails->template_text:"";
		       echo $editor->display('comm_message', $templateDetails->template_text, '600', '600', '80', '40', false);
		?>		
		</td>
	</tr>	
	<tr>
		<td colspan=2>
			<?php echo $button_div; ?>
		</td>
	</tr>
</table>
</div>
	<input type="<?= $in_type ?>" name="comm_id" id="comm_id" value="<?php echo isset($templateDetails->comm_id)?$templateDetails->comm_id:0 ?>" />	
	<input type="<?= $in_type ?>" name="tmp_id" value="<?php echo isset($templateDetails->template_id)?$templateDetails->template_id:0; ?>" />		
	<input type="<?= $in_type ?>" name="option" id="option" value="<?php echo $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" id="Itemid" value="<?php echo $Itemid ?>" />
	<input type="<?= $in_type ?>" name="task" value="savecomms" />	
	<input type="<?= $in_type ?>" name="c" value="comms" />	
	<input type="<?= $in_type ?>" name="check" id="check" value="" />	
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
