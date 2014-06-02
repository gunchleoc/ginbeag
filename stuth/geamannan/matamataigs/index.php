<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"stuth"));

include_once($projectroot."includes/objects/page.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<meta name="keywords" content="Gaelic, Scottish-Gaelic, Scots Gaelic, Schottisch-Gälisch, Gàidhlig, Fòram, bòrd-brath, forum">
	<title>Fòram na Gàidhlig - Geama matamataigs</title>
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<link rel="stylesheet" href="../../../templates/fng/main.css" type="text/css">
	<script type="text/javascript" src="jquery.js"></script>
	<style type="text/css">
#messagebox {
	position: absolute;
	overflow: auto;
	z-index: 10;
	color:green;
	background-color:white;
	border:5px ridge blue;
	padding:10px;
	text-align:center;
	font-size:150%;
	padding-top:1em;
	padding-bottom:1em;
	opacity: 0;
}

table {
	border-style: none;
	margin: 0px;
	box-shadow: none;
	font-size: 120%;
}

caption {
	border-style: none;
	box-shadow: none;
}

th {
	border-style: none;
}

td {
	border-style: none;
	font-size: 110%;
}
</style>

<SCRIPT LANGUAGE="JavaScript">

function disableEnterKey(e)
 {
      var key;
     if(window.event)
           key = window.event.keyCode; //IE
      else
           key = e.which; //firefox

     return (key != 13);
 }



<!--START OF TIMER SCRIPT-->


//how much time they get
var time=120;
var timesup=0;
var started=0;
var theirpoints=0;

//document.math.answer.value="";
//document.math.points.value="0";



/*
 *
 */
function CountDown() {
	if(time>0)
	{
		document.getElementById("timer").innerHTML = time;
		time=time-1;
		var gameTimer=setTimeout("CountDown()", 1000);
	}
	else if (time==0)
	{
		document.getElementById("timer").innerHTML = "0";
		timesup=1;
		var pointsmessage="<br />Fhuair thu "+theirpoints;
		if(theirpoints == 1 || theirpoints == 11 || theirpoints == 2 || theirpoints == 12) pointsmessage=pointsmessage + " phuing";
		else {
			if((3 <= theirpoints && theirpoints <= 19 ) || (13 <= theirpoints && theirpoints <=19)) pointsmessage=pointsmessage + " puingean";
			else pointsmessage=pointsmessage + " puing";
		}
		showmessage('Dh\'fhalbh an ùine ort!'+pointsmessage);
		document.math.answer.value="";
		document.getElementById("operator").innerHTML = "Deireadh a' gheama";
		document.getElementById("firstnum").innerHTML = "";
		document.getElementById("secondnum").innerHTML = "";

	}
}
<!--END OF TIMER SCRIPT-->


function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}



