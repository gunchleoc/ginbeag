$(document).ready(function() {

	var elements = new Array();
	elements[0] = $("input[id*=movetop]");
	elements[1] = $("input[id*=movebottom]");
	elements[2] = $("input[id*=moveup]");
	elements[3] = $("input[id*=movedown]");
	elements[4] = $("input[id*=positions]");
	
	// movetop
	$("#{JSID}movetop").click(function() {
	
		disableElements(elements);

		showprogressbox("Moving Page "+$("#{JSID}moveid").val()+" to the Top ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/menus/movepage.php",
			{
				sid: $("#{JSID}sid").val(),
	       		page: $("#{JSID}page").val(),
	       		moveid: $("#{JSID}moveid").val(),
	       		positions: $("#{JSID}positions").val(),
	       		movetop: "movetop"
	   		},
	   		function(xml)
			{
				postRequest(
					projectroot+"admin/includes/ajax/menus/updatesubpages.php",
					{
						sid: $("#{JSID}sid").val(),
			       		page: $("#{JSID}page").val()
			   		},
			   		function(html)
					{
						$("#movepageform").html(html);
					},
					elements
				); // post 
			
				showmessageXML(xml);
			},
			elements
		); // post 
		
	}); // movetop
		

	// moveup
	$("#{JSID}moveup").click(function() {
	
		disableElements(elements);

		showprogressbox("Moving Page "+$("#{JSID}moveid").val()+" Up ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/menus/movepage.php",
			{
				sid: $("#{JSID}sid").val(),
	       		page: $("#{JSID}page").val(),
	       		moveid: $("#{JSID}moveid").val(),
	       		positions: $("#{JSID}positions").val(),
	       		moveup: "moveup"
	   		},
	   		function(xml)
			{
				postRequest(
					projectroot+"admin/includes/ajax/menus/updatesubpages.php",
					{
						sid: $("#{JSID}sid").val(),
			       		page: $("#{JSID}page").val()
			   		},
			   		function(html)
					{
						$("#movepageform").html(html);
					},
					elements
				); // post 
			
				showmessageXML(xml);
			},
			elements
		); // post 
		
	}); // moveup
		

	// movedown
	$("#{JSID}movedown").click(function() {
	
		disableElements(elements);

		showprogressbox("Moving Page "+$("#{JSID}moveid").val()+" Down ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/menus/movepage.php",
			{
				sid: $("#{JSID}sid").val(),
	       		page: $("#{JSID}page").val(),
	       		moveid: $("#{JSID}moveid").val(),
	       		positions: $("#{JSID}positions").val(),
	       		movedown: "movedown"
	   		},
	   		function(xml)
			{
				postRequest(
					projectroot+"admin/includes/ajax/menus/updatesubpages.php",
					{
						sid: $("#{JSID}sid").val(),
			       		page: $("#{JSID}page").val()
			   		},
			   		function(html)
					{
						$("#movepageform").html(html);
					},
					elements
				); // post 
			
				showmessageXML(xml);
			},
			elements
		); // post 
		
	}); // movedown

	// movebottom
	$("#{JSID}movebottom").click(function() {
	
		disableElements(elements);

		showprogressbox("Moving Page "+$("#{JSID}moveid").val()+" to the Bottom ...");
		
		postRequest(
			projectroot+"admin/includes/ajax/menus/movepage.php",
			{
				sid: $("#{JSID}sid").val(),
	       		page: $("#{JSID}page").val(),
	       		moveid: $("#{JSID}moveid").val(),
	       		positions: $("#{JSID}positions").val(),
	       		movebottom: "movebottom"
	   		},
	   		function(xml)
			{
				postRequest(
					projectroot+"admin/includes/ajax/menus/updatesubpages.php",
					{
						sid: $("#{JSID}sid").val(),
			       		page: $("#{JSID}page").val()
			   		},
			   		function(html)
					{
						$("#movepageform").html(html);
					},
					elements
				); // post 
			
				showmessageXML(xml);
			},
			elements
		); // post 
		
	}); // movebottom


}); // document