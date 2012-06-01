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

$member_params = $this->all_headings["member_params"];
$filter_heading = $this->all_headings["filters"];
ClubregHelper::generate_menu_tabs($member_params,$page_title );
$templateDetails = $this->templates->templateDetails;


ob_start();
?>
	var recipient_count = 0;
	
	function simpleValidate(action){
			
			if(action == "previewcomms"){
				document.adminForm.task.value='previewcomms';
				document.adminForm.target='_blank'
			}else{
				document.adminForm.task.value= action;
				document.adminForm.target='';
				
				if(recipient_count == 0){
					alert("No Recipients Selected");
				}
			}
			
			
	};
	
	
	
	//Window.onDomReady(simpleValidate);
<?php 
		$t_script = ob_get_contents();
		ob_end_clean();
	 	$document->addScriptDeclaration($t_script);

 ob_start(); ?>

<div class="toggler" id="toggler_div" style="background-color:#95C5DE;padding:5px;">
<div style="font-weight:bold;font-size:1.3em;">Recipients</div>
	<div class="recipients" >
		<?php 
		if(count($this->all_headings["templategroups"])> 0){
			echo "<ol>";
			foreach($this->all_headings["templategroups"] as $a_group){
				echo "<li>"	?><input type="checkbox" name="comm_groups[]" value="<?php echo $a_group->value?>" class='recipients_check'/><?php echo sprintf("<span id=\"recipients_span_%d\">",$a_group->value);echo $a_group->text; echo "</span></li>";
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
			<input type="submit" class="button" value="Save For Later" onclick="simpleValidate('savecomms')"/>
			<input type="submit" class="button" value="Send Now" onclick="simpleValidate('sendcomms')"/>
			<input type="submit"  value="Preview" class="button" onclick="simpleValidate('previewcomms')"/>		
		</div>
		
<?php 
		$button_div = ob_get_contents();
		ob_end_clean();
	
?>
<form action="index.php" method="post" name="adminForm"   id="adminForm"  class="form-validate">
<div class="top_buttons">
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
		<td id="to_content">&nbsp;</td>
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
			<input type="text" value="<?php echo stripslashes($templateDetails->template_subject); ?>" id="comm_subject" name="comm_subject" style="width:590px;"/></td>
	</tr>
	<tr>
		<td valign="top">		
			<label class="lbcls" for="comm_message">Message <span class="isReq">*</span></label><?php echo $colon ;?>
			</td>
			<td>
		<?php
		       $editor =& JFactory::getEditor();
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
	<input type="<?= $in_type ?>" name="comm_id" id="comm_id" value="<?php echo $templateDetails->comm_id ?>" />	
	<input type="<?= $in_type ?>" name="tmp_id" value="<?php echo $templateDetails->template_id; ?>" />		
	<input type="<?= $in_type ?>" name="option" id="option" value="<?php echo $option ?>" />	
	<input type="<?= $in_type ?>" name="Itemid" id="Itemid" value="<?php echo $Itemid ?>" />
	<input type="<?= $in_type ?>" name="task" value="savecomms" />	
	<input type="<?= $in_type ?>" name="c" value="comms" />	
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
