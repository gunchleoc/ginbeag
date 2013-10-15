$(document).ready(function() {
	
	// save source
	$("#{JSID}savesourcebutton").click(function() {
	
		if($("#{JSID}year").val().length!=4)
		{
			alert("Please enter a 4-digit year!");
		}
		else if(!$.isNumeric($("#{JSID}year").val()))
		{
			alert("The year must be a number!");
		}
		else
		{
	
			var elements = new Array();
			elements[0] = $("#{JSID}savesourcebutton");
			elements[1] = $("#{JSID}savesourcereset");
			elements[2] = $("#{JSID}author");
			elements[3] = $("#{JSID}location");
			elements[4] = $("#{JSID}day");
			elements[5] = $("#{JSID}month");
			elements[6] = $("#{JSID}year");
			elements[7] = $("#{JSID}source");
			elements[8] = $("#{JSID}sourcelink");
			elements[9] = $("#{JSID}toc_yes");
			elements[10] = $("#{JSID}toc_no");
			disableElements(elements);
			
			showprogressbox("Saving Source Info for Article ID: "+$("#{JSID}page").val()+" ...");
			
			postRequest(
					projectroot+"admin/includes/ajax/articles/savesource.php",
	       			{
	       				page: $("#{JSID}page").val(),
	       				author: uni2ent($("#{JSID}author").val()),
	       				location: uni2ent($("#{JSID}location").val()),
	       				day: $("#{JSID}day").val(),
	       				month: $("#{JSID}month").val(),
	       				year: $("#{JSID}year").val(),
	       				source: uni2ent($("#{JSID}source").val()),
	       				sourcelink: $("#{JSID}sourcelink").val(),
	       				toc: $("#{JSID}toc_yes").is(':checked')
		       		},
		       		function(xml)
					{
						enableElements(elements);
						showmessageXML(xml);
					},
					elements
			); // post 
		} // year check
	}); // save source
	
	// add categories
	$("#{JSID}addcatbutton").click(function() {
	
		var elements = new Array();
		elements[0] = $("#{JSID}addcatbutton");
		elements[1] = $("#{JSID}removecatbutton");
		elements[2] = $("#{JSID}selectedcat");
		disableElements(elements);
	
		showprogressbox("Saving Categories for Page ID: "+$("#{JSID}page").val()+" ...");
	
		postRequest(
			projectroot+"admin/includes/ajax/articles/addcategories.php",
			{
				page: $("#{JSID}page").val(),
	   			selectedcat: $("#{JSID}selectedcat").val()
	   		},
	   		function(xml)
			{
	
	   			postRequest(
	   					projectroot+"admin/includes/ajax/articles/updatecategories.php",
		       			{
	    					page: $("#{JSID}page").val()
			       		},
			       		function(html)
	    				{
	    					$("#{JSID}categorylist").html(html);
		       				enableElements(elements);
	    				},
	    				elements
	    		); // post 
				showmessageXML(xml);
			},
			elements
		); // post
	
	}); // add categories
	
	
	// remove categories
	$("#{JSID}removecatbutton").click(function() {
		var elements = new Array();
		elements[0] = $("#{JSID}addcatbutton");
		elements[1] = $("#{JSID}removecatbutton");
		elements[2] = $("#{JSID}selectedcat");
		disableElements(elements);
		
		showprogressbox("Saving Categories for Page ID: "+$("#{JSID}page").val()+" ...");
	
		postRequest(
			projectroot+"admin/includes/ajax/articles/removecategories.php",
			{
				page: $("#{JSID}page").val(),
	   			selectedcat: $("#{JSID}selectedcat").val()
	   		},
	   		function(xml)
			{
			
	   			postRequest(
	   					projectroot+"admin/includes/ajax/articles/updatecategories.php",
		       			{
	    					page: $("#{JSID}page").val()
			       		},
			       		function(html)
	    				{
	    					$("#{JSID}categorylist").html(html);
		       				enableElements(elements);
	    				},
	    				elements
	    		); // post 	       			
				showmessageXML(xml);
			},
			elements
		); // post 			
		
	}); // remove categories

}); // document