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

jimport( 'joomla.application.component.view');


class ClubRegViewajax extends JView
{
	function display($tpl = null){
		
		
		$layout	= $this->getLayout();
		
		if( $layout == 'ajax') {
			$this->_editnote($tpl);
			return;
		}
		if( $layout == 'editpayment') {
			$this->_editpayment($tpl);
			return;
		}		
		
		$tpl = "note";
		parent::display($tpl);
	}
	
	function _editnote($tpl){
		
		$tpl = "note";
		
		write_debug($tpl);
		
		parent::display($tpl);
		
	}
	
	function _editpayment($tpl){
		
		$tpl = "payment";
		
		parent::display($tpl);
	
	}
}