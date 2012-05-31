Window.onDomReady(function(){	
	
	
	$("normal_save").addEvents({
		
		click:function(event){				
			
			d_form = $("payment_admin");	
			
			var fstate = true;
			
			var t_value = $('payment_method').get('value');
			if(t_value == 0){				
				after_check($('payment_method'));
				fstate = false;
			}
			
			t_value = $('payment_transact_no').get('value');
			if (!t_value) {
				after_check($('payment_transact_no'));
				fstate = false;
			}
			
			t_value = $('payment_status').get('value');
			if(t_value == 0){				
				after_check($('payment_status'));
				fstate = false;
			}
			
			t_value = $('payment_desc').get('value');
			if(t_value == 0){				
				after_check($('payment_desc'));
				fstate = false;
			}
			
			t_value = $('payment_amount').get('value');
			if(!checknumeric(t_value)){				
				after_check($('payment_amount'));
				fstate = false;
			}					
				
				
		if(fstate){
			
			event.stop();				
			d_form.send({					
				onComplete:function(response){
					var parent_window = window.parent;			
					var payment_div = parent_window.document.id('payment_div');					
					payment_div.empty();
					payment_div.set('html' ,response);					
				}				
			});		
			
			window.top.setTimeout("window.parent.document.getElementById('sbox-window').close()", 700);			
		}else{
			
			var msg = 'Some values are not acceptable.  Please retry.';	
			if($('payment_method').hasClass('invalid')){msg += '\n\t* Invalid Payment Method';} 
			if($('payment_transact_no').hasClass('invalid')){msg += '\n\t* Invalid Transaction Number';}
			if($('payment_status').hasClass('invalid')){msg += '\n\t* Invalid Payment Status';}
			if($('payment_desc').hasClass('invalid')){msg += '\n\t* Invalid Payment Description';}
			if($('payment_amount').hasClass('invalid')){msg += '\n\t* Invalid Payment Amount';}
			
			alert(msg);
			
			event.stop();	
			return false;
			
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