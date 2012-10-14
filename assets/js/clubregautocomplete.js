/**
 * club reg auto complete
 */

Window.onDomReady(function(){
	
	var search_Itemid = ($('search_Itemid'))?$('search_Itemid').get('value'):0;
	var search_option = ($('search_option'))?$('search_option').get('value'):0;
	// Our instance for the element with id "demo-remote"
	new Autocompleter.Ajax.Json('searchMembers', 'index2.php?option='+search_option+'&task=usersearch&c=userreg&no_html=1&Itemid='+search_Itemid, {
		//name the element containing the search term something suitable
		//otherwise defaults to 'value'
		'postVar': 'searhstring'
	});	
	
})