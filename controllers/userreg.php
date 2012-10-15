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
 * Static class to hold controller functions for the Poll component
 *
 * @static
 * @package		Joomla
 * @subpackage	Poll
 * @since		1.5
 */
require_once (JPATH_COMPONENT.DS.'assets'.DS.'recaptcha'.DS.'recaptchalib.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubNotes.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubTables.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubHidden.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubTags.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'clubStats.php');
class ClubRegControllerUserReg extends JController
{
	
	function __construct()
	{
		global $mainframe;
		parent::__construct();
		
		// Register Extra tasks
		$this->registerTask("editreg","editreg");
		$this->registerTask("renderreg","renderreg");
		$this->registerTask("deletereg","deletereg");
		$this->registerTask("save_details","save_details");
		$this->registerTask("jparent","jparent");
		$this->registerTask("subgroup","subgroup");
		$this->registerTask("editpayment","editpayment");
		$this->registerTask("savepayments","savepayments");
		
		$this->registerTask("editnote","editnote");
		$this->registerTask("savenotes","savenotes");
		$this->registerTask("deletenote","deletenote");
		
		$this->registerTask("batchupdate","batchupdate");
		
		$this->registerTask("addtag","addtag");
		$this->registerTask("deletetag","deletetag");
		$this->registerTask("gettags","gettags");
		
		$this->registerTask("save_extradetails", "save_extradetails");
		$this->registerTask("usersearch", "usersearch");
		
		
		
	}
	
	
	function display($tpl = null){		
		
		JRequest::setVar('view','userreg');
		switch($t){
			
		}
		parent::display($tpl);	
		
	}
	function editreg(){
		
		JRequest::setVar('layout', 'editreg');
		JRequest::setVar('view','userreg');
		parent::display();
	}
	
	function renderreg(){	
		JRequest::setVar('layout', 'displayreg');
		JRequest::setVar('view','renderreg');
		parent::display();
	}
	
	
	function save_details(){	
		
		global $mainframe;
		
		$user		= &JFactory::getUser();
		$app = JFactory::getApplication();
		
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$save_junior = $update_em_contact = false;
		$post = JRequest::get('post');
		
		$player_type = trim(JRequest::getVar( "g_playertype", '', 'post', 'string' ));
		$member_id = intval(JRequest::getVar( "member_id", 0, 'post', 'int' ));
		$row	=& JTable::getInstance('clubregmembers', 'Table');
		
		$row_old	=& JTable::getInstance('clubregmembers', 'Table');
		if(intval($member_id)>0){ $row_old->load($member_id); }else{
			$row->member_status = 'registered';
			$row->created = date('Y-m-d H:i:s');
			$row->created_by = $user->id;
			JRequest::setVar('playertype', $player_type);
		}
		
		switch($player_type){
			case "junior":
				$contact_array = array('memberid','memberlevel','surname','givenname','dob','group','gender','playertype','parent_id','year_registered','subgroup');				
				$must_supply = array('surname','givenname');
				
				$row->member_id = $member_id;
				$atleast = array();
				
				foreach($contact_array as $a_key){
					$t_key = "g_".$a_key;
					

					if($a_key == "dob"){
						// try reformating the date
						$t_explode = explode('/',JRequest::getVar( $t_key, null, 'post', 'string' ));
						if(count($t_explode) == 3 ){$row->$a_key = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );}
					}else{						
						$row->$a_key = trim(JRequest::getVar( $t_key, '-1', 'post', 'string' ));
						if(in_array($a_key, $must_supply)){
							if(strlen($row->$a_key) > 2){
								$atleast[] = true;
							}
						}
					}					
				}	
				if(count($atleast) == 2){	
					if($member_id > 0){
						$other_details["primary_id"] = $member_id;
						$other_details["short_desc"] = "updated ".$player_type;
						ClubregHelper::save_old_data($row_old,$other_details);
					}		
					$row->store();
					JRequest::setVar('member_id', $row->member_id);
					$app->enqueueMessage("Details Updated for ".PLAYER);
					$update_em_contact = true;
				}else{
					JError::raiseWarning( 500, "Incomplete ".PLAYER." Details :: Player Names Must be more than 2 characters" );
				}			
				
			break;
			case "senior":
				
				jimport('joomla.mail.helper');
				
				$contact_array = array('memberid','memberlevel','surname','givenname','group',
				'emailaddress','phoneno','mobile',
				'address','suburb','postcode','gender','send_news','playertype','year_registered','subgroup');
				
				$must_supply = array('surname','givenname');
				$is_email = array('emailaddress');
				
				$row->member_id = $member_id;
				$msg = array();			
				
				foreach($contact_array as $a_key){
					$t_key = "g_".$a_key;
						
					$row->$a_key = trim(JRequest::getVar( $t_key, '-1', 'post', 'string' ));
					if(in_array($a_key, $is_email)){
						if(JMailHelper::isEmailAddress($row->$a_key) === FALSE ){
							$row->$a_key = NULL;
							$msg[] = "Email Address Invalid";
						}else{
							$atleast[] = true;
						}
					}else{
						
						if(in_array($a_key, $must_supply)){
							if(strlen($row->$a_key) > 2){
								$atleast[] = true;
							}else{
								$msg[] = sprintf("%s Must be more than 2 characters",ucfirst($a_key));
							}
						}
					}
				}
				
				if(count($atleast) == 3){
					if($member_id > 0){
						$other_details["primary_id"] = $member_id;
						$other_details["short_desc"] = "updated ".$player_type;						
						ClubregHelper::save_old_data($row_old,$other_details);
					}
					
					$row->store();
					JRequest::setVar('member_id', $row->member_id);	
					$update_em_contact = true;
					
					$app->enqueueMessage("Details Updated for ".PLAYER);
				}else{
					JError::raiseWarning( 500, sprintf("Incomplete ".PLAYER." Details :: %s",implode(", ",$msg)));
					
				}			
				
			break;
			case "guardian":
				jimport('joomla.mail.helper');
				
				$contact_array = array('surname','givenname',
								'emailaddress','phoneno','mobile',
								'address','suburb','postcode','send_news','playertype');
				
				$must_supply = array('surname','givenname');
				$is_email = array('emailaddress');
				
				$row->member_id = $member_id;
				$msg = array();
				
				foreach($contact_array as $a_key){
					$t_key = "g_".$a_key;
				
					$row->$a_key = trim(JRequest::getVar( $t_key, '-1', 'post', 'string' ));
					if(in_array($a_key, $is_email)){
						if(JMailHelper::isEmailAddress($row->$a_key) === FALSE ){
							$row->$a_key = NULL;
							$msg[] = "Email Address Invalid";
						}else{
							$atleast[] = true;
						}
					}else{
				
						if(in_array($a_key, $must_supply)){
							if(strlen($row->$a_key) > 2){
								$atleast[] = true;
							}else{
								$msg[] = sprintf("%s Must be more than 2 characters",ucfirst($a_key));
							}
						}
					}
				}
				
				if(count($atleast) == 3){
					if($member_id > 0){
						$other_details["primary_id"] = $member_id;
						$other_details["short_desc"] = "updated ".$player_type;
						ClubregHelper::save_old_data($row_old,$other_details);
					}
						
					$row->store();
					JRequest::setVar('member_id', $row->member_id);
					$parent_id =  $row->member_id;
					$app->enqueueMessage("Details Updated for ".PLAYER);
				}else{
					JError::raiseWarning( 500, sprintf("Incomplete ".PLAYER." Details :: %s",implode(", ",$msg)));
						
				}
				
				$junior_ids = JRequest::getVar( "junior_id", array(), 'post', 'array' );
				$junior_contact_array = array('memberid','memberlevel','surname','givenname','dob','group','gender','year_registered','subgroup');
				$prefix_key = "player_";
				
				/**
				 * 	Save details of Existing Junior Member
				 */				
				foreach($junior_ids as $a_junior_id){
					$t_index = $prefix_key.$a_junior_id; $player_contact = array();
					
					$player_row_old	=& JTable::getInstance('clubregmembers', 'Table');
					$player_row_old->load($a_junior_id);
					
					foreach($junior_contact_array as $a_key){		
						$t_explode = array();
						$t_key = sprintf('r_%s',$a_key);							
						$tmp_post = JRequest::getVar( $t_key, array(), 'post', 'array' ); // get the whole array from the post
						$player_contact[$a_key] = JArrayHelper::getValue( $tmp_post, $t_index, '-1', 'string' ); // get the value from current index from the returned array
						
						if($a_key == "dob"){
							// try reformating the date
							$t_explode = explode('/',$player_contact[$a_key]);
							$player_contact[$a_key] = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
						}						
						
						$tmp_post = array();				
						
					}					
					
					$player_row	=& JTable::getInstance('clubregmembers', 'Table');
					$player_row->bind( $player_contact );
					$player_row->member_id = $a_junior_id;					
					$player_row->playertype = "junior";		

					$other_details["primary_id"] = $a_junior_id;
					$other_details["short_desc"] = "updated g_junior";
					ClubregHelper::save_old_data($player_row_old,$other_details);
					
					$player_row->store();		
					
				}
				/**
				 * 
				 * register new junior member
				 * @var unknown_type
				 */
				$must_supply = array('surname','givenname');
				$div_counter = JRequest::getVar( 'div_counter', 0, 'post', 'int' );
				for($i = 0; $i < $div_counter ; $i++){
					$t_index = $prefix_key.$i;
					$at_least = array(); $player_contact = array();
					foreach($junior_contact_array as $a_key){
						$t_key = sprintf('p_%s',$a_key);
				
						$tmp_post = JRequest::getVar( $t_key, array(), 'post', 'array' );
						$player_contact[$a_key] = JArrayHelper::getValue( $tmp_post, $t_index, '-1', 'string' );
				
						if(strlen($player_contact[$a_key]) > 0 && $player_contact[$a_key] != '-1' ){
							
							if($a_key == "dob"){
								// try reformating the date
								$t_explode = explode('/',$player_contact[$a_key]);
								$player_contact[$a_key] = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0] );
							}
							if(in_array($a_key, $must_supply)){
								$at_least[] = 1;
							}
						}
						$tmp_post = array();
					}						
						
					if(count($at_least) > 1){
						$player_row	=& JTable::getInstance('clubregmembers', 'Table');
						$player_row->bind( $player_contact );
				
						$player_row->parent_id = $parent_id;
						$player_row->created = date('Y-m-d H:i:s');
						$player_row->created_by = $user->id;
						$player_row->member_status = "registered";
						$player_row->playertype = "junior";					
						
						$player_row->store();
					}	
				}
			break;
			
		}
		
		
		if($update_em_contact){
			$db		=& JFactory::getDBO();
			
			unset($d_qry);
			$d_qry = array();
			$tmp_contact_array = ClubContactHelper::getContactArray();			
			$special_contacts = $tmp_contact_array["special"]; // get the special s
			$contact_keys = JRequest::getVar( "contact_key", array(), 'post', 'array' );
			foreach($contact_keys as $con_key){
				
				$contact_array = array_merge($tmp_contact_array["contact_items"],$special_contacts[$con_key]); // merge special based in contact key
				
				foreach($contact_array as $a_contact_item){
					$contact_item = $con_key.$a_contact_item;
					$contact_value = JRequest::getVar( $contact_item, '', 'post', 'string' );
					if($row->member_id > 0){
						$d_qry[] = sprintf("insert into %s set `member_id` = %d ,`contact_detail` = %s ,`contact_value` = %s on duplicate key update 
							contact_value = values(contact_value);	
						",CLUB_CONTACT_TABLE,$row->member_id,$db->Quote($contact_item),$db->Quote($contact_value));
					}
				}
			}
			
				if(count($d_qry) > 0){
					$q_string = implode("",$d_qry);
					$db->setQuery($q_string);
					if(!$db->queryBatch()){
						return JError::raiseError(500, $db->getErrorMsg() );
					}
				}		
			unset($d_qry);
		}
		
		$next_action = isset($_POST["saveNnew"])?JRequest::getVar( "saveNnew", null, 'post', 'string' ):null;
		
		if(isset($next_action)){
			JRequest::setVar('member_id', 0);
			JRequest::setVar('next_action', "saveNnew");
		}
		
		$this->editreg();
		return;
		
	}
	function jparent(){
		
		$jparent_id = intval(JRequest::getVar( "jparent_id", 0, 'get', 'int' ));
		$row_parent	= JTable::getInstance('clubregmembers', 'Table');
		$parent_data = new stdClass();
		if(intval($jparent_id)>0){
			$row_parent->load($jparent_id);
			foreach($row_parent as $tkey => $tvalue){
					if($tkey[0] == "_")
						continue;
				$parent_data->$tkey = $tvalue;		
		}
		}
		echo json_encode( $parent_data );	
	}
	function  subgroup(){
		
		$group_id = intval(JRequest::getVar( "group_id", 0, 'get', 'int' ));		
		$group_data 	=& JModel::getInstance('groups', 'ClubRegModel');
		$subgroups = $group_data->load_subgrougs($group_id); // get the subgroups of the current group		
		
		echo json_encode( $subgroups );
	}
	function deletereg(){
		
		
		
		$db		=& JFactory::getDBO();
		$user		= &JFactory::getUser();
		
		$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
		$member_data->getData($user->id); // get the member data for current user
		$member_params =  new JParameter( $member_data->user_data->params );
		
		$reg_members = JRequest::getVar( "reg_members", array(), 'post', 'array' );
		
		if($member_params->get('deletereg' ) == "yes"){
		
			if(count($reg_members) > 0 ){
				$member_ids = implode(",",$reg_members);
				$d_qry = sprintf("update %s set member_status = 'deleted' where member_id in (%s) or parent_id in (%s) ;",CLUB_REGISTEREDMEMBERS_TABLE,$member_ids,$member_ids);
				//$db->setQuery( $d_qry );
				//$db->query();
				JError::raiseWarning( 500, "Registered Members Deleted" );
			}else{
				JError::raiseWarning( 500, "Please Select at least one member" );
			}
		
		}else{
			JError::raiseWarning( 500, "You are not authorised to delete a Registered Member" );
		}
		
		/*
		
		$session =& JFactory::getSession();
		$d_url_ = $session->get("com_clubreg.back_url");
		
		$back_url = sprintf("index.php?option=%s&c=userreg&task=loadregistered&Itemid=%d&%s",$option,$Itemid,implode("&",$d_url_));
		
		echo $back_url;*/
		
		JRequest::setVar('view','userreg');		
		parent::display($tpl);
	}
	function editpayment(){
		
		
		global $option, $Itemid,$colon;
		
		JHTML::_('behavior.formvalidation');
		
		
		$document =& JFactory::getDocument();
		
		JRequest::checkToken("get") or jexit( 'Invalid Token' );
		
		$return_data['member_id'] = intval(JRequest::getVar('member_id','0', 'request', 'int'));
		$return_data['payment_id'] = intval(JRequest::getVar('payment_id','0', 'request', 'int'));
		
		$row	=& JTable::getInstance('clubregmembers', 'Table');
		if($return_data['member_id'] > 0){
			$row->load($return_data['member_id']);
		}else{
			JError::raiseWarning( 500, "Invalid Payment Process" );
			return;
		}
		$payment_row	=& JTable::getInstance('clubpayments', 'Table');
		if($return_data['payment_id']  > 0){
			$payment_row->load($return_data['payment_id']);
			
		}else{
			$payment_row->payment_season = date("Y");
			//JError::raiseWarning( 500, "Invalid Payment Process" );
			//return;
		}		
		
		$t_array = array();
		$t_array = ClubPaymentsHelper::getPaymentMethods();
		
		$t_prop=" style='width:170px;'";
		$name = "payment_method";
		$id= "payment_method";	
		
		JHTML::_('script', 'payments.js?'.time(), 'components/com_clubreg/assets/js/');	
		
		$year_registered_list = ClubregHelper::generate_seasonList();		
	?>
		<form action="index2.php" method="post"  style="text-align:left;" name="payment_admin" id="payment_admin" class="form-validate">			
			<div class="h3"><?php  ClubHtmlHelper::renderIcon(array('img'=>'payment.png','text'=>'Payments'));?>Payment Details :: <?php echo ucwords($row->surname." ".$row->givenname); ?></div>
			<div class="fieldset">
			<div class="n"><label class="lbcls" for="payment_season"><?php echo SEASON ?> <span class="isReq">*</span></label><?php echo $colon; 	echo JHTML::_('select.genericlist',  $year_registered_list, "payment_season", 'class="intext required" id="payment_season"  size="1" '.$t_prop, 'value', 'text', $payment_row->payment_season);?></div>			
			<div class="n"><label class="lbcls" for="payment_method">Payment Method <span class="isReq">*</span></label><?php echo $colon; 	echo JHTML::_('select.genericlist',  $t_array, $name, 'class="intext required" id="'.$id.'"  size="1" '.$t_prop, 'value', 'text', $payment_row->payment_method);?></div>
			<div class="n"><label class="lbcls" for="payment_transact_no" >Transaction # <span class="isReq">*</span></label><?php echo $colon; ?><input type="text" name="payment_transact_no" id="payment_transact_no" value="<?php echo $payment_row->payment_transact_no; ?>" class="intext required" <?php echo $t_prop; ?>/></div>
			<?php 		
			$t_array = array();
			$t_array = ClubPaymentsHelper::getPaymentStatus();
			$t_prop=" required validate-paymentselect";
			$name = "payment_status";
			$id= "payment_status";			
			?>			
			<div class="n"><label class="lbcls" for="payment_status">Status <span class="isReq">*</span></label><?php echo $colon; echo JHTML::_('select.genericlist',  $t_array, $name, 'class="intext required" id="'.$id.'"  size="1" '.$t_prop, 'value', 'text', $payment_row->payment_status); ?></div>
			<div class="n"><label class="lbcls" for="payment_date">Payment Date</label><?php echo $colon; ?>
			<?php 
				$format = '%d/%m/%Y';
				$name = "payment_date";
				$id= "payment_date";$value = "";
				$value_ = explode("-",$payment_row->payment_date);
				//
				if(count($value_) == 3 && !preg_match("/0000-00-00/",$payment_row->payment_date)){
					$value = sprintf("%s/%s/%s",$value_[2],$value_[1],$value_[0]);
				}else
					$value =date("d/m/Y");
				echo JHTML::_('calendar', $value, $name, $id, $format, array('class' => 'intext','style'=>'width:80px;','readonly'=>'readonly'));
						
			?>			
			</div>
			<?php 			
			$t_array = array();
			$t_array = ClubPaymentsHelper::getPaymentDescription();
			
			$t_prop=" required";
			$name = "payment_desc";
			$id= "payment_desc";		$p_amount = 0;	$in_type = "hidden";
			?>		
			<div class="n"><label class="lbcls" for="payment_desc">Description <span class="isReq">*</span></label><?php echo $colon; echo JHTML::_('select.genericlist',  $t_array, $name, 'class="intext" id="'.$id.'"  size="1" '.$t_prop, 'value', 'text', $payment_row->payment_desc); ?></div>
			<div class="n" style="vertical-align:top"><label class="lbcls" style="vertical-align:top" for="payment_notes">Notes</label><?php echo $colon; ?><textarea class="intext" rows=3 id="payment_notes" name="payment_notes" style="width:200px"><?php echo stripslashes($payment_row->payment_notes); ?></textarea></div>
			<div class="n"><label class="lbcls" for="payment_amount">Amount(<?php echo CURRENCY; ?>) <span class="isReq">*</span></label><?php echo $colon; ?><input type="text" name="payment_amount" value="<?php echo sprintf("%.2f",($payment_row->payment_amount* 0.01)); ?>" id="payment_amount" class="intext required validate-numeric" style="text-align:right;width:70px;"/></div>
			
			<div style="text-align:center;padding:3px;">
			<input class="button validate" name='normal_save' id="normal_save" type="submit" value='Save Details' />
			</div>			
			</div>			
				<input type="<?= $in_type ?>" name="payment_id" value="<?php echo $payment_row->payment_id; ?>" />	
				<input type="<?= $in_type ?>" name="member_id" value="<?php echo $row->member_id; ?>" />					
				<input type="<?= $in_type ?>" name="option" value="<?= $option ?>" />	
				<input type="<?= $in_type ?>" name="Itemid" value="<?= $Itemid ?>" />
				<input type="<?= $in_type ?>" name="task" value="savepayments" />	
				<input type="<?= $in_type ?>" name="c" value="userreg" />
				<input type="<?= $in_type; ?>" name="no_html" value="1" />		
				<?php echo JHTML::_( 'form.token' ); ?>
		</form>		
		<?php 			
	}
	function savepayments(){			
			
			$user		= &JFactory::getUser();		
			
			JRequest::checkToken() or jexit( 'Invalid Token' );
			
			$post = JRequest::get('post');
			
			$payment_row	=& JTable::getInstance('clubpayments', 'Table');
			$payment_row->bind( $post );
			
			$payment_row_old	=& JTable::getInstance('clubpayments', 'Table');
			
			if(intval($post["payment_id"])>0){
				$payment_row_old->load($post["payment_id"]);
				$other_details["primary_id"] = $payment_row->payment_id;
				$other_details["short_desc"] = "updated invoice";
				ClubregHelper::save_old_data($payment_row_old,$other_details);
			}
			
			if(isset($payment_row->payment_id) && intval($payment_row->payment_id) > 0){
				
			}else{
				$payment_row->created = date('Y-m-d H:i:s');
				$payment_row->created_by = $user->id;
			}
			
			$t_explode = explode('/',$payment_row->payment_date);
			$payment_row->payment_date = sprintf('%s-%s-%s',$t_explode[2],$t_explode[1],$t_explode[0]);
			
			$payment_row->payment_amount = intval(100 * $payment_row->payment_amount);
			$not_in = array();
			$msg = array();
			
			if(strval(trim($payment_row->payment_season)) == "0" ){
				$not_in[] = false;
			}		
			
			if(strval(trim($payment_row->payment_method)) == "0" ){
				$not_in[] = false;
			}
			
			if(strlen(trim($payment_row->payment_transact_no)) == 0 ){
				$not_in[] = false;
			}
			if(strval(trim($payment_row->payment_status)) == "0" ){
				$not_in[] = false;
			}
			if(strval(trim($payment_row->payment_desc)) == "0" ){
				$not_in[] = false;
			}
			
			if(intval($payment_row->payment_amount) == 0 ){
				$not_in[] = false;
			}			
			
			if(count($not_in) == 0){
				$payment_row->store();
			}else{
				JError::raiseWarning( 500, sprintf("Payment Details are not Complete",implode(", ",$msg)));
			}
			
			$row	=& JTable::getInstance('clubregmembers', 'Table');			
			$row->load($payment_row->member_id);
			
			$payment_list = ClubPaymentsHelper::getPaymentList($row);
			ClubPaymentsHelper::renderPaymentList($payment_list,$row);			
			
		
	}
	
	
	function editnote(){		
		
		global $option, $Itemid,$colon;
	
		JHTML::_('behavior.formvalidation');
	
		$document =& JFactory::getDocument();
	
		JRequest::checkToken("get") or jexit( 'Invalid Token' );
	
		$return_data['member_id'] = intval(JRequest::getVar('member_id','0', 'request', 'int'));
		$return_data['note_id'] = intval(JRequest::getVar('note_id','0', 'request', 'int'));
	
		$row	=& JTable::getInstance('clubregmembers', 'Table');
		if($return_data['member_id'] > 0){
			$row->load($return_data['member_id']);
		}else{
			JError::raiseWarning( 500, "Invalid Note Process" );
			return;
		}
		$note_row	=& JTable::getInstance('clubnotes', 'Table');
		if($return_data['note_id']  > 0){
			$note_row->load($return_data['note_id']);
				
		}else{
			//JError::raiseWarning( 500, "Invalid Payment Process" );
			//return;
		}		
	
		JHTML::_('script', 'notes.js?'.time(), 'components/com_clubreg/assets/js/');			
		?>
			<form action="index2.php" method="post"  style="margin:2px;text-align:left;" name="note_admin" id="note_admin" class="form-validate">				
				<div class="h3"><?php  ClubHtmlHelper::renderIcon(array('img'=>'notes.png','text'=>'Notes'));?> Notes :: <?php echo ucwords($row->surname." ".$row->givenname); ?></div>
				<div class="fieldset">				
				<div class="n"><label class="lbcls" for="note_status">Make Private </label><?php echo $colon; ?><input type="checkbox" name="note_status" id="note_status" <?php echo isset($note_row->note_status) && ($note_row->note_status == 1)?"checked":""?> value="1" /></div>				
				
				<div class="n" style="vertical-align:top">
					<label class="lbcls" style="vertical-align:top;" for="notes">Notes</label><?php echo $colon; ?>
				</div>
				<div class="n" style="vertical-align:top;text-align:right;">
					<textarea class="intext" rows=4 id="notes" name="notes" style="width:95%"><?php echo stripslashes($note_row->notes); ?></textarea>
				</div>				
				<div style="text-align:center;padding:3px;">
				<input class="button validate" name='normal_save_note' id="normal_save_note" type="submit" value='Add Note' />
				</div>			
				</div>	
				<?php  $in_type = "hidden"; ?>		
					<input type="<?= $in_type ?>" name="note_id" value="<?php echo $note_row->note_id; ?>" />	
					<input type="<?= $in_type ?>" name="member_id" value="<?php echo $row->member_id; ?>" />					
					<input type="<?= $in_type ?>" name="option" value="<?= $option ?>" />	
					<input type="<?= $in_type ?>" name="Itemid" value="<?= $Itemid ?>" />
					<input type="<?= $in_type ?>" name="task" value="savenotes" />	
					<input type="<?= $in_type ?>" name="c" value="userreg" />
					<input type="<?= $in_type; ?>" name="no_html" value="1" />		
					<?php echo JHTML::_( 'form.token' ); ?>
			</form>		
			<?php 			
		}
		function savenotes(){
			
			$user		= &JFactory::getUser();		
			
			JRequest::checkToken() or jexit( 'Invalid Token' );
			
			$post = JRequest::get('post');
			
			$note_row	=& JTable::getInstance('clubnotes', 'Table');
			$note_row->bind( $post );
			
			$note_row->note_status  = intval(JRequest::getVar('note_status','0', 'request', 'int'));
			
			$row_old	=& JTable::getInstance('clubnotes', 'Table');
			
			if(intval($post["note_id"])>0){
				$row_old->load($post["note_id"]);
				$other_details["primary_id"] = $note_row->note_id;
				$other_details["short_desc"] = "updated note";
				ClubregHelper::save_old_data($row_old,$other_details);
			}
			
			if(isset($note_row->note_id) && intval($note_row->note_id) > 0){
				
			}else{			
				$note_row->created = date('Y-m-d H:i:s');
				$note_row->created_by = $user->id;
			}
			
			$not_in = array();
			$msg = array();
			
			if(strlen(trim($note_row->notes)) == 0 ){
				$not_in[] = false;
			}					
			
			if(count($not_in) == 0){
				$note_row->store();
			}else{
				JError::raiseWarning( 500, sprintf("Note Details are not Complete",implode(", ",$msg)));
			}
			
			$row	=& JTable::getInstance('clubregmembers', 'Table');			
			$row->load($note_row->member_id);
			
			$note_list = ClubNotesHelper::getNoteList($row);
			ClubNotesHelper::renderNoteList($note_list,$row);			
			
		}
		
		function deletenote(){
			
			
			$user		= &JFactory::getUser();
			
			$next_action = false;
				
			if(	JRequest::checkToken('get') ){					
				
				$note_row	=& JTable::getInstance('clubnotes', 'Table');
				$note_row->note_id = intval(JRequest::getVar( "note_id", 0, 'get', 'int' ));			
				$note_row->note_status  = 99;
				
				if($note_row->store()){
					$next_action = true;
				}else{
					$next_action = false;	
				}	
			}
			echo json_encode( $next_action );			
		}
		function batchupdate(){
			
			$batch_controls["update_group"] = array("default"=>-1, "label"=>GROUP);
			$batch_controls["update_sgroup"] = array("default"=>-1, "label"=>SUBGROUP);
			$batch_controls["update_gender"] = array("default"=>-1, "label"=>"Gender");
			$batch_controls["update_season"] = array("default"=>0, "label"=>SEASON);
			$batch_controls["update_memberlevel"] = array("default"=>-1, "label"=>PLAYER.' level');
			
			$registered_users = JRequest::getVar( "reg_members", array(), 'post', 'array' ); // get the whole array from the post
			
			$update_group = intval(JRequest::getVar( "update_group", -1, 'post', 'int' ));		
			$update_sgroup = intval(JRequest::getVar( "update_sgroup", -1, 'post', 'int' ));
			$update_season = intval(JRequest::getVar( "update_season", 0, 'post', 'int' ));
			
			$update_gender = trim(JRequest::getVar( "update_gender", -1, 'post', 'string' ));
			$update_memberlevel = trim(JRequest::getVar( "update_memberlevel", -1, 'post', 'string' ));
			
			$app = JFactory::getApplication();
			
			
			$user		= &JFactory::getUser();
			
			$member_data 	=& JModel::getInstance('member', 'ClubRegModel');
			$member_data->getData($user->id); // get the member data for current user
			$member_params =  new JParameter( $member_data->user_data->params );
			
			
			
			if($member_params->get('manageusers' ) !== "yes"){ JError::raiseWarning( 500, "You are not authorised to Update the Member Details" ); return; }
			
			
			foreach($registered_users as $member_id){
				
				$row_old	=& JTable::getInstance('clubregmembers', 'Table');
				if(intval($member_id)>0){
					$row_old->load($member_id);
				}			
				
				$other_details["primary_id"] = $member_id;
				$other_details["short_desc"] = "batch updated ".$row->playertype;
				ClubregHelper::save_old_data($row_old,$other_details);
				
				$row = & JTable::getInstance('clubregmembers', 'Table');
				$row->member_id = $member_id;
				
				if($update_group != $batch_controls["update_group"]["default"]){
					$row->group = $update_group;					
					$row->subgroup = 0;
				}
				
				if($update_sgroup != $batch_controls["update_sgroup"]["default"]){
					$row->subgroup = $update_sgroup;
				}
				
				if($update_season != $batch_controls["update_season"]["default"]){
					$row->year_registered = $update_season;
				}	

				if($update_gender != $batch_controls["update_gender"]["default"]){
					$row->gender = $update_gender;
				}
				
				if($update_memberlevel != $batch_controls["update_memberlevel"]["default"]){
					$row->memberlevel = $update_memberlevel;
				}
				
				// render changes notes
				
				$row->store();
						
			}
			
			$app->enqueueMessage("Details Updated for Registered Users");
			
			JRequest::setVar('view','userreg');
			JRequest::setVar('task','loadregistered');
			parent::display($tpl);
			
		}
		
		function addtag(){			
			
			$tag_text_ = trim(JRequest::getVar( "newtag", '', 'get', 'string' ));	
			$member_id = intval(JRequest::getVar( "member_id", -1, 'get', 'int' ));
			
			$ntags = JRequest::getVar( "ntag_", array(), 'get', 'array' );
			$user		= &JFactory::getUser();
			$db		=& JFactory::getDBO();			
						
			$value_ = array();
			
			if(strlen($tag_text_) > 0){
			
				$d_qry = sprintf("select tag_id from %s where tag_text = %s",CLUB_TAG_TABLE,$db->Quote($tag_text_) );
				$db->setQuery($d_qry);
				$tag_id = $db->loadResult();
				
				if(!isset($tag_id) ){
	
					$d_qry = sprintf("insert into %s set tag_id =NULL, tag_text = %s, published = '1', created_date = '%s',created_time='%s', createdby = '%s' ",
					CLUB_TAG_TABLE, $db->Quote($tag_text_),date('Y-m-d'),date('H:i:s'),$user->id);
					$db->setQuery($d_qry);
					$db->query();
					
					$tag_id = $db->insertid();			
				}
				
				
				$ntags[] = $tag_id;
				//CLUB_TAGPLAYER_TABLE		
							
			}

			if(count($ntags) > 0 && ($member_id > 0)){
				unset($d_qry);
				foreach($ntags as $onetag){
					$value_[] = sprintf(" ('%d','%d') ",$onetag,$member_id );
				}				
			}
			
			
			$d_qry = sprintf("insert into %s  (tag_id,member_id) values %s;",
			CLUB_TAGPLAYER_TABLE,implode(",",$value_));
			
			$db->setQuery($d_qry);
			$db->query();
			
			
			// try render the tag list
			$tagModel	=& JModel::getInstance('tags', 'ClubRegModel');
			
			$tag_list = $tagModel->getTags($member_id);;
			ob_start();
			if(count($tag_list) > 0){
				foreach($tag_list as $a_tag){
					$tagModel->renderTag($a_tag);
				}
			}
			$tag_string = ob_get_contents();
			ob_end_clean();
			echo $tag_string;
			
			
			
			
		} // function end;
		
		function deletetag(){			
				
			$next_action = false;
			
			$db		=& JFactory::getDBO();
			
			if(	JRequest::checkToken('get') ){
			
				
				$tagkey = trim(JRequest::getVar( "tagkey", '', 'get', 'string' ));
				list($tag_id,$member_id) = explode("_",$tagkey);
				
				if(isset($tag_id) && $tag_id > 0 && isset($member_id) && $member_id > 0 ){
					
					$d_qry = sprintf("delete from %s where tag_id = %d and member_id = %d ",CLUB_TAGPLAYER_TABLE,$tag_id,$member_id);
					$db->setQuery($d_qry);
					$db->query();		
					if($db->getErrorNum() > 0){
					}else{
						$next_action = true;
					}
				}
			}
			echo json_encode( $next_action );
			
		}
		function gettags(){
			
			$member_id = intval(JRequest::getVar( "member_id", -1, 'get', 'int' ));
			
			$tagModel	=& JModel::getInstance('tags', 'ClubRegModel');
			$tag_list = $tagModel->getNotTag($member_id);
			
			if(count($tag_list) > 0 ){

				foreach($tag_list as $a_tag){?>
					<input type="checkbox" value="<?php echo $a_tag->tag_id?>" name="ntag_[]" class="nottag" /><?php echo $a_tag->tag_text ;?><br />
				<?php 				
				}
			}
		}
		
		function save_extradetails(){		
			
			JRequest::checkToken() or jexit( 'Invalid Token' );
				
			$post = JRequest::get('post');			
			$db		=& JFactory::getDBO();
			
			$member_id = intval(JRequest::getVar('member_id','0', 'request', 'int'));
				
			unset($d_qry);
			$d_qry = array();
			$key_pattern = "/extra_/";	
			
				foreach($post as $t_key =>$t_value ){
					
					if(preg_match($key_pattern, $t_key)){
					
						$contact_value = JRequest::getVar( $t_key, '', 'post', 'string' );
						
						if($member_id > 0){
							$d_qry[] = sprintf("insert into %s set `member_id` = %d ,`contact_detail` = %s ,`contact_value` = %s on duplicate key update
									contact_value = values(contact_value);
									",CLUB_CONTACT_TABLE,$member_id,$db->Quote($t_key),$db->Quote($contact_value));
						}
					}
				}
			

			if(count($d_qry) > 0){
				$q_string = implode("",$d_qry);
				$db->setQuery($q_string);
				if(!$db->queryBatch()){
					return JError::raiseError(500, $db->getErrorMsg() );
				}
			}
			unset($d_qry);
			
			?>
			<script type="text/javascript">
			<!--				
				if(window.parent.document.getElementById('adminFormExtradetails_span')){
					window.parent.document.getElementById('adminFormExtradetails_span').innerHTML = "Saved";
				};
				
			//-->
			</script>
				<?php 	
			
		}
		
		function usersearch(){
			global $option,$Itemid;
			$db		=& JFactory::getDBO();
				
			$searhstring = trim(JRequest::getVar('searhstring','', 'post', 'string'));			
					
			$d_str[] = " member_id";
			$d_str[] = " concat(givenname,' ',surname) as fname";
			
			$where_str = "";
			$where_[] = sprintf(" givenname  like '%%%s%%'", $searhstring);
			$where_[] = sprintf(" surname  like '%%%s%%'", $searhstring);			
			
			if(count($where_) > 0){
				$where_str = " where ".implode(" or ",$where_ );
			}	

			$var_string = implode(",",$d_str);
			
			$d_qry = sprintf("select %s from %s %s ", 
					$var_string,
					CLUB_REGISTEREDMEMBERS_TABLE,
					$where_str
					);			
			
			$db->setQuery($d_qry,0, 30);
			$all_results = $db->loadObjectList();
			$fresult = array();
			$render_url = sprintf("index.php?option=%s&c=userreg&task=renderreg&Itemid=%s&member_id=",$option,$Itemid);
			foreach($all_results as $a_reg){
				$fresult[] = sprintf("<a href='%s%d' class='regLink'>%s</a>",$render_url,$a_reg->member_id,$a_reg->fname);
			}
			
			echo json_encode($fresult);
			
		}
	
		
		
	

}