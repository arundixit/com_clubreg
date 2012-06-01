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
class TableClubRegmembers extends JTable
{
	
	var $member_id = null;
	
	var $memberid = null;
	var $memberlevel = null;
	var $surname = null;
	var $givenname = null;
	var $mobile = null;
	var $address = null;
	var $suburb = null;
	var $postcode = null;
	var $phoneno = null;
	var $emailaddress = null;
	var $send_news = null;
	var $dob = null;
	var $group = null;
	var $subgroup = null;
	
	var $as_above = null;
	var $gender = null;
	var $created = null;
	var $created_by = null;
	var $approved = null;
	var $approved_by = null;
	var $year_registered = null;
	var $member_status = null;
	var $parent_id = null;	
	
	var $playertype = null;
	var $eoi_id = null;
	
	function __construct( &$db )
	{
		parent::__construct( CLUB_REGISTEREDMEMBERS_TABLE, 'member_id', $db );
	}
	/*
	 * 	ALTER TABLE `jos_clubreg_registeredmembers` ADD `memberid` VARCHAR( 50 ) NULL DEFAULT ' ' COMMENT 'Member Club Id Number' AFTER `member_id` 
		ALTER TABLE `jos_clubreg_registeredmembers` ADD `memberlevel` VARCHAR( 50 ) NULL DEFAULT ' ' COMMENT 'Member Club level' AFTER `memberid`
* */
	

}