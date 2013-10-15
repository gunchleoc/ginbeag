$(document).ready(function() {

	// save description
	$("#{JSID}savedescriptionbutton").click(function() {
		var elements = new Array();
		elements[0] = $("#{JSID}savedescriptionbutton");
		elements[1] = $("#{JSID}resetdescriptionbutton");
		elements[2] = $('input[name={JSID}permission]');
		disableElements(elements);

		showprogressbox("Saving Description for "+$("#{JSID}filename").val()+" ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/imagelist/savedescription.php",
   			{
				filename: $("#{JSID}filename").val(),
   				caption: uni2ent($("#{JSID}caption").val()),
   				source: uni2ent($("#{JSID}source").val()),
   				sourcelink: $("#{JSID}sourcelink").val(),
   				copyright: uni2ent($("#{JSID}copyright").val()),
   				permission: $('input[name={JSID}permission]:checked').val()
       		},
       		function(xml)
			{
   				postRequest(
   					projectroot+"admin/includes/ajax/imagelist/updateimage.php",
	       			{
    					filename: $("#{JSID}filename").val()
		       		},
		       		function(html)
    				{
    					$("#{JSID}image").html(html);
   						enableElements(elements);
    				},
    				elements
    			); // post
    			showmessageXML(xml);
    		},
    		elements
	    ); // post
	
	}); // savedescription
	
	// add categories
	$("#{JSID}addcatbutton").click(function() {
	
		var elements = new Array();
		elements[0] = $("#{JSID}addcatbutton");
		elements[1] = $("#{JSID}removecatbutton");
		elements[2] = $("#{JSID}selectedcat");
		disableElements(elements);
		
		showprogressbox("Saving Categories for "+$("#{JSID}filename").val()+" ...");

		postRequest(
			projectroot+"admin/includes/ajax/imagelist/addcategories.php",
   			{
				filename: $("#{JSID}filename").val(),
       			selectedcat: $("#{JSID}selectedcat").val()
       		},
       		function(xml)
			{
   				postRequest(
   					projectroot+"admin/includes/ajax/imagelist/updatecategories.php",
	       			{
    					filename: $("#{JSID}filename").val()
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
		
		showprogressbox("Saving Categories for "+$("#{JSID}filename").val()+" ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/imagelist/removecategories.php",
   			{
				filename: $("#{JSID}filename").val(),
       			selectedcat: $("#{JSID}selectedcat").val()
       		},
       		function(xml)
			{
   				postRequest(
   					projectroot+"admin/includes/ajax/imagelist/updatecategories.php",
	       			{
    					filename: $("#{JSID}filename").val()
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


	// get usage
	$("#{JSID}showusagebutton").click(function() {
		var elements = new Array();
		elements[0] = $("#{JSID}showusagebutton");
		disableElements(elements);	

		showprogressbox("Fetching Usage Info for "+$("#{JSID}filename").val()+" ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/imagelist/getimageusage.php",
   			{
				filename: $("#{JSID}filename").val()
       		},
       		function(html)
			{
				$("#{JSID}usage").html(html);
				$("#{JSID}showusagebutton").val('Update info');
				showmessage("Updated usage info for "+$("#{JSID}filename").val());
				enableElements(elements);
			},
			elements
		); // post
	
	}); // get usage

}); // document