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
		
class ClubMenuHelper{	


static function generate_menu_tabs($member_params,$page_title){
	global $option,$Itemid;
	$c= trim(JRequest::getVar('c','', 'request', 'string'));
	?>
		<div style="font-weight:bold;font-size:1.5em;padding-left:4px;float:left;"><?php echo $page_title; ?></div><p class="cl"></p>
		<div  id="userNav_clubreg">		
			<ol>
			<?php 
				 if( $member_params->get( 'vieweoi' ) == "yes" ){ ?>
				 <li >
				 	<a href="index.php?option=<?php echo $option; ?>&view=eoi&layout=loadeoi&c=eoi&task=loadeoi&Itemid=<?php echo $Itemid; ?>" <?php echo  ($c=="eoi")?"class=\"acts\"":""; ?>><span>Manage Expression of Interest</span></a>
				</li>	
				<?php } 
					if( $member_params->get( 'manageusers' ) == "yes" ){ ?>					
					 <li>	
					 	<a href="index.php?option=<?php echo $option; ?>&c=userreg&task=loadregistered&Itemid=<?php echo $Itemid; ?>&limit=20" <?php echo  ($c=="userreg")?"class=\"acts\"":""; ?>><span>Registered <?php echo PLAYERS?></span></a>
					 </li>
					<?php } 
					if( $member_params->get( 'manageusers' ) == "yes" ){ ?>
					 <li >
					 	<a href="index.php?option=<?php echo $option; ?>&c=stats&task=loadstats&Itemid=<?php echo $Itemid; ?>" <?php echo  ($c=="stats")?"class=\"acts\"":""; ?>><span>Manage <?php echo STATS; ?></span></a>
					</li>	
					<?php } 
					if( $member_params->get( 'sendcommunication' ) == "yes" ){ ?>
					 <li class="last">	
					 	<a href="index.php?option=<?php echo $option; ?>&c=comms&task=listcomms&Itemid=<?php echo $Itemid; ?>" <?php echo  ($c=="comms")?"class=\"acts\"":""; ?>><span>Send Communications</span></a>
					 </li>
					<?php }  ?>			
			
			</ol>	
			</div>			
				
			<?php 		
		}
}
?>