$(document).ready(function() {

var mapnumbersold = new Array("a neoni", "a h-aon", "a dhà", "a trì", "a ceithir", "a còig", "a sia", "a seachd", "a h-ochd", "a naoi",
"a deich", "a h-aon deug", "a dhà dheug", "a trì deug", "a ceithir deug", "a còig deug", "a sia deug", "a seachd deug", "a h-ochd deug","a naoi deug",
"fichead", "a h-aon air fhichead", "a dhà air fhichead", "a trì air fhichead", "a ceithir air fhichead", "a còig air fhichead", "a sia air fhichead", "a seachd air fhichead", "a h-ochd air fhichead", "a naoi air fhichead",
"deich air fhichead", "a h-aon deug air fhichead", "a dhà dheug air fhichead", "a trì deug air fhichead", "a ceithir deug air fhichead", "a còig deug air fhichead", "a sia deug air fhichead", "a seachd deug air fhichead", "a h-ochd deug air fhichead", "a naoi deug air fhichead",
"dà fhichead", "dà fhichead is a h-aon", "dà fhichead is a dhà","dà fhichead is a trì", "dà fhichead is a ceithir","dà fhichead is a còig","dà fhichead is a sia","dà fhichead is a seachd","dà fhichead is a h-ochd","dà fhichead is a naoi",
"dà fhichead is a deich","dà fhichead is a h-aon deug", "dà fhichead is a dhà dheug", "dà fhichead is a trì deug", "dà fhichead is a ceithir deug", "dà fhichead is a còig deug", "dà fhichead is a sia deug", "dà fhichead is a seachd deug", "dà fhichead is a h-ochd deug","dà fhichead is a naoi deug",
"trì fichead", "trì fichead is a h-aon", "trì fichead is a dhà","trì fichead is a trì", "trì fichead is a ceithir","trì fichead is a còig","trì fichead is a sia","trì fichead is a seachd","trì fichead is a h-ochd","trì fichead is a naoi",
"trì fichead is a deich","trì fichead is a h-aon deug", "trì fichead is a dhà dheug", "trì fichead is a trì deug", "trì fichead is a ceithir deug", "trì fichead is a còig deug", "trì fichead is a sia deug", "trì fichead is a seachd deug","trì fichead is a h-ochd deug", "trì fichead is a naoi deug",
"ceithir fichead", "ceithir fichead is a h-aon", "ceithir fichead is a dhà","ceithir fichead is a trì", "ceithir fichead is a ceithir","ceithir fichead is a còig","ceithir fichead is a sia","ceithir fichead is a seachd","ceithir fichead is a h-ochd","ceithir fichead is a naoi",
"ceithir fichead is a deich", "ceithir fichead is a h-aon deug", "ceithir fichead is a dhà dheug", "ceithir fichead is a trì deug", "ceithir fichead is a ceithir deug", "ceithir fichead is a còig deug", "ceithir fichead is a sia deug", "ceithir fichead is a seachd deug", "ceithir fichead is a h-ochd deug", "ceithir fichead is a naoi deug",
"ceud");



var mapnumbersnew = new Array("a neoni", "a h-aon", "a dhà", "a trì", "a ceithir", "a còig", "a sia", "a seachd", "a h-ochd", "a naoi",
"a deich", "a h-aon deug", "a dhà dheug", "a trì deug", "a ceithir deug", "a còig deug", "a sia deug", "a seachd deug", "a h-ochd deug","a naoi deug",
"fichead", "fichead is a h-aon", "fichead is a dhà","fichead is a trì", "fichead is a ceithir","fichead is a còig","fichead is a sia","fichead is a seachd","fichead is a h-ochd","fichead is a naoi",
"trithead", "trithead is a h-aon", "trithead is a dhà","trithead is a trì", "trithead is a ceithir","trithead is a còig","trithead is a sia","trithead is a seachd","trithead is a h-ochd","trithead is a naoi",
"ceathrad", "ceathrad is a h-aon", "ceathrad is a dhà","ceathrad is a trì", "ceathrad is a ceithir","ceathrad is a còig","ceathrad is a sia","ceathrad is a seachd","ceathrad is a h-ochd","ceathrad is a naoi",
"caogad", "caogad is a h-aon", "caogad is a dhà","caogad is a trì", "caogad is a ceithir","caogad is a còig","caogad is a sia","caogad is a seachd","caogad is a h-ochd","caogad is a naoi",
"seasgad", "seasgad is a h-aon", "seasgad is a dhà","seasgad is a trì", "seasgad is a ceithir","seasgad is a còig","seasgad is a sia","seasgad is a seachd","seasgad is a h-ochd","seasgad is a naoi",
"seachdad", "seachdad is a h-aon", "seachdad is a dhà","seachdad is a trì", "seachdad is a ceithir","seachdad is a còig","seachdad is a sia","seachdad is a seachd","seachdad is a h-ochd","seachdad is a naoi",
"ochdad", "ochdad is a h-aon", "ochdad is a dhà","ochdad is a trì", "ochdad is a ceithir","ochdad is a còig","ochdad is a sia","ochdad is a seachd","ochdad is a h-ochd","ochdad is a naoi",
"naochad", "naochad is a h-aon", "naochad is a dhà","naochad is a trì", "naochad is a ceithir","naochad is a còig","naochad is a sia","naochad is a seachd","naochad is a h-ochd","naochad is a naoi",
"ceud");


var mapnumbers = mapnumbersold;


document.getElementById("togglesystem").value = "Cleachd na h-àireamhan ùra";
document.getElementById("numsystem").firstChild.nodeValue = "seann nòs";



/*
// mapping test
for (var i=0; i<=100; i++)
{
	showmessage(""+i+": "+mapnumbers[i]);
}
*/




/*
 *
 */
function startgame()
{
	document.math.answer.value="";
	document.getElementById("points").innerHTML="0";
	theirpoints=0;
	started=0;
	time=120;
	timesup=0;
	CountDown();
	started=1;
	getProb();
	showmessage("Siuthad! Dè nì e?");
}

<!--START OF RANDOM NUMBER SCRIPT-->
/*
 *
 */
function randnum(min,max)
{
	var num=Math.round(Math.random()*(max-min))+min;
	return num;
}
<!--END OF RANDOM NUMBER SCRIPT-->

var choose, rightanswer

/*
 *
 */
function getProb()
{
	var choose1=0;
	var choose2=0;

	choose=randnum(1,4);
	if (choose=="1")
	{
		document.getElementById("operator").innerHTML = " cuir ris ";

		choose1=randnum(0,50);
		choose2=randnum(0,50);

		rightanswer=choose1 + choose2;
	}
	if (choose=="2")
	{
		document.getElementById("operator").innerHTML = " thoirt air falbh ";
		choose2=randnum(0,50);
		choose1=randnum(choose2,50);

		rightanswer=choose1 -  choose2;
	}
	if (choose=="3")
	{
		document.getElementById("operator").innerHTML = " iomadaich le ";
		choose1=randnum(0,10);
		choose2=randnum(0,10);
		rightanswer=choose1 * choose2;
	}
	if (choose=="4")
	{
		document.getElementById("operator").innerHTML = " roinn le ";
		choose2=randnum(1,10);
		choose1=choose2 * randnum(0,10);

		rightanswer=choose1 /  choose2;
	}
	document.getElementById("firstnum").innerHTML = mapnumbers[choose1]+" ";
	document.getElementById("secondnum").innerHTML = " "+mapnumbers[choose2];
}

/*
 *
 */
function answerit()
{
	if (started==0)
	{
		showmessage('Feumaidh tu briogadh air a\' phùtan "Tòisich air geama ùr"!');
	}
	else
	{
		if (timesup!=0)
		{
			showmessage('Tha an ùine seachad!');
		}
		else
		{
			var theiranswer=trim(document.math.answer.value);
			theirpoints=eval(trim(document.getElementById("points").innerHTML));
			if (theiranswer==null || theiranswer=="")
			{
				showmessage('Cuir an fhreagairt agad sa bhogsa air barr a\' phutain "Cuir an fhreagairt a-null"!');
				document.math.answer.select();
			}
			else
			{
				if (theiranswer==rightanswer)
				{
					showmessage('Tha sin ceart!');
					theirpoints++;
					document.getElementById("points").innerHTML=theirpoints;
				}
				else if (theiranswer==mapnumbers[rightanswer])
				{
					showmessage("'S math a rinn thu!");
					theirpoints=theirpoints+2;
					document.getElementById("points").innerHTML=theirpoints;
				}

				else
				{
					showmessage("Tha \""+ theiranswer + "\" cearr!\n\n\Is \""+rightanswer + "\" no \""+mapnumbers[rightanswer]+"\" an fhreagairt ceart!")
				}
				document.math.answer.select();
				getProb();
			}
		}
	}
	document.math.answer.value="";

	document.math.answer.focus();
}



/*
 *
 */
function togglesystem()
{
	if(mapnumbers == mapnumbersnew)
	{
		mapnumbers = mapnumbersold;
		document.getElementById("togglesystem").value = "Cleachd na h-àireamhan ùra";
		document.getElementById("numsystem").firstChild.nodeValue = "seann nòs";
		showmessage("Bidh an ath àireamh san t-seann nòs.");
	}
	else
	{
		mapnumbers = mapnumbersnew;
		document.getElementById("togglesystem").value = "Cleachd na seann àireamhan";
		document.getElementById("numsystem").firstChild.nodeValue = "nòs ùr";
		showmessage("Bidh an ath àireamh san nòs ùr.");
	}
}


$("#start").click(function(e){
	startgame();
	document.math.answer.focus();

});


$("#answerit").click(function(e){
	answerit();
});


$("#answer").keyup(function(e) {
	if(e.keyCode == 13) {
		var test=trim(document.math.answer.value);
		if(test.length>0)
		{
			jQuery(this).blur();
			jQuery('#submit').focus().click();
			answerit();
		}
	}
});


$("#togglesystem").click(function(e){
	togglesystem();
});







});



