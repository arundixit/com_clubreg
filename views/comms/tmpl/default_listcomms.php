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
$template_list_text = $this->templates->template_list_text;

$headings = $this->all_headings["headings"];

?>
<div  id="subNav">
<ol>
	<li ><a href="">Messages Sent</a></li>
	<li class="last"><a href="javascript:void(0)" id="newMessages">Send New Messages</a><br />
	<?php echo $template_list_text; ?>
	</li>	
	
</ol>
</div>
<?php 

write_debug($headings);
?>