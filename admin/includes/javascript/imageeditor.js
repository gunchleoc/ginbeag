/**
 * returns text
 */
function savestatusfailedmessage()
{
	return "Failed to save!";
}


$(document).ready(function() {

	// only activate buttons if there is a change
	var filenameisedited=false;
	var propertiesisedited=false;
	
	/**
	 * call when something changes in the text
	 */
	function setfilenameisedited()
	{
		filenameisedited=true;
		$("#{JSID}submitfilename").val("Save Changes");
		$("#{JSID}submitfilename").css("font-style","normal");
		//$("#{JSID}submitfilename").css("height:auto;");
	}
	
	/**
	 * call when changes are reset or saved
	 */
	function setfilenameisnotedited()
	{
		filenameisedited=false;
		$("#{JSID}submitfilename").val("To change image, type in the box above");
		$("#{JSID}submitfilename").css("font-style","italic");
	}

	/**
	 * call when something changes in the text
	 */
	function setpropertiesisedited()
	{
		filenameisedited=true;
		$("#{JSID}submitproperties").val("Save Changes");
		$("#{JSID}submitproperties").css("font-style","normal");
		//$("#{JSID}submitfilename").css("height:auto;");
	}
	
	/**
	 * call when changes are reset or saved
	 */
	function setpropertiesisnotedited()
	{
		filenameisedited=false;
		$("#{JSID}submitproperties").val("To change image alignment, select a button above");
		$("#{JSID}submitproperties").css("font-style","italic");
	}

	addlistenersFilename();
	addlistenersProperties();

	
	/**
	 * listeners for filename pane
	 */	
	function addlistenersFilename()
	{
		setfilenameisnotedited();
		var elements = new Array();
		elements[0] = $("#{JSID}submitfilename");
		elements[1] = $("#{JSID}resetfilename");
		
		// watch edit state to activate save button
		$("#{JSID}imagefilename").on("keypress", function() {
			setfilenameisedited();
		});	
		
		/* save image filename */
		$("#{JSID}submitfilename").click(function() {

			disableElements(elements);
			
			//alert("Submit filename clicked!");

			showprogressbox("Saving Image File: "+$("#{JSID}imagefilename").val()+" ... ");
			
       		postRequest(
				projectroot+"admin/includes/ajax/imageeditor/saveimagefilename.php",
       			{
					imagefilename: $("#{JSID}imagefilename").val(),
	       			page: $("#{JSID}page").val(),
	       			item: $("#{JSID}item").val(),
	       			elementtype: $("#{JSID}elementtype").val()
	       		},
				function(xml)
				{
					var element=$(xml).find('message');
					var error = element.attr("error");
					if(error !="1")
		       		{
		       			//alert("Image saved!");
			       		setfilenameisnotedited();
			       		
						postRequest(
							projectroot+"admin/includes/ajax/imageeditor/updateimage.php",
			       			{
				       			page: $("#{JSID}page").val(),
				       			item: $("#{JSID}item").val(),
				       			elementtype: $("#{JSID}elementtype").val()
				       		},
							function(html)
							{
								$("#{JSID}editorimagepane").html(html);
							},
							elements
				    	); // post updateimage.php
				    	
				    	if($("#{JSID}imagefilename").val().length<1)
				    	{
				    		$("#{JSID}editorpropertiespane").html("");
				    	}
				    	else
				    	{
				    		postRequest(
								projectroot+"admin/includes/ajax/imageeditor/showimageproperties.php",
				       			{
					       			page: $("#{JSID}page").val(),
					       			item: $("#{JSID}item").val(),
					       			elementtype: $("#{JSID}elementtype").val()
					       		},
								function(html)
								{
									$("#{JSID}editorpropertiespane").html(html);
									addlistenersProperties();
								},
								elements
					    	); // post showimageproperties.php
				    	}
			       		
					} // no error
					showmessageXML(xml);
					enableElements(elements);
				},
				elements
	    	); // post saveimagefilename.php

		}); // submitfilename
		
		
		
		/* reset edit status */
		$("#{JSID}resetfilename").click(function() {
       		setfilenameisnotedited();
		});	// resetbutton

	} // addlistenersfilename



	
	/**
	 * listeners for alignment pane
	 */	
	function addlistenersProperties()
	{
		setpropertiesisnotedited();
		
		var elements = new Array();
		elements[0] = $("#{JSID}submitproperties");
		elements[1] = $("#{JSID}resetproperties");
		
		// watch edit state to activate save button
		$("#{JSID}imagealignleft").on("click", function() {
			setpropertiesisedited();
		});
		
		$("#{JSID}imagealignright").on("click", function() {
			setpropertiesisedited();
		});
		
		$("#{JSID}imagealigncenter").on("click", function() {
			setpropertiesisedited();
		});
		
		/* save image filename */
		$("#{JSID}submitproperties").click(function() {

			disableElements(elements);

			showprogressbox("Saving Image Alignment: "+$('input[name={JSID}imagealign]:checked').val()+" ... ");
			
			postRequest(
				projectroot+"admin/includes/ajax/imageeditor/saveimageproperties.php",
       			{
					imagealign: $('input[name={JSID}imagealign]:checked').val(),
	       			page: $("#{JSID}page").val(),
	       			item: $("#{JSID}item").val(),
	       			elementtype: $("#{JSID}elementtype").val()
	       		},
				function(xml)
				{
					var element=$(xml).find('message');
					var error = element.attr("error");
					if(error !="1")
		       		{
		       			//alert("Image saved!");
			       		setpropertiesisnotedited();
					} // no error
					showmessageXML(xml);
					enableElements(elements);
				},
				elements
	    	); // post saveimageproperties.php
			
		}); // submitfilename
		
		/* reset edit status */
		$("#{JSID}resetproperties").click(function() {
       		setpropertiesisnotedited();
		});	// resetbutton

	} // addlistenersproperties
	
}); // document