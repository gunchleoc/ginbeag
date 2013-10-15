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
	
}); // document