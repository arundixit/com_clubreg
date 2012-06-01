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

class ClubRegViewmembers extends JView
{
	function display($tpl = null)
	{		

		global $mainframe;
		
		$user		= &JFactory::getUser();
		$pathway	= &$mainframe->getPathway();
		$document	= & JFactory::getDocument();			
		
		// Get the parameters of the active menu item
		$menus	= &JSite::getMenu();
		$menu    = $menus->getActive(); // current menu
		
		$pparams = &$mainframe->getParams('com_clubreg');		
		
		
		$menu_params = new JParameter( $menu->params );

		$can_update =  $menu_params->get('update_details');
		$member_id = JRequest::getVar('id',0 , 'request', 'int');
		
		
		$member	=  $this->getModel('member');		  
		$member->getData($member_id);	
		$lists["member"]  = $member;			
		
		
		
		$this->assignRef('lists',		$lists);
		
		// Set the document page title
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		
		if (is_object( $menu ) && 
			isset($menu->query['view']) && 
			$menu->query['view'] == 'members' && 
			isset($menu->query['id']) && 
			$menu->query['id'] == $member->user_data->id) {
		
			if (!$menu_params->get( 'page_title')) {
				$pparams->set('page_title',	$member->user_data->name);
			}
			
		} else {			
			$pparams->set('page_title',	$member->user_data->name);
		}
		$document->setTitle( $pparams->get( 'page_title' ) );
		
		//set breadcrumbs
		if (isset( $menu ) && isset($menu->query['view']) && $menu->query['view'] != 'member'){
			$pathway->addItem($member->user_data->name, '');
		}	
		
		$this->assignRef('params', $pparams);	// parameters for component
		$this->assignRef('member_params', new JParameter( $member->user_data->params ));

		
		if($can_update == 1 && $member_id == $user->id ){
			if($user->id == 0 || is_null($member->user_data)){
				$this->r_access();
			}else			
				$this->renderMyDetails($tpl);			
		}else{		
			parent::display($tpl);	
		}			
	}
	function renderMyDetails($tpl){
		
		$db		=& JFactory::getDBO();
		
		$d_qry = sprintf("select config_short,config_name,params from %s 
					where which_config = '%s' and publish = 1 order by ordering",
					CLUB_TEMPLATE_CONFIG_TABLE,	CLUB_MEMBER_WHICH);
					$db->setQuery($d_qry);
					$tmp_hd = $db->loadObjectList('config_short');
						
		
					
		$this->assignRef('headings', $tmp_hd);
		
		$howmany_guardian = $howmany_senior = 0;
		
		if($this->member_params->get( 'vieweoi' ) == "yes"){
			$where_[] = sprintf(" member_status = 'eoi' ");
			$where_[] = sprintf(" playertype in ('senior') ");
			$where_str = "where ".implode(" and ", $where_);
			$d_qry = sprintf("select count(member_id) as howmany from %s %s ", CLUB_EOIMEMBERS_TABLE, $where_str);
			
			$db->setQuery($d_qry);
			$howmany_senior = $db->loadResult();
			
			$where_ = array();
			
			$where_[] = sprintf(" member_status = 'eoi' ");
			$where_[] = sprintf(" playertype in ('guardian') ");
			
			$where_str = "where ".implode(" and ", $where_);
			$d_qry = sprintf("select count(member_id) as howmany from %s %s ", CLUB_EOIMEMBERS_TABLE, $where_str);				
			$db->setQuery($d_qry);			
			$howmany_guardian = $db->loadResult();			
		}
		$this->assign("howmany_senior", $howmany_senior);
		$this->assign("howmany_guardian", $howmany_guardian);
		
		$tpl = "renderMyDetails" ;		
		parent::display($tpl);
	}
	function r_access(){
		?>
		<div class="componentheading">
		Restricted Access
		</div>
		<?php 
	}
}