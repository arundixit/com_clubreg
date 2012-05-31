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
class TableClubNotes extends JTable
{
	
	var $note_id = null;
	var $member_id = null;
	
	var $note_status = null;	
	var $note_type = null;
	var $notes = null;	
	var $created = null;
	var $created_by = null;	
	
	function __construct( &$db )
	{
		parent::__construct( CLUB_NOTES_TABLE, 'note_id', $db );
	}
}