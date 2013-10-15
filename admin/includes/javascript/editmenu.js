$(document).ready(function() {

	// save options
	$("#{JSID}saveoptionsbutton").click(function() {
	
		var elements = new Array();
		elements[0] = $("#{JSID}saveoptionsbutton");
		elements[1] = $("#{JSID}saveoptionsreset");
		elements[2] = $("#{JSID}sisters");
		elements[3] = $("#{JSID}pagelevels");
		elements[4] = $("#{JSID}navlevels");
		disableElements(elements);

		showprogressbox("Saving Menu Options for Page: "+$("#{JSID}page").val()+" ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/menus/saveoptions.php",
			{
	       		page: $("#{JSID}page").val(),
	       		sisters: $("#{JSID}sisters").attr('checked'),
	       		pagelevels: $("#{JSID}pagelevels").val(),
	       		navlevels: $("#{JSID}navlevels").val()
	   		},
	   		function(xml)
			{
				enableElements(elements);
				showmessageXML(xml);
			},
			elements
		); // post 
		
	}); // save options
		
}); // document