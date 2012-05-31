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
class TableClubMembers extends JTable
{
	
	var $member_id = null;
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
	
	function __construct( &$db )
	{
		parent::__construct( CLUB_EOIMEMBERS_TABLE, 'member_id', $db );
	}
	/*
		ALTER TABLE `jos_clubreg_eoimembers` ADD `member_id` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `surname` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `givenname` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `mobile` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `address` VARCHAR( 50 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `suburb` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `postcode` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `phoneno` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `emailaddress` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `send_news` TINYINT  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `dob` DATE  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `group` INT(11)  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `as_above` TINYINT  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `gender` TINYINT  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `created` DATETIME  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `created_by` INT(11)  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `approved` DATETIME  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `approved_by` INT(11)  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `year_registered` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `member_status` VARCHAR( 30 )  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `parent_id` INT(11)  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD `playertype` VARCHAR(11)  NULL ;
		ALTER TABLE `jos_clubreg_eoimembers` ADD INDEX ( `parent_id` ) ;
	 */

}