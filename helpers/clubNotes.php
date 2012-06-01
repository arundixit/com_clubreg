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
class ClubNotesHelper{


	static function getNoteList($player_data){
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$where_[] = sprintf(" member_id = %d",$player_data->member_id) ;
		$where_[] = sprintf(" ( note_status in (0) or a.created_by = %d  )",$user->id) ;
		$where_[] = sprintf("  note_status != 99 ") ;
		
		$where_str = implode(" and ", $where_);
		
		$all_notes = array();
		

		$d_qry = sprintf("select a.*,
			date_format(a.created, '%%d/%%m/%%Y %%H:%%i:%%s') as created, b.name from %s as a left join #__users as b on 
			a.created_by = b.id
			 where %s  order by a.created asc ",CLUB_NOTES_TABLE,$where_str);
		$db->setQuery($d_qry);
		$all_notes = $db->loadObjectList();
		return $all_notes;

	}


	static function renderNoteList($all_notes,&$player_data){
		global $option,$Itemid;	?>
				<table class="smaller_table" width="100%" border=0 cellspacing=2  cellpadding=2>
				<tr>
					<th width=150>Date</th>
					<th>Notes</th>						
					<th width="10%">Added By</th>
				</tr>
				<tr>
				<?php $k= $i = 1;			
					
					 $cl_ = array("row0","row1");	
					 
					 /*,'<?php echo JUtility::getToken()?>'*/
					 
					 $lock_img['0'] = $lock_img['99'] = "";
					 $lock_img['1'] = JHTML::_('image', 'components/com_clubreg/assets/images/private.png', JText::_( 'Private' ), array('align' => 'right', 'align'=>'top'));
		
					$note_url = sprintf("index2.php?option=%s&c=userreg&task=editnote&Itemid=%s&member_id=%s&no_html=0&path=&%s=1&note_id=",$option,$Itemid,$player_data->member_id,JUtility::getToken());
					//$delete_url = sprintf("index2.php?option=%s&c=userreg&task=deletenote&Itemid=%s&member_id=%s&no_html=0&path=&%s=1&note_id=",$option,$Itemid,$player_data->member_id,JUtility::getToken());
				if(count($all_notes) > 0 ){ 	
					foreach($all_notes as $a_note){ ?>
				<tr class="<?php echo $cl_[$k];?>" id="note_<?php echo $a_note->note_id; ?>">
					<td style="white-space:nowrap"><?php echo $a_note->created; ?> <?php echo $lock_img[$a_note->note_status] ?></td>
					<td style="padding-left:5px;">
						<a href="javascript:void(0);" onclick="process_delete(<?php echo $a_note->note_id; ?>,'<?php echo JUtility::getToken()?>')">
						<?php echo JHTML::_('image', 'components/com_clubreg/assets/images/delete.png', JText::_( 'Delete' ), array('align' => 'right')); ?>
						</a>
						<a href="<?php echo $note_url.$a_note->note_id;?>" class="modal-button" rel="{handler: 'iframe', size: {x: 400, y: 250}}">										
							<?php //echo JHTML::_('image', 'components/com_clubreg/assets/images/edit.png', JText::_( 'Edit' ), array('align' => 'right','hspace'=>"5")); ?>						
							<?php echo nl2br(stripslashes($a_note->notes)); ?>
							</a>
					</td>					
					<td style="white-space:nowrap"><?php echo $a_note->name; ?></td>
				
				</tr>
				
				<?php $i++; $k= 1- $k; } // end for each
				
				}// end if count
				?>
				
				</table>
				<br />
				<?php 
			}
	
}
?>