Window.onDomReady(function(){

	
	if($("template_list")){
		
	var myVerticalSlide = new Fx.Slide('template_list').hide();
		
		if($("newMessages")){
			$("newMessages").addEvents({
				
				'click':function(event){	
					event.stop();					
				   myVerticalSlide.toggle();	
					
				}	
			});	
			
		}
		
	}
	
	
	if($("toggler_div")){
		
		var myVerticalSlide = new Fx.Slide('toggler_div').hide();
			
			if($("toButton")){
				$("toButton").addEvents({
					
					'click':function(event){	
						event.stop();					
					   myVerticalSlide.toggle();						
					}	
				});					
			}			
		}
	
	$$(".recipients_check").each(function(e){
		
		e.addEvent('click',function(){	
			
			
			var rspan = $('recipients_span_'+e.value);
			var rspan_clone = rspan.clone();
			if(e.checked){	
				rspan_clone.set('id','recipients_span_c'+e.value);
				rspan_clone.set('class','recipients_span');
				rspan_clone.inject($('to_content'));
				recipient_count++;
			}else{
				var rspan = $('recipients_span_c'+e.value);
				rspan.dispose();
				recipient_count--;
			}
			
			
		});
		
	});
	
	
});