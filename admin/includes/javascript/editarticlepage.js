$(document).ready(function() {
	
	// save section title
	$("#{JSID}savesectiontitlebutton").click(function() {
		var elements = new Array();
		elements[0] = $("#{JSID}savesectiontitlebutton");
		elements[1] = $("#{JSID}savesectiontitlereset");
		elements[2] = $("#{JSID}sectiontitle");
		disableElements(elements);
		
		$("#{JSID}savesectiontitlebutton").attr('disabled', 'disabled');
	
		showprogressbox("Saving Section Title for Section ID: "+$("#{JSID}articlesection").val()+" ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/articles/savesectiontitle.php",
			{
				articlesection: $("#{JSID}articlesection").val(),
	   			sid: $("#{JSID}sid").val(),
	   			page: $("#{JSID}page").val(),
	   			sectiontitle: $("#{JSID}sectiontitle").val()
	   		},
	   		function(xml)
			{
	
				postRequest(
					projectroot+"admin/includes/ajax/articles/updatesectiontitle.php",
	       			{
						articlesection: $("#{JSID}articlesection").val(),
	       				sid: $("#{JSID}sid").val()
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
	}); // save section title

}); // document