// special treatment for IE
if(navigator.appName =="Microsoft Internet Explorer")
{
	document.write('<link rel="stylesheet" type="text/css" href="templates/ie.css">');
}

/**
 * User feedback message from server
 */
function showmessage(message)
{
	$('#messagebox').stop();
	$("#messagebox").html(message);
	placeOnBottom($('#messagebox'));

	$('#messagebox').animate({opacity: 1},0, function() {

		$('#messagebox').delay(600).animate({
			opacity: 0
			}, 4000, function() {
			$('#messagebox').css("width","0px");
			$('#messagebox').css("height","0px");
			// Animation complete.
		}); // animate 2
	}); // animate 1
} // showmessage


// http://www.howtocreate.co.uk/tutorials/javascript/browserwindow
function placeOnBottom(element)
{
	element.css("position","fixed");
	element.css("width","70%");
	element.css("height","auto");
	element.css("right","0px");
	element.css("margin-right","25px");
	var height = element.css("height");
	if(height)
	{
		var temp = height.indexOf("px");
		height = height.substring(0, temp);
	}
	else height=0;

	var windowheight = document.body.clientHeight;
	var top = windowheight-height*2-10;

	// Internet Exploder
	if(element.css("position")=="static")
	{
		element.css("position","absolute");
		top = top+document.body.scrollTop;
	}
	top = top-25;

	top = top+"px";
	element.css("top",top);
}


