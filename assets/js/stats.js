/**
 * render update table
 */

Window.onDomReady(function(){	
			
	$$(".bt_stats").each(function(e){
		
		e.addEvent('click',function(){
			var ctrl_name = e.get('rel');
			var myparent =  e.getParent("td");

			
			var posted_controls = $$('form [name^='+ctrl_name+']');
			
			var posted_items = {};	;
			var final_data = {}
			Array.each(posted_controls, function (e){				
				final_data[e.get('name')] = e.get('value');				
			});
			
			final_data['option'] = $('option').get('value');
			final_data['Itemid'] = $('Itemid').get('value');	
			final_data['stats_date'] = $('stats_date').get('value');
			final_data['c'] = 'stats';
			final_data['task'] = 'savestats';
			final_data['no_html'] = '1';
			final_data[token_value] = 1;
			final_data['which_stats'] = ctrl_name;		
			
			var a = new Ajax( 'index2.php', {
				method: 'post',
				data:final_data,
				onRequest: function(){ 
					myparent.addClass('loading1');
						
				},
				onComplete: function(){		
					if(myparent.hasClass('loading1')){
						myparent.removeClass('loading1');
					}
						var data = Json.decode(this.response.text);
						alert(data["msg"]);
					}
				}).request();				
			
		});
	});
			
});