/**
 * returns text
 */
function savestatusfailedmessage()
{
	return "Failed to save text!";
}


$(document).ready(function() {

	// watch edit state so user won't forget to save. Add this to all buttons.
	var textisedited=false;
	
	/**
	 * call when something changes in the text
	 */
	function settextisedited()
	{
		textisedited=true;
		$("#{JSID}hideeditorbutton").val("Discard Changes");
	}
	
	/**
	 * call when changes are reset or saved
	 */
	function settextisnotedited()
	{
		textisedited=false;
		$("#{JSID}hideeditorbutton").val("Hide Editor");
	}

	// don't know which initial state we're getting
	addlistenersCollapsed();
	addlistenersExpanded();
	
	
	
//http://blog.vishalon.net/index.php/javascript-getting-and-setting-caret-position-in-textarea	
// todo funzt dort, bei mir aber nicht, wieso?
/*
function doGetCaretPosition (ctrl) {

	var CaretPos = 0;
	// IE Support
	if (document.selection) {

		ctrl.focus ();
		var Sel = document.selection.createRange ();

		Sel.moveStart ('character', -ctrl.value.length);

		CaretPos = Sel.text.length;
	}
	// Firefox support
	else if (ctrl.selectionStart || ctrl.selectionStart == '0')
		CaretPos = ctrl.selectionStart;
	return (CaretPos);

}
*/

	/**
	 * helper for Caret
	 */	
	function getcaretstart(element)
	{
		var result=0;
		
		try
		{
       		result=element.caret().start;
		}
		catch(e){
		 //todo try to do something about IE
	 		result=element.val().length;
		}

		// Opera adds 1 pos per line break, undo this
		if(navigator.userAgent.substring(0,5) == "Opera")
		{
			//alert("hallo");
			var splitme = element.val().substring(0,result);
			var split = splitme.split("\n");
			result=result-split.length+1;
			//alert(result);
		}

		return result;
	}
	
	/**
	 * helper for Caret
	 */	
	function getcaretend(element)
	{
		var result=0;
		
		try
		{
       		result=element.caret().end;
		}
		catch(e){
		 //todo try to do something about IE
		       // To get cursor position, get empty selection range
	 		result=element.val().length;
		}
		// Opera adds 1 pos per line break, undo this
		if(navigator.userAgent.substring(0,5) == "Opera")
		{
			var splitme = element.val().substring(0,result);
			var split = splitme.split("\n");
			result=result-split.length+1;
		}
		
		return result;
	}
	
	/**
	 * helper for Caret
	 */		
	function setcaret(element,position)
	{
		element.focus();

		try
		{
       		element.caret({start:position,end:position});
		}
		catch(e){
		 //todo try to do something about IE
		}		
	}
	


	/**
	 * helper for BBCode
	 */	
	function insertOpenCloseTag(opentag, closetag)
	{
       	var sourcetext = $("#{JSID}edittext").val();
   		var caretstart =getcaretstart($("#{JSID}edittext"));
   		var caretend =getcaretend($("#{JSID}edittext"));
   		
       	var text = sourcetext.substring(0, caretstart);
       	var tag = opentag + sourcetext.substring(caretstart,caretend) + closetag;
       	text = text + tag;
       	text = text + sourcetext.substring(caretend);
       	$("#{JSID}edittext").val(text);
       	setcaret($("#{JSID}edittext"),caretend+opentag.length);
 	}
 	
 	
 	
 	/**
 	 * Get rid of curly quotes
 	 */
 	function cleanupquotes(text)
 	{
 		return text
  				.replace(/[\u2018\u2019]/g, "'")
  				.replace(/[\u201C\u201D]/g, '"');
 	}
	
	
	/**
	 * listeners for collapsed editor
	 */	
	function addlistenersCollapsed()
	{
		/* expandbutton */
		$("#{JSID}expandbutton").click(function() {
		
			var elements = new Array();
			elements[0] = $("#{JSID}expandbutton");
			disableElements(elements);
		
			$("#{JSID}status").html("Loading editor ...");
			
       		postRequest(
				projectroot+"admin/includes/ajax/editor/expandeditor.php",
       			{
					edittext: $("#{JSID}edittext").val(),
	       			page: $("#{JSID}page").val(),
	       			item: $("#{JSID}item").val(),
	       			title: $("#{JSID}title").val(),
	       			elementtype: $("#{JSID}elementtype").val()
	       		},
	       		function(html)
				{
					$("#{JSID}editorcontents").html(html); 
					addlistenersExpanded();
				},
				elements
	    	); // post 	
       		
       		$("#{JSID}status").html("");   
        			
		}); // expandbutton
	} // addlistenersCollapsed
	
	
	/**
	 * listeners for expanded editor
	 */	
	function addlistenersExpanded()
	{
		/* edittext */
		$("#{JSID}edittext").focus();
		// watch edit state so user won't forget to save
		$("#{JSID}edittext").on("keypress", function() {
			settextisedited();
		});	
	
		/************* BBCode buttons *************/
		/* bold */
		$("#{JSID}bold").click(function() {
			insertOpenCloseTag("[b]", "[/b]");
	       	settextisedited();
		});	//bold
		
		/* italic */
		$("#{JSID}italic").click(function() {
	       	insertOpenCloseTag("[i]", "[/i]");
	       	settextisedited();
		}); // italic
		
		/* underline */
		$("#{JSID}underline").click(function() {
	       	insertOpenCloseTag("[u]", "[/u]");
	       	settextisedited();
		}); // underline
		
		/* ul */
		$("#{JSID}ul").click(function() {
	       	var sourcetext = $("#{JSID}edittext").val();
	       	var caretstart =getcaretstart($("#{JSID}edittext"));
	       	var caretend =getcaretend($("#{JSID}edittext"));
	       	var text = sourcetext.substring(0, caretstart);
	       	text = text + "[list]";
	       	
	       	var splitme = sourcetext.substring(caretstart,caretend);
	    	var split = splitme.split("\n");
	    	for(var line in split)
	    	{
	    		text = text + "[*]"+split[line]+"\n";
	    	}
	       	text = text + "[/list]";
	       	text = text + sourcetext.substring(caretend);
			$("#{JSID}edittext").val(text);
			settextisedited();
			setcaret($("#{JSID}edittext"),caretstart+9);
			
		});	// ul
		
		/* ol */	
		$("#{JSID}ol").click(function() {
			var type=prompt("Please enter number style for the list","1");
			
	       	var sourcetext = $("#{JSID}edittext").val();
	       	var caretstart =getcaretstart($("#{JSID}edittext"));
	       	var caretend =getcaretend($("#{JSID}edittext"));
	       	var text = sourcetext.substring(0, caretstart);
	       	var opentag ="";
	       	
			if (type!=null && type!="")
		  	{
	       		opentag ="[list="+type+"]";
		  	}
	       	else
	       	{
	       		opentag ="[list=]";
	       	}
	
	       	text = text + opentag;
	       	
	       	var splitme = sourcetext.substring(caretstart,caretend);
	    	var split = splitme.split("\n");
	    	for(var line in split)
	    	{
	    		text = text + "[*]"+split[line]+"\n";
	    	}
	       	text = text + "[/list]";
	       	text = text + sourcetext.substring(caretend);
			$("#{JSID}edittext").val(text);
			settextisedited();
			setcaret($("#{JSID}edittext"),caretstart+opentag.length+3);
		}); // ol

		/* li */
		$("#{JSID}li").click(function() {
	       	insertOpenCloseTag("[*]", "");
	       	settextisedited();
		});	// li		

		/* img */
		$("#{JSID}img").click(function() {
	
			var link=prompt("Please enter the link to the image","http://");
			if (link!=null && link!="")
		  	{
				var caretstart =getcaretstart($("#{JSID}edittext"));
				var caretend =getcaretend($("#{JSID}edittext"));
		  		var sourcetext = $("#{JSID}edittext").val();
	       		var text = sourcetext.substring(0, caretstart);
	       		text = text + "[img]"+ link+"[/img]";
	       		text = text + sourcetext.substring(caretend);
	       		$("#{JSID}edittext").val(text);	
	       		//setcaret($("#{JSID}edittext"),caretend);
	       		setcaret($("#{JSID}edittext"),caretstart+link.length+11);
		  	}
		  	else
		  	{
	       		insertOpenCloseTag("[img]", "[/img]");
		  	}

		  	settextisedited();
		}); // img
	
		/* url */
		$("#{JSID}url").click(function() {
			var caretstart =getcaretstart($("#{JSID}edittext"));
	       	var caretend =getcaretend($("#{JSID}edittext"));
	       	if(caretstart<caretend)
	       	{
	       		var address=prompt("Please enter the URL address","http://");
				if (address!=null && address!="")
		  		{
		  			insertOpenCloseTag("[url="+address+"]", "[/url]");
		  		}
		  		else
		  		{
		  			insertOpenCloseTag("[url]", "[/url]");
		  		}
	       	}
	       	else
	       	{
				var address=prompt("Please enter the link address","http://");
				if (address!=null && address!="")
		  		{
		  			var name=prompt("Please enter link title to be displayed","Title");
					if (name!=null && name!="")
		  			{
		  				var sourcetext = $("#{JSID}edittext").val();
	       				var text = sourcetext.substring(0, caretstart);
	       				var tag ="[url="+address+"]"+ name+"[/url]"
	       				text = text + tag;
	       				text = text + sourcetext.substring(caretend);
		  				$("#{JSID}edittext").val(text);
		  				//setcaret($("#{JSID}edittext"),caretend+tag.length);
		  				setcaret($("#{JSID}edittext"),caretstart+address.length+name.length+12);
		  			}
		  			else
		  			{
		  				insertOpenCloseTag("[url="+address+"]", "[/url]");
		  			}
		  		}
		  		else
		  		{
		  			insertOpenCloseTag("[url]", "[/url]");
		  		}
		  	}
		  	settextisedited();
		});	// url
		

		/* table */
		$("#{JSID}table").click(function() {
			var caretstart =getcaretstart($("#{JSID}edittext"));
			var rows = "NaN";
			var promptlabel = "Table rows:";
			while (rows != null && isNaN(rows))
			{
				rows = prompt(promptlabel, "");
				if(isNaN(rows)) promptlabel = "'" + rows + "' is not a number! Please enter the number of rows for the table:";
			}
			if (rows != null && rows != "")
			{
				var cols = "NaN";
				var promptlabel = "Table columns:";
				while (cols != null && isNaN(cols))
				{
					cols = prompt(promptlabel, "");
					if(isNaN(cols)) promptlabel = "'" + cols + "' is not a number! Please enter the number of columns for the table:";
				}
				if (cols != null && cols != "")
				{
					var sourcetext = $("#{JSID}edittext").val();
					var text = sourcetext.substring(0, caretstart);
					var tag = "\n[table]\n\t[caption]CAPTION[/caption]\n\t[tr]";
					for(var i = 0; i < cols; i++)
					{
						tag += "\n\t\t[th]HEADER"+(i+1)+"[/th]";
					}
					tag += "\n\t[/tr]";
					for(i = 0; i < rows; i++)
					{
						tag += "\n\t[tr]";
						for(var j = 0; j < cols; j++)
						{
						tag += "\n\t\t[td]CELL"+(i+1)+(j+1)+"[/td]";
						}
						tag += "\n\t[/tr]";
					}
					tag += "\n[/table]\n";
					text = text + tag;
					text = text + sourcetext.substring(caretstart);
					$("#{JSID}edittext").val(text);
					setcaret($("#{JSID}edittext"),caretstart + 19);
				}
			}
			settextisedited();
		});	// table

		/* styleform */
		$("#{JSID}styleform").children().each(function(index) {
			$(this).on("click", function() {
				var caretend =getcaretend($("#{JSID}edittext"));
				
				if ($(this).attr("value")!=0)
			  	{
		       		insertOpenCloseTag("[style="+$(this).attr("value")+"]", "[/style]");
		       		settextisedited();
		       		$("#{JSID}hideeditorbutton").val("Discard Changes");
				  	// todo reset syleform
			  	}
      		});
		}); // styleform
					
		/************* Action buttons *************/
		
		var elements = new Array();
		elements[0] = $("#{JSID}previewbutton");
		elements[1] = $("#{JSID}savebutton");
		elements[2] = $("#{JSID}resetbutton");
		elements[3] = $("#{JSID}hideeditorbutton");
		elements[4] = $("#{JSID}bold");
		elements[5] = $("#{JSID}italic");
		elements[6] = $("#{JSID}underline");
		elements[7] = $("#{JSID}ul");
		elements[8] = $("#{JSID}ol");
		elements[9] = $("#{JSID}li");
		elements[10] = $("#{JSID}img");
		elements[11] = $("#{JSID}url");
		elements[12] = $("#{JSID}styleform");
		elements[13] = $("#{JSID}edittext");
							
		/* preview text */
		$("#{JSID}previewbutton").click(function() {

			disableElements(elements);
		
			$("#{JSID}status").html("Updating preview ....");
			var sourcetext = cleanupquotes($("#{JSID}edittext").val());
			$("#{JSID}edittext").val(sourcetext);
			
       		postRequest(
				projectroot+"admin/includes/ajax/editor/formatpreviewtext.php",
       			{
					previewtext: sourcetext
	       		},
	       		function(html)
				{
					$("#{JSID}previewarea").html(html);
	       			$("#{JSID}status").html("Preview updated");
	       			enableElements(elements);
	       			showmessage("Preview updated");
				},
				elements
	    	); // post formatpreviewtext.php
		}); // previewbutton
		
		/* save text and collapse if successful */
		$("#{JSID}savebutton").click(function() {
			disableElements(elements);
			$("#{JSID}status").html("Saving now ... ");
			$("#{JSID}previewarea").html("Fetching preview ...");
			showprogressbox("Saving Text ... ");
			
			var sourcetext = cleanupquotes($("#{JSID}edittext").val());
			$("#{JSID}edittext").val(sourcetext);
			
      		postRequest(
				projectroot+"admin/includes/ajax/editor/formatpreviewtext.php",
       			{
					previewtext: sourcetext
	       		},
	       		function(html)
				{
	       	
		       		$("#{JSID}previewarea").html(html);
		
					var savestatus="";
			       	
			       	$("#{JSID}status").html("Saving "+$("#{JSID}elementtype").val()+" ... ");
			       	
		       		postRequest(
	   					projectroot+"admin/includes/ajax/editor/savetext.php",
		       			{
	    					savetext: uni2ent(sourcetext),
			       			page: $("#{JSID}page").val(),
			       			item: $("#{JSID}item").val(),
			       			elementtype: $("#{JSID}elementtype").val()
			       		},
						function(xml)
	    				{
	    					showmessageXML(xml);
	    					var element=$(xml).find('message');
							var error = element.attr("error");
							if(error =="1")
							{
								alert(element.text());
				       			savestatus=savestatusfailedmessage();
				       			$("#{JSID}status").html(savestatus);
				       			alert(savestatus);
				       			enableElements(elements);
				       		}
				       		else
				       		{
				       			savestatus=element.text();
					       		settextisnotedited();
					       		
					       		postRequest(
				   					projectroot+"admin/includes/ajax/editor/collapseeditor.php",
					       			{
				    					page: $("#{JSID}page").val(),
						       			item: $("#{JSID}item").val(),
						       			title: $("#{JSID}title").val(),
						       			elementtype: $("#{JSID}elementtype").val()
						       		},
						       		function(html)
				    				{
				    					$("#{JSID}editorcontents").html(html); 
						       			addlistenersCollapsed();
						       			$("#{JSID}status").html(savestatus);
				    				},
				    				elements
						    	); // post collapseeditor
							} // else
						},
	    				elements
			    	); // post savetext.php		       		
				},
				elements
	    	); // post formatpreviewtext.php
		}); // savebutton
		
		/* reset edit status */
		$("#{JSID}resetbutton").click(function() {
       		settextisnotedited();
		});	// resetbutton
		
		/* collapse the editor */
		$("#{JSID}hideeditorbutton").click(function() {
			disableElements(elements);
			if(textisedited)
			{
				showmessage("Caution: Text has not been saved!");
	       		$("#{JSID}status").html("Hiding editor ... ");
	       		var sourcetext = cleanupquotes($("#{JSID}edittext").val());
				$("#{JSID}edittext").val(sourcetext);
	       		
				$("#{JSID}previewarea").html("Fetching preview ...");
				
	       		postRequest(
					projectroot+"admin/includes/ajax/editor/formatpreviewtext.php",
	       			{
						previewtext: sourcetext
		       		},
		       		function(html)
					{
						$("#{JSID}previewarea").html(html);
					},
					elements
		    	); // post formatpreviewtext.php
		    	
	       		postRequest(
					projectroot+"admin/includes/ajax/editor/editorcontentssavedialog.php",
	       			{
						page: $("#{JSID}page").val(),
		       			item: $("#{JSID}item").val(),
		       			edittext: $("#{JSID}edittext").val(),
		       			title: $("#{JSID}title").val(),
		       			elementtype: $("#{JSID}elementtype").val()
		       		},
		       		function(html)
					{
						$("#{JSID}editorcontents").html(html); 
		       			addlistenersSaveDialog();
					},
					elements
		    	); // post editorcontentssavedialog.php

       		
       			$("#{JSID}status").html("Are you sure you wish to discard your changes?");
	       	}
	       	else
	       	{
	       		$("#{JSID}status").html("Hiding editor ... ");
	       		
	       		postRequest(
					projectroot+"admin/includes/ajax/editor/collapseeditor.php",
	       			{
						page: $("#{JSID}page").val(),
		       			item: $("#{JSID}item").val(),
		       			title: $("#{JSID}title").val(),
		       			elementtype: $("#{JSID}elementtype").val()
		       		},
		       		function(html)
					{
						$("#{JSID}editorcontents").html(html); 
		       			addlistenersCollapsed();
		       			$("#{JSID}status").html("");
					},
					elements
		    	); // post collapseeditor.php
	       	}
		}); // hideeditorbutton

	} // addlistenersExpanded
	
	
	/**
	 * listeners when editor is closed without saving
	 */
	function addlistenersSaveDialog()
	{
			var elements = new Array();
			elements[0] = $("#{JSID}saveandcollapsebutton");
			elements[1] = $("#{JSID}expandeditedbutton");
			elements[2] = $("#{JSID}dismissbutton");

			
		/* save and collapse */
		$("#{JSID}saveandcollapsebutton").click(function() {
			disableElements(elements);
			$("#{JSID}status").html("Saving now ... ");
			var savestatus="";
			var sourcetext = cleanupquotes($("#{JSID}edittext").val());
			$("#{JSID}edittext").val(sourcetext);
			       	
	       	$("#{JSID}status").html("Saving "+$("#{JSID}elementtype").val()+" ... ");
	       	showprogressbox("Saving "+$("#{JSID}elementtype").val()+" ... ");
	       	
       		postRequest(
				projectroot+"admin/includes/ajax/editor/savetext.php",
       			{
					savetext: uni2ent(sourcetext),
	       			page: $("#{JSID}page").val(),
	       			item: $("#{JSID}item").val(),
	       			elementtype: $("#{JSID}elementtype").val()
	       		},
	       		function(html)
				{
		       		if(html=="error")
		       		{
		       			savestatus=savestatusfailedmessage();
		       			$("#{JSID}status").html(savestatus);
		       			alert(savestatus);
		       			showmessage(savestatus);
		       			
			       		postRequest(
							projectroot+"admin/includes/ajax/editor/expandeditor.php",
			       			{
								edittext: $("#{JSID}edittext").val(),
				       			page: $("#{JSID}page").val(),
				       			item: $("#{JSID}item").val(),
				       			title: $("#{JSID}title").val(),
				       			elementtype: $("#{JSID}elementtype").val()
				       		},
				       		function(html)
							{
								$("#{JSID}editorcontents").html(html);
				       			settextisedited();
								addlistenersExpanded();
							},
							elements
				    	); // post expandeditor.php
		       		}
		       		else
		       		{
		       			savestatus=html;
			       		settextisnotedited();
			       		showmessage(savestatus);
			       		
			       		postRequest(
							projectroot+"admin/includes/ajax/editor/collapseeditor.php",
			       			{
								page: $("#{JSID}page").val(),
				       			item: $("#{JSID}item").val(),
				       			title: $("#{JSID}title").val(),
				       			elementtype: $("#{JSID}elementtype").val()
				       		},
				       		function(html)
							{
								$("#{JSID}editorcontents").html(html); 
				       			addlistenersCollapsed();
					       		$("#{JSID}status").html(savestatus);
							},
							elements
				    	); // post collapseeditor.php
					} // else	       		
				},
				elements
	    	); // post savetext.php
		}); // saveandcollapsebutton
		
		/* discard changes */		
		$("#{JSID}dismissbutton").click(function() {
			disableElements(elements);
		
       		$("#{JSID}status").html("Hiding editor ... ");
       		showmessage("Changes discarded");
       		
       		// get text from database
       		$("#{JSID}previewarea").html("Fetching original text ...");
       		postRequest(
				projectroot+"admin/includes/ajax/editor/gettextfromdatabase.php",
       			{
					page: $("#{JSID}page").val(),
		       		item: $("#{JSID}item").val(),
		       		elementtype: $("#{JSID}elementtype").val()
	       		},
	       		function(html)
				{
					$("#{JSID}previewarea").html(html);
				},
				elements
	    	); // post gettextfromdatabase.php   
       		
       		postRequest(
				projectroot+"admin/includes/ajax/editor/collapseeditor.php",
       			{
					page: $("#{JSID}page").val(),
	       			item: $("#{JSID}item").val(),
	       			title: $("#{JSID}title").val(),
	       			elementtype: $("#{JSID}elementtype").val()
	       		},
	       		function(html)
				{
					$("#{JSID}editorcontents").html(html); 
	       			addlistenersCollapsed();
		       		$("#{JSID}status").html("");
				},
				elements
	    	); // post collapseeditor.php      		
       		
		}); // dismissbutton
	
		/* expand the editor */
		$("#{JSID}expandeditedbutton").click(function() {
			disableElements(elements);
		
			$("#{JSID}status").html("Loading editor ...");
			
			
       		postRequest(
				projectroot+"admin/includes/ajax/editor/expandeditor.php",
       			{
					edittext: $("#{JSID}edittext").val(),
	       			page: $("#{JSID}page").val(),
	       			item: $("#{JSID}item").val(),
	       			title: $("#{JSID}title").val(),
	       			elementtype: $("#{JSID}elementtype").val()
	       		},
	       		function(html)
				{
	       			$("#{JSID}editorcontents").html(html);
	       			settextisedited();
	       			addlistenersExpanded();
		       		$("#{JSID}status").html("");  
				},
				elements
	    	); // post expandeditor.php  

		}); // expandeditedbutton
	} // addlistenersSaveDialog
	
}); // document