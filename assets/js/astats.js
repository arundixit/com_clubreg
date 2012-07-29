Window.onDomReady(function(){	
	
	
	$("normal_save").addEvents({
		
		click:function(event){				
			
			d_form = $("stats_admin");	
			
			event.stop();				
			d_form.send({					
				onComplete:function(response){
					var parent_window = window.parent;			
					var stats_div = parent_window.document.id('stats_div');					
					stats_div.empty();
					stats_div.set('html' ,response);					
				}				
			});		
			
			window.top.setTimeout("window.parent.document.getElementById('sbox-window').close()", 700);			 
		}	
	});	
	
});