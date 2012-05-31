Window.onDomReady(function(){	
	
	
	$("normal_save_note").addEvents({
		
		click:function(event){				
			
			d_form = $("note_admin");	
			
			var fstate = true;				
			
		if(fstate){
			
				event.stop();				
				d_form.send({					
					onComplete:function(response){
						var parent_window = window.parent;			
						var note_div = parent_window.document.id('note_div');					
						note_div.empty();
						note_div.set('html' ,response);					
					}				
				});		
				
				window.top.setTimeout("window.parent.document.getElementById('sbox-window').close()", 700);			
			}		
				 
		}	
	});	
	
});

function after_check(d_obj){	
	d_obj.addClass("invalid");
	
}
function checknumeric(value) {
	regex=/^(\d|-)?(\d|,)*\.?\d*$/;
	return regex.test(value);
}