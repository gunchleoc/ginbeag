// call this unicode-function when sending text with Ajax. Handles non-ASCII characters.
// http://www.codeproject.com/Articles/34481/Posting-Unicode-Characters-via-AJAX
function uni2ent(srcTxt) {
  var entTxt = '';
  var c, hi, lo;
  var len = 0;
  for (var i=0, code; code=srcTxt.charCodeAt(i); i++) {
    var rawChar = srcTxt.charAt(i);
    // needs to be an HTML entity
    if (code > 255) {
      // normally we encounter the High surrogate first
      if (0xD800 <= code && code <= 0xDBFF) {
        hi  = code;
        lo = srcTxt.charCodeAt(i+1);
        // the next line will bend your mind a bit
        code = ((hi - 0xD800) * 0x400) + (lo - 0xDC00) + 0x10000;
        i++; // we already got low surrogate, so don't grab it again
      }
      // what happens if we get the low surrogate first?
      else if (0xDC00 <= code && code <= 0xDFFF) {
        hi  = srcTxt.charCodeAt(i-1);
        lo = code;
        code = ((hi - 0xD800) * 0x400) + (lo - 0xDC00) + 0x10000;
      }
      // wrap it up as Hex entity
      c = "&#x"+ code.toString(16).toUpperCase() + ";";
    }
    else {
      c = rawChar;
    }
    entTxt += c;
    len++;
  }
  return entTxt;
}


// only used in admin, so can use admin to split string for getting the link path
function makeprojectroot()
{
	var linkpath=$(location).attr('href');
	return linkpath.substr(0,linkpath.indexOf("admin"));
}

var projectroot = makeprojectroot();

/**
 * Replacement function for $post that handles server errors
 * url: ajax server address
 * data: Array of post data to send to server
 * success: anonymous function: what to do on success
 * elements: css elements to be enabled after operation
 */
function postRequest(url, data, success, elements)
{

	$.ajax({
		url: url,
		data: data,
		type: 'post',
		error: function(XMLHttpRequest, textStatus, errorThrown)
		{
			var errormessage='Server Error: ' + XMLHttpRequest.status + ' - ' + XMLHttpRequest.statusText+' - URL: '+url;
			if (errormessage) alert(errormessage);
			showmessage(errormessage);
			enableElements(elements);
		},
		success: success
	});
}

/**
 * User feedback message from server
 */
function showmessage(message)
{
	$('#{JSID}messagebox').stop();
	$('#{JSID}progressbox').css("opacity",0);
	$('#{JSID}progressbox').css("height","0px");
	$('#{JSID}progressbox').css("width","0px");
	$("#{JSID}messagebox").html(message);
	placeOnBottom($('#{JSID}messagebox'));

	$('#{JSID}messagebox').animate({opacity: 1},0, function() {

		$('#{JSID}messagebox').delay(600).animate({
			opacity: 0
			}, 3000, function() {
			$('#{JSID}messagebox').css("width","0px");
			$('#{JSID}messagebox').css("height","0px");
			// Animation complete.
		}); // animate 2
	}); // animate 1
} // showmessage


/**
 * User feedback message from server
 * @xml = full XML returned from the server
 */
function showmessageXML(xml)
{
	var element=$(xml).find('message');
	var error = element.attr("error");
	if(error =="1")
	{
		alert(element.text());
	}
	showmessage(element.text());
} // showmessageXML


/**
 * User feedback message. Show before sending server request
 */
function showprogressbox(message)
{
	$("#{JSID}progressbox").html(message);
	placeOnBottom($('#{JSID}progressbox'));

	$('#{JSID}progressbox').animate({opacity: 1},0, function() {

	}); // animate 1

} // showprogressbox


// http://www.howtocreate.co.uk/tutorials/javascript/browserwindow
function placeOnBottom(element)
{
	element.css("position","fixed");
	element.css("width","100%");
	element.css("height","auto");
	element.css("left","0px");
	var height = element.css("height");
	// todo: why is heigt undefined with sitepolicy?
	if(height)
	{
		var temp = height.indexOf("px");
		height = height.substring(0, temp);
	}
	else height=0;

	var windowheight = document.body.clientHeight;
	var top = windowheight-height*2-30;

	// Internet Exploder
	if(element.css("position")=="static")
	{
		element.css("position","absolute");
		top = top+document.body.scrollTop;
	}

	top = top+"px";
	element.css("top",top);
}

/*
// http://www.howtocreate.co.uk/tutorials/javascript/browserwindow
function placeOnCenter(element)
{
	element.css("position","fixed");
	element.css("width","33%");
	element.css("height","auto");

	var heightunit = "px";
	var height = element.css("height");
	if(!height) height="33%";
	var temp = height.indexOf("px");
	if(temp<0) temp = height.indexOf("%");
	else if(temp<0) temp = height.length;
	else widthunit = "%";
	height = height.substring(0, temp);

	var windowheight = document.body.clientHeight;
	var top = (windowheight-height)/2;

	// Internet Exploder
	if(element.css("position")=="static")
	{
		element.css("position","absolute");
		top = top+document.body.scrollTop;
	}

	top = top+"px";
	element.css("top",top);

	var widthunit = "px";
	var width = element.css("width");
	if(!width) width="33%";
	var temp = width.indexOf("px");
	if(width<0)	temp = width.indexOf("%");
	else if(width<0) temp = width.length;
	else widthunit = "%";
	width = width.substring(0, temp);

	var windowwidth = document.body.clientWidth;
	var left = (windowwidth-width)/2;
	left = left+"px";

	element.css("left",left);
}
*/


/**
 * Reenable form elements after server response
 * @elements = Array
 */
function enableElements(elements)
{
	var number = elements.length;
	for(var i=0; i<number;i++)
	{
		elements[i].removeAttr('disabled');
	}
}

/**
 * Grey out form elements before sending server request
 * @elements = Array
 */
function disableElements(elements)
{
	var number = elements.length;
	for(var i=0; i<number;i++)
	{
		elements[i].attr('disabled', 'disabled');
	}
}
