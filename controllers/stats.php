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

jimport('joomla.application.component.controller');

/**
 * Static class to hold controller functions for the component
 *
 * @static
 * @package		Joomla
 * @subpackage	Poll
 * @since		1.5
 */
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubComms.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubTables.php');

class ClubRegControllerStats extends JController
{
	
	function __construct()
	{
		global $mainframe;
		parent::__construct();	
	}
	
	
	function display(){	

		JRequest::setVar('view','stats');		
		parent::display($tpl);		
	}

}