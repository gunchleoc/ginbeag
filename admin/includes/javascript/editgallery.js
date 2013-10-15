$(document).ready(function() {

	// save image
	$("#{JSID}saveimagebutton").click(function() {
	
		var elements = new Array();
		elements[0] = $("#{JSID}saveimagebutton");
		elements[1] = $("#{JSID}saveimagereset");
		elements[2] = $("#{JSID}imagefilename");
		disableElements(elements);
	
		showprogressbox("Saving Image: "+$("#{JSID}imagefilename").val()+" ...");
		
		
		postRequest(
			projectroot+"admin/includes/ajax/galleries/saveimage.php",
			{
	   			page: $("#{JSID}page").val(),
	   			imagefilename: $("#{JSID}imagefilename").val(),
	   			galleryitemid: $("#{JSID}galleryitemid").val()
	   		},
	   		function(xml)
			{
	
   			
				postRequest(
	   					projectroot+"admin/includes/ajax/galleries/updateimage.php",
		       			{
		       				page: $("#{JSID}page").val(),
		       				galleryitemid: $("#{JSID}galleryitemid").val()
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
	}); // save image

}); // document