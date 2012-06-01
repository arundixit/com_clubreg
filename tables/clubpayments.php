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
class TableClubPayments extends JTable
{
	
	var $payment_id = null;
	var $member_id = null;
	
	var $payment_method = null;
	var $payment_transact_no = null;
	var $payment_status = null;
	var $payment_date = null;	
	var $payment_desc = null;
	
	var $payment_notes = null;
	var $payment_amount = null;
	
	var $created = null;
	var $created_by = null;	
	
	function __construct( &$db )
	{
		parent::__construct( CLUB_PAYMENTS_TABLE, 'payment_id', $db );
	}
}