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

global $colon,$Itemid,$option;
$document =& JFactory::getDocument();

$in_type = "hidden";
$page_title = "Manage Stats";
$document->setTitle($page_title );

$member_params = $this->all_headings["member_params"];
//$filter_heading = $this->all_headings["filters"];
ClubregHelper::generate_menu_tabs($member_params,$page_title );
//$template_list_text = $this->templates->template_list_text;

?>