$(document).ready(function() {
	// height for pages where content is shorter than navigator
	var navheight = $("#navigator").outerHeight(true);
	var bannerheight = $("#banners").outerHeight(true);
	var highestheader = $("#headerleft");

	if ($("#headerright").outerHeight(true) > highestheader.outerHeight(true))
	{
		highestheader = $("#headerright");
	}
	if ($("#headercenter").outerHeight(true) > highestheader.outerHeight(true))
	{
		highestheader = $("#headercenter");
	}
	var headerheight = highestheader.outerHeight(true);
	var titleheight = $("#headerpagetitle").outerHeight(true);
	var wrapperheight = Math.ceil(navheight+bannerheight+headerheight+titleheight);
	if ($("#wrapper").height() < wrapperheight)
	{
		$("#wrapper").height(wrapperheight);
		var difference = $("#wrapper").outerHeight() - $("#wrapper").height();
		//var difference = $("#wrapper").css("margin-top")+ $("#wrapper").css("margin-bottom")+ $("#wrapper").css("padding-top")+$("#wrapper").css("padding-bottom");
		var margin = $("#contentarea").css("margin-bottom").replace("px","") + $("#contentarea").css("margin-top").replace("px","");
		$("#contentarea").height(Math.ceil(navheight+bannerheight-difference-margin));
	}

});
	</script>

</head>
<body>
	<div id="wrapper">
		<div id="headerleft">
	        <a href="http://www.foramnagaidhlig.net/">
		        <img src="../../../img/fnglogo_cearn.gif" border="0" alt="Fòram na Gàidhlig" vspace="1" />
	        </a>
		</div>
		<div id="headercenter">
		    <h1 class="maintitle">Fòram na Gàidhlig</h1>

		    <div id="sitedescription">Coimhearsneachd airson ionnsachadh is leasachadh na Gàidhlig</div>

		</div>
		<div id="headerright">


		</div>
		<h1 id="headerpagetitle" class="headerpagetitle newline">Geama Matamataigs</h1>

		<div class="invisible"><a href="#contentarea" accesskey="n" class="invisible">Skip navigation</a></div>

		<div id="navigator" title="Clàr-taice">
