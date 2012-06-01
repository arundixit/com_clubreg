Window.onDomReady(function(){
	
	if($("payment_div")){
		$("payment_div").addEvents({
		
			'click':function(event){		
				
				if(event.target.className && (event.target.className == 'modal-button')){ // ie
					event.stop();
					var t_target = event.target;
					SqueezeBox.fromElement(t_target);
				}			
				
				if(event.target.get('class') == 'modal-button'  ){				//normal
					event.stop();
					var t_target = event.target;
					SqueezeBox.fromElement(t_target);
				}
			}	
		});	
	}
	if($("note_div")){
		$("note_div").addEvents({
			
			'click':function(event){		
				
				if(event.target.className ){
					//(event.target.className == 'modal-button')
					if(event.target.className == 'modal-button'){
						event.stop();
						var t_target = event.target;
						SqueezeBox.fromElement(t_target);
					}
					
					if(event.target.className == 'deleteClass'){
						event.stop();
						alert("here");
					}
					
					
				}else if((event.target) && (event.target.get('class'))  && (event.target.get('class') == 'modal-button')){				
					event.stop();
					var t_target = event.target;
					SqueezeBox.fromElement(t_target);
					
				}
			}	
		});			
		
	}
	 new Fx.Accordion($('accordion'), '#accordion .h3', '#accordion .fieldset' ,			 
			 {
		    	display: 0,
		    	alwaysHide: true,
		    	onActive: function(toggler, element){
		    		toggler.setStyle('color', '#ff3300');
		    	},
		     
		    	onBackground: function(toggler, element){
		    		toggler.setStyle('color', '#222');
		    	}
			 }	
	 );
	
	 if($("tag_link")){
		 
		 pos1 = $("tag_link").getCoordinates();		 
		 var x_left = pos1.left - 150;
			$('tag_div').setStyles({
					left:x_left+"px"					
			
			});		 
		
			$("tag_link").addEvents({ // open div
				
				'click':function(event){					
					event.stop();	
					
					$('newtag').set('value','');$("ntag_divlist").empty();
					
					var c_display =  $('tag_div').getStyles('display').display;
					
					if(c_display == "none"){ c_display = 'block'; }else{ c_display = 'none'; }
					
					
					
					if(c_display == "block"){
						var m_id = $('m_id').get('value');	
						var toption = $('option').get('value');
						var Itemid = $('Itemid').get('value');	
						
						var a = new Ajax( 'index2.php?option='+toption+'&Itemid='+Itemid+'&c=userreg&task=gettags&no_html=1&member_id='+m_id, {
							method: 'get',
							onRequest: function(){
								if($("ntag_divlist"))
									$('ntag_divlist').addClass('loading');
							},
							onComplete: function(response){											
									if($("ntag_divlist")){
										$('ntag_divlist').removeClass('loading');
										$("ntag_divlist").set('html',response);
									}			
								}
							}).request();					
					}
					
					$('tag_div').setStyles({						
						display:c_display				
					});	
					
				}	
			});	
			
		}
	 
	 if($('addtag_bt')){
		 $("addtag_bt").addEvents({
			 'click':function(event){					
					event.stop();	
					var howmanyTags = 0;
					var q_string = ""; // query string
					var newtag = $('newtag').get('value');
					var m_id = $('m_id').get('value');	
					var toption = $('option').get('value');
					var Itemid = $('Itemid').get('value');	
					
					var nottags = $$(".nottag");
					
					nottags.each(function(te){ if(te.get('checked')== true){ howmanyTags = howmanyTags + 1; 
						q_string = q_string + "&ntag_[]="+te.get("value");
					} });			// when we try to save we add the checked to the query string	
					
					
					if (!newtag && (howmanyTags == 0)) {
						$('newtag').addClass("invalid");
						alert("Please Enter a "+tagWord+" or Select an existing "+tagWord);
						return false
					}
					
					var mtagList = $('tagList_'+m_id);
					var a = new Ajax( 'index2.php?option='+toption+'&Itemid='+Itemid+'&c=userreg&task=addtag&no_html=1&newtag='+newtag+'&member_id='+m_id+q_string, {
						method: 'get',
						onRequest: function(){	
							
							if(mtagList){
								mtagList.empty();	
								mtagList.addClass('enlarge');
								mtagList.addClass('loading');								
							}
							
						},
						onComplete: function(response){											
								
								
								if(mtagList){
									mtagList.empty();	
									mtagList.removeClass('enlarge');
									mtagList.removeClass('loading');																
									mtagList.set('html' ,response);	
								}								
							}
						}).request();		
									
					$('tag_div').setStyles({						
						display:'none'				// add tag selection window
					});					
				}	
			 
			 
		 });			 
	 } // add tag	
});
function process_delete(id,ttoken){
	
	$next_action =  confirm('Are you sure want to delete this note?');
		
	if($next_action){
		
		$row_ = $("note_"+id);
		
		if($row_){			
			var toption = $('option').get('value');
			var Itemid = $('Itemid').get('value');	
			
			var a = new Ajax('index2.php?option='+toption+'&Itemid='+Itemid+'&c=userreg&task=deletenote&no_html=1&note_id='+id+'&'+ttoken+'=1', {
				method: 'get',
				onRequest: function(){	},
				onComplete: function(){				
					
						var next_action = Json.decode(this.response.text);	
						
						if(next_action){
							$row_.setStyle('display', 'none');
						}else{
							alert("Note Not deleted");
						}
					}
				}).request();			
			
		}
	}
	
}
function process_tag(rel_value,ttoken){
	
	$next_action =  confirm('Are you sure want to delete this '+tagWord+'?');
	
	if($next_action){
		 var tag_str = "tag_"+rel_value;
		 
		 if($(tag_str)){	
			var toption = $('option').get('value');
			var Itemid = $('Itemid').get('value');	
			
			var a = new Ajax('index2.php?option='+toption+'&Itemid='+Itemid+'&c=userreg&task=deletetag&no_html=1&tagkey='+rel_value+'&'+ttoken+'=1', {
				method: 'get',
				onRequest: function(){	},
				onComplete: function(){				
					
						var next_action = Json.decode(this.response.text);	
						
						if(next_action){
							$(tag_str).setStyles({display:'none'});	
						}else{
							alert(tagWord+" Not deleted");
						}
					}
				}).request();					
		 }
		
	}
	
}