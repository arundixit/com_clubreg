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
class ClubHtmlHelper{
	
	static function renderIcon($icon_){
		
		echo JHTML::_('image', "components/com_clubreg/assets/images/".$icon_['img'], JText::_( $icon_["text"] ), array('align' => 'right', 'align'=>'bottom','width'=>'24','hspace'=>'2','style'=>'vertical-align:middle'));
		
	}
	
}
	