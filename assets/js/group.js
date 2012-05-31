/**
 * change groups on the edit player page (junior, senior)
 */
Window.onDomReady(function(){
	
if($('g_group')){
	
	$('g_group').addEvent('change', function (){
		
		var g_group_id = $('g_group').get('value');
		
		var toption = $('option').get('value');
		var Itemid = $('Itemid').get('value');	
		
		
		var a = new Ajax( 'index2.php?option='+toption+'&Itemid='+Itemid+'&c=userreg&task=subgroup&no_html=1&group_id='+g_group_id, {
		method: 'get',
		onRequest: function(){
			$("g_subgroup").empty();
			var newoption = new Option('- Loading Please Wait -','-1' );				
			$("g_subgroup").add(newoption);		
		},
		onComplete: function(){
							
				var data = Json.decode(this.response.text);		
				$("g_subgroup").empty();
				data.each(function(item){ 						
					var newoption = new Option(item['text'],item['value'] );				
					$("g_subgroup").add(newoption);					
				});
				
				
			}
		}).request();
		
		return false;			
	});
}	;

	

});
/**
 * loading subgroups on the edit player page (guardian, existing and new juniors)
 */
Window.onDomReady(function(){
	
	$$(".group_select").each(function(e){
		
		e.addEvent('change',function(){			
			
			var t_string = e.id;
			var farray = t_string.split("_");			
			
			if(farray[0] == "r")			
				var sub_select = "r_subgroupplayer_"+farray[2];
			else
				var sub_select = "p_subgroupplayer_"+farray[2];
			
			
			var g_group_id =  $(e).get('value');
			
			var toption = $('option').get('value');
			var Itemid = $('Itemid').get('value');	
			
			
			var a = new Ajax( 'index2.php?option='+toption+'&Itemid='+Itemid+'&c=userreg&task=subgroup&no_html=1&group_id='+g_group_id, {
				method: 'get',
				onRequest: function(){
					$(sub_select).empty();
					var newoption = new Option('- Loading Please Wait -','-1' );				
					$(sub_select).add(newoption);		
				},
				onComplete: function(){
									
						var data = Json.decode(this.response.text);		
						$(sub_select).empty();
						data.each(function(item){ 						
							var newoption = new Option(item['text'],item['value'] );				
							$(sub_select).add(newoption);					
						});
						
						
					}
				}).request();		
			
		});
		
	});
	
});

