$(document).ready(function() {

if($("#{JSID}imagefilename").val()=="")
{
	var elements = new Array();
	elements[0] = $("#{JSID}removeimagebutton");
	elements[1] = $("#{JSID}removeconfirm");
	disableElements(elements);
}

	// save properties
	$("#{JSID}savepropertiesbutton").click(function() {
		var elements = new Array();
		elements[0] = $("#{JSID}savepropertiesbutton");
		elements[1] = $("#{JSID}savepropertiesreset");
		elements[2] = $("#{JSID}title");
		elements[3] = $("#{JSID}link");
		disableElements(elements);
		
		showprogressbox("Saving Properties for Link ID : "+$("#{JSID}linkid").val()+" ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/linklists/savelinkproperties.php",
			{
				sid: $("#{JSID}sid").val(),
       			page: $("#{JSID}page").val(),
       			title: uni2ent($("#{JSID}title").val()),
       			link: $("#{JSID}link").val(),
       			linkid: $("#{JSID}linkid").val()
	   		},
	   		function(xml)
			{
	   			postRequest(
   					projectroot+"admin/includes/ajax/linklists/updatelinktitle.php",
	       			{
    					sid: $("#{JSID}sid").val(),
	       				page: $("#{JSID}page").val(),
	       				linkid: $("#{JSID}linkid").val()
		       		},
		       		function(html)
    				{
    					$("#{JSID}sectionheader").html(html);
	   					enableElements(elements);
    				},
    				elements
	    		); // post
				showmessageXML(xml);
			},
			elements
		); // post
	}); // save properties
	

	// save image
	$("#{JSID}saveimagebutton").click(function() {
	
		if($("#{JSID}imagefilename").val().length<5)
		{
			alert("Error: Filename too short");
			showmessage("Error: Filename too short");
		}
		else
		{
			var elements = new Array();
			elements[0] = $("#{JSID}saveimagebutton");
			elements[1] = $("#{JSID}saveimagereset");
			elements[2] = $("#{JSID}imagefilename");
			disableElements(elements);
			
			elements[3] = $("#{JSID}removeimagebutton");
			elements[4] = $("#{JSID}removeconfirm");
	
			showprogressbox("Saving Image: "+$("#{JSID}imagefilename").val()+" ...");
		
			postRequest(
				projectroot+"admin/includes/ajax/linklists/saveimage.php",
				{
					sid: $("#{JSID}sid").val(),
	       			page: $("#{JSID}page").val(),
	       			imagefilename: $("#{JSID}imagefilename").val(),
	       			linkid: $("#{JSID}linkid").val()
		   		},
		   		function(xml)
				{
		   			postRequest(
	   					projectroot+"admin/includes/ajax/linklists/updateimage.php",
		       			{
	    					sid: $("#{JSID}sid").val(),
		       				page: $("#{JSID}page").val(),
		       				linkid: $("#{JSID}linkid").val()
			       		},
			       		function(html)
	    				{
	    					$("#{JSID}image").html(html);
		   					enableElements(elements);
			   				$("#{JSID}removeconfirm").removeAttr('checked');
	    				},
	    				elements
		    		); // post
					showmessageXML(xml);
	   			
			}); // post image
		}
	}); // save image
	

	// remove image
	$("#{JSID}removeimagebutton").click(function() {
	
		var elements = new Array();
		elements[0] = $("#{JSID}removeimagebutton");
		elements[1] = $("#{JSID}removeconfirm");
		disableElements(elements);

		showprogressbox("Saving Image: "+$("#{JSID}imagefilename").val()+" ...");

		if($("#{JSID}removeconfirm").attr('checked'))
		{
			postRequest(
				projectroot+"admin/includes/ajax/linklists/removeimage.php",
				{
					sid: $("#{JSID}sid").val(),
		       		page: $("#{JSID}page").val(),
		       		removeconfirm: $("#{JSID}removeconfirm").attr('checked'),
		       		linkid: $("#{JSID}linkid").val()
		   		},
		   		function(xml)
				{
		   			postRequest(
	   					projectroot+"admin/includes/ajax/linklists/updateimage.php",
		       			{
	    					sid: $("#{JSID}sid").val(),
		       				page: $("#{JSID}page").val(),
		       				linkid: $("#{JSID}linkid").val()
			       		},
			       		function(html)
	    				{
	    					$("#{JSID}image").html(html);
			   				$("#{JSID}imagefilename").val('');
			   				$("#{JSID}removeconfirm").removeAttr('checked');
	    				},
	    				elements
		    		); // post
					showmessageXML(xml);
	 			},
				elements
			); // post
		} // if
		else
		{
			alert('Error: In order to remove an image, you have to check "Confirm remove".');
			showmessage('Error: In order to remove an image, you have to check "Confirm remove".');
			enableElements(elements);
		}
	}); // remove image

}); // document