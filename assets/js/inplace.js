/**
 * render update table
 */

Window.onDomReady(function(){
	
	if($("update_table")){
		var myVerticalSlide = new Fx.Slide('update_table').hide();
	
	
		if($("batch_updater")){
			$("batch_updater").addEvents({
				
				'click':function(event){					
					event.stop();
				    myVerticalSlide.toggle();				
				}	
			});	
			
		}
	}
	
	if($("bt_update")){
		$("bt_update").addEvents({
			
			'click':function(event){
				var fstate = false;
				var $msg = "";
				
				var t_value = $('update_memberlevel').get('value');			
				
				if(t_value == -1){					
					
				}else{
					fstate = fstate || true;
				}
				
				var t_value = $('update_group').get('value');			
				
				if(t_value == -1){					
					
				}else{
					fstate = fstate || true;
				}
				
				t_value = $('update_sgroup').get('value');				
				
				if (t_value == -1){				
					
				}else{
					fstate = fstate || true;
				}
				
				t_value = $('update_season').get('value');			
				
				if(t_value == 0){							
					
				}else{
					fstate = fstate || true;
				}
				t_value = $('update_gender').get('value');			
				
				if(t_value == -1){							
					
				}else{
					fstate = fstate || true;
				}
				
				t_value = $('boxchecked').get('value');	
				if(!fstate)
					$msg = "Please Select at Least One Property to Update.";
				
				if(t_value == 0){
					$msg = $msg + "\nPlease Select at Least one Item.";
					fstate = false;
				}
				
				if(!fstate){					
					alert($msg)
					event.stop();
				}else{
					$('task').set('value','batchupdate');			
				}
				
				return fstate;				
				
			}	
		});	
		
	}
	// ajax function to update subgroups on batch update 
	if($('update_group')){
		
		$('update_group').addEvent('change', function (){
			
			var g_group_id = $('update_group').get('value');
			
			var toption = $('option').get('value');
			var Itemid = $('Itemid').get('value');	
			
			
			var a = new Ajax( 'index2.php?option='+toption+'&Itemid='+Itemid+'&c=userreg&task=subgroup&no_html=1&group_id='+g_group_id, {
			method: 'get',
			onRequest: function(){
				$("update_sgroup").empty();
				var newoption = new Option('- Loading Please Wait -','-1' );				
				$("update_sgroup").add(newoption);		
			},
			onComplete: function(){
								
					var data = Json.decode(this.response.text);		
					$("update_sgroup").empty();
					data.each(function(item){ 						
						var newoption = new Option(item['text'],item['value'] );				
						$("update_sgroup").add(newoption);					
					});
					
					
				}
			}).request();
			
			return false;			
		});
	};
	
	if($$("table.userTable") && Browser.Engine.trident){
		var userTableRows = $$("table.userTable tr");
		var i = 0;
		Array.each(userTableRows, function (e){				
			if((i%2) == 0){
				e.addClass('userTableOdd');		
			}
			i++;
		});
	}
	
	
	
});