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
 *  Simpleconfig Component Clubgroups Model
 *
 * @package		Joomla
 * @subpackage	ClubReg
 * @since 1.5
 */
class TableClubComms extends JTable
{
	
	var $comm_id = null;
	var $template_id = null;
	
	var $comm_groups = null;	
	var $comm_subject = null;
	var $comm_message = null;	
	var $created = null;
	var $created_by = null;	
	
	var $comm_status = null;
	
	var $sent_date = null;
	
	function __construct( &$db )
	{
		parent::__construct( CLUB_SAVEDCOMMS_TABLE, 'comm_id', $db );
	}
}