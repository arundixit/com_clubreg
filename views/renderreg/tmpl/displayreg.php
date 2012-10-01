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
ClubMenuHelper::generate_menu_tabs($member_params,$page_title );