<?php
$navigator = new Navigator(38,false,1,false,false);
print($navigator->toHTML());
if(getproperty('Display Banners'))
{
  $banners=new BannerList();
  print($banners->toHTML());
}
$db->closedb();
?>
		</div>
		<div id="contentarea" style="height: 950px;" title="Susbaint">
<!--  Game HTML starts here  -->
				<h2 class="pagetitle">Geama Matamataigs</h2>

<table align="center" cellpadding="5">
<form name="math" >
	<tr>
  		<td align="left" rowspan="6"><img src="matamataigslogo.png" /></td>

    </tr>
    <tr>
      	<td align="left"><span style="font-family: Arial, Helvetica, Sans Serif; font-size: 100%; font-weight:bold; color:#000000">Fuasgail: </span></td>
      	<td colspan="2">
      		<font face="Arial, Helvetica, Sans Serif">
      			<span name="firstnum" id="firstnum" style="font-weight:bold;"> </span>
      			<span name="operator" id="operator"> </span>
      			<span name="secondnum" id="secondnum" style="font-weight:bold;"> </span>
      		</font>
      	</td>
    </tr>
    <tr>
      	<td align="left" valign="top"><span style="font-family: Arial, Helvetica, Sans Serif; font-size: 100%; font-weight:bold; color:#000000">A'&nbsp;dèanamh:</span></td>
      	<td colspan="2" valign="top"><input type="text" ID="answer" name="answer" size="40" style="font-size: 100%;" onKeyPress="return disableEnterKey(event)"> <input type="button" id="answerit" name="answerit" value="Cuir an fhreagairt a-null" style="font-size: 90%;"></td>
    </tr>
    <tr>
		<td align="left"><span style="font-family: Arial, Helvetica, Sans Serif; font-size: 100%; font-weight:bold; color:green;">Puingean:</span></td>
		<td><span name="points" id="points" style="font-weight:bold; color:green;"> </span></td>
		<td align="right"><font face="Arial, Helvetica, Sans Serif">Ùine air fhàgail: </font><span name="timer" id="timer" style="font-weight:bold;"> </span></td>
    </tr>
</form>
	<tr>
    	<td align="left" colspan="3">
    		<p style="font-family: Arial, Helvetica, Sans Serif; font-size: 75%;">Gheibh thu puing do gach freagairt cheart ma chleachdas tu àireamh.
			<br />Gheibh thu dà phuing do gach freagairt cheart ma sgrìobhas tu ainm na h-àireimh.</p>
      	</td>
	</tr>
	<tr>
		<td>
			<font face="Arial, Helvetica, Sans Serif"><span style="font-weight:bold;">Àireamhan:</span></font>
      	</td>
      	<td>
			<font face="Arial, Helvetica, Sans Serif"><span name="numsystem" id="numsystem" style="font-weight:bold;">seann nòs</span></font>
      	</td>
      	<td align="right">
    		<input type="button" id="togglesystem" name="togglesystem" value="Cleachd na h-àireamhan ùra" style="font-size: 80%;">
    	</td>
	</tr>
	<tr>
    	<td align="center">
    		<input type="button" id="start" name="start" value="Tòisich air geama ùr" style="font-size: 90%;">
		</td>
		<td colspan="3">&nbsp;</td>
  	</tr>
</table>
<div id="messagebox"></div>
<br /><hr>
<p>Tha an geama seo stèidhichte air JavaScript le <a href="http://javascriptsource.com">The JavaScript Source</a>.</p>
<p>Cleachdaidh an geama seo JavaScript.</p>

<!-- End game HTML -->
</div>
	<div class="footer newline"></div>
	</div>
</body>
</html>
