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


// Require the base controller

require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.DS.'constants.php');

require_once (JPATH_COMPONENT.DS.'controller.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubreg.php');

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
global $colon,$append;
$append ="";
$colon = "<span style='vertical-align:top'><b>:</b>&nbsp;&nbsp;&nbsp;</span>";

jimport('joomla.environment.browser');
$instance	=& JBrowser::getInstance();
if($instance->_browser == "msie"){
	JHTML::_('stylesheet', 'data_table.ie.css', $append .'components/com_clubreg/assets/');	
}else{
	JHTML::_('stylesheet', 'data_table.css', $append .'components/com_clubreg/assets/');
}
JHTML::_('stylesheet', 'tabs.css', $append .'components/com_clubreg/assets/');
JHTML::_('stylesheet', 'menu.css', $append .'components/com_clubreg/assets/');
JHTML::_('stylesheet', 'small_table.css', $append .'components/com_clubreg/assets/');
JHTML::_('stylesheet', 'common.css', $append .'components/com_clubreg/assets/');

jimport( 'joomla.cache.cache' );

$is_default = false;
// Require specific controller if requested
if($controller = JRequest::getWord('c')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
		$is_default = true;
	}
	
}else{
	$is_default = true;
}

// Create the controller
$classname	= 'ClubRegController'.ucfirst($controller);
$controller = new $classname( );

// Register Extra tasks
if($is_default){
	

}



// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

function write_debug($d_data){
	echo "<pre>",var_dump($d_data),"</pre>";	
}

function get_a_list($d_qry,$t_key=null){	
	
	if($t_key)
		$uq_id = sprintf("%s%s",$d_qry,$t_key);
	else
		$uq_id = sprintf("%s",$d_qry);
		
	$uq_id = md5($uq_id);
	$group_id = "all_configs";
	
	$function = ("simple_cached_function");
	
 	$cache =& JFactory::getCache( $group_id );
    $cache->setCaching( 1 ); // enable caching (1 = enabled, 0 = disabled)
     
    $result = $cache->call( $function, $d_qry,$t_key);
    
    if ( empty( $result ) ) {
    	// find the id to remove data for
        $id = $cache->_makeId( $function, $d_qry,$t_key);

        // invalidate this entry in the cache
        $cache->remove( $id, $group_id );

       // call our function again knowing the cache has been fixed
       $result = $cache->call( $function, $d_qry,$t_key);  
    }
	return $result;
}
function simple_cached_function($d_qry,$t_key=null){	
	$db 	=& JFactory::getDBO();
	$db->setQuery( $d_qry );		
	$t_array = $db->loadObjectList($t_key);		
	return $t_array;	
}
function stop($msg = '')
{
    global $mainframe;
    echo $msg;
    $mainframe->close();
  /*
Alternative:
echo $msg;
jexit();
*/
}

function tryUseCookies($t_title,$tab_offset,$tab_id){
	return sprintf("<span onclick=\"tryUseCookies(%d,%d)\">%s</span>",$tab_offset,$tab_id ,$t_title);
}
