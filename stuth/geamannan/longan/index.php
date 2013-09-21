<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"stuth"));

include_once($projectroot."includes/objects/page.php");
?>
<?xml version="1.1" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<meta name="keywords" content="Gaelic, Scottish-Gaelic, Scots Gaelic, Schottisch-Gälisch, Gàidhlig, Fòram, bòrd-brath, forum">
	<title>Fòram na Gàidhlig - Longan-cogaidh</title>
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<link href="longan.css"	rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../../../templates/fng/main.css" type="text/css">
	<script language="JavaScript">

// special treatment for IE	
if(navigator.appName =="Microsoft Internet Explorer")
{
	document.write('<link rel="stylesheet" type="text/css" href="templates/ie.css">');
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
		<h1 id="headerpagetitle" class="headerpagetitle newline">Longan-cogaidh</h1>

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
				<h2 class="pagetitle">Longan-cogaidh</h2>


<SCRIPT LANGUAGE="JavaScript">
<!-- Original:  Jason Hotchkiss (jasonhotchkiss@home.com) -->
<!-- Web Site:  http://www.members.home.com/jasonhotchkiss -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin


/* Information used to draw the ships
*/
var ship =  [[[1,5], [1,2,5], [1,2,3,5], [1,2,3,4,5]], [[6,10], [6,7,10], [6,7,8,10], [6,7,8,9,10]]];

/* Information used to draw sunk ships
*/
var dead = [[[201,203], [201,202,203], [201,202,202,203], [201,202,202,202,203]], [[204,206], [204,205,206], [204,205,205,206], [204,205,205,205,206]]];

/* Information used to describe ships
*/
var shiptypes = [["Eathar-mèin",2,4],["Friogad",3,4],[ "Crùsair",4,2],[ "Long-chogaidh",5,1]];

var gridx = 16, gridy = 16;
var player = [], computer = [], playersships = [], computersships = [];
var playerlives = 0, computerlives = 0, playflag=true, statusmsg="";

/* random messages
*/

function missedMessage() {
	var missedmessages = 
	[
 		"Chaidh an urchair againn dhan uisge", 
 		"Tha na gunnachan againn air fàs meirgeach", 
 		"Cha do rinn sinn bualadh air dad", 
 		"Chaill sinn an t-amas", 
 		"Cha tàinig sinn faisg air an nàmhaid", 
 		"Tha an criutha a' call an dùr-aire", 
 		"Tha na gealtairean am falach fhathast",
 		"Chaidh an losgadh seachad air an targaid", 
 		"Bha agam ris an sairdseant gunnaireachd ìsleachadh", 
 		"Bha an droch-shealbh leinn", 
 		"Cha deach leinn bualadh air an nàbhaid", 
 		"Tha eòlaiche airm-tilgidh a dhìth oirnn", 
 		"Cha robh Dia leinn"
	];
	
	return missedmessages[Math.floor(Math.random()*missedmessages.length)];
}

function missedMessageComp() {
	var missedmessages = 
	[
 		"Chuala sinn spreadhadh", 
 		"Chunnaic sinn losgadh gunna-mhòir", 
 		"Chaidh peilear dhan uisge faisg oirnn", 
 		"Chunnaic sinn fuaran àrd", 
 		"Cha do mharbh an nàmhaid ach èisg", 
 		"Rinn an nàmhaid toll ùr sa ghrunnd", 
 		"Cha tàinig oirnn ach fliuchadh", 
 		"Rinn an nàmhaid oidhirp eile",
 		"Cha do shoirbhich leis an nàbhaid",
 		"Cha do dh'èirich gu math dhan nàmhaid",
 		"Fhuair sinn lasachadh greis",
 		"Tha an nàmhaid cho dall ri dallaig",
 		"Bhuail an nàmhaid air teas-mheadhain a' chuain"
	];
	
	return missedmessages[Math.floor(Math.random()*missedmessages.length)];
}




/* Function to preload all the images, to prevent delays during play
*/
var preloaded = [];
function imagePreload() {
	var i,ids = [1,2,3,4,5,6,7,8,9,10,100,101,102,103,201,202,203,204,205,206];
	window.status = "A' luchdadh dhealbhan...fuirich diog";
	var imgCuan = new Image, name="cuan.png";
	for (i=0;i<ids.length;++i) {
		var img = new Image, name = "batt"+ids[i]+".gif";
		img.src = name;
		preloaded[i] = img;
	}
	window.status = "";
}

/* Function to place the ships in the grid
*/
function setupPlayer(ispc) {
	var y,x;
	grid = [];
	for (y=0;y<gridx;++y) {
		grid[y] = [];
		for (x=0;x<gridx;++x)
			grid[y][x] = [101,-1,0];
	}

	var shipno = 0;
	var s;
	for (s=shiptypes.length-1;s>=0;--s) {
		var i;
		for (i=0;i<shiptypes[s][2];++i) {
			var d = Math.floor(Math.random()*2);
			var len = shiptypes[s][1], lx=gridx, ly=gridy, dx=0, dy=0;
			if ( d==0) {
				lx = gridx-len;
				dx=1;
			}
			else {
				ly = gridy-len;
				dy=1;
			}
			var x,y,ok;
			do {
				y = Math.floor(Math.random()*ly);
				x = Math.floor(Math.random()*lx);
				var j,cx=x,cy=y;
				ok = true;
				for (j=0;j<len;++j) {
					if (grid[cy][cx][0] < 101) {
						ok=false;
						break;
					}
					cx+=dx;
					cy+=dy;
   				}
			} while(!ok);
			var j,cx=x,cy=y;
			for (j=0;j<len;++j) {
				grid[cy][cx][0] = ship[d][s][j];
				grid[cy][cx][1] = shipno;
				grid[cy][cx][2] = dead[d][s][j];
				cx+=dx;
				cy+=dy;
			}
			if (ispc) {
				computersships[shipno] = [s,shiptypes[s][1]];
				computerlives++;
			}
			else {
				playersships[shipno] = [s,shiptypes[s][1]];
				playerlives++;
			}
			shipno++;
   		}
	}
	return grid;
}

/* Function to change an image shown on a grid
*/
function setImage(y,x,id,ispc) {
	if ( ispc ) {
		computer[y][x][0] = id;
		document.images["pc"+y+"_"+x].src = "batt"+id+".gif";
	}
	else {
		player[y][x][0] = id;
		document.images["ply"+y+"_"+x].src = "batt"+id+".gif";
	}
}

/* Function to insert HTML source for a grid
*/
function showGrid(ispc) {
	var y,x;
	for (y=0;y<gridy;++y) {
		for (x=0;x<gridx;++x) {
			if ( ispc )
				document.write ('<a href="javascript:gridClick('+y+','+x+');"><img name="pc'+y+'_'+x+'" src="batt100.gif" width=16 height=16 class="leac"></a>');
			else
				document.write ('<a href="javascript:void(0);"><img name="ply'+y+'_'+x+'" src="batt'+player[y][x][0]+'.gif" width=16 height=16 class="leac"></a>');
		}
		document.write('<br>');
	}
}

/* Handler for clicking on the grid
*/
function gridClick(y,x) {
	if ( playflag ) {
		if (computer[y][x][0] < 101) {
			setImage(y,x,103,true);
			document.getElementById("log").innerHTML=getDateTimeString()+" <span style='color:lime'>Bhuail sinn air an nàmhaid!</span>"+"</br>"+document.getElementById("log").innerHTML;
			var shipno = computer[y][x][1];
				if ( --computersships[shipno][1] == 0 ) {
					sinkShip(computer,shipno,true);
					document.getElementById("log").innerHTML=getDateTimeString()+" <span style='color: green;'>Chuir sinn "+shiptypes[computersships[shipno][0]][0]+" fodha!"+"</span></br>"+document.getElementById("log").innerHTML;
					updateStatus();
					if ( --computerlives == 0 ) {
						document.getElementById("log").innerHTML=getDateTimeString()+" <b><span style='color:green'>Rinn sinn a' chùis air an nàmhaid!</b></span>"+"</br>"+document.getElementById("log").innerHTML;
						alert("Bhuannaich thu!");
						playflag = false;
					}
				}
				if ( playflag ) computerMove();
			}
			else if (computer[y][x][0] == 100 || computer[y][x][0] == 101) {
				setImage(y,x,102,true);
				document.getElementById("log").innerHTML=getDateTimeString()+" "+missedMessage()+"</br>"+document.getElementById("log").innerHTML;
				computerMove();
		}
	}
}

/* Function to make the computers move. Note that the computer does not cheat, oh no!
*/
function computerMove() {
	var x,y,pass;
	var sx,sy;
	var selected = false;

	/* Make two passes during 'shoot to kill' mode
	*/
	for (pass=0;pass<2;++pass) {
		for (y=0;y<gridy && !selected;++y) {
			for (x=0;x<gridx && !selected;++x) {
				/* Explosion shown at this position
				*/
				if (player[y][x][0]==103) {
					sx=x; sy=y;
					var nup=(y>0 && player[y-1][x][0]<=101);
					var ndn=(y<gridy-1 && player[y+1][x][0]<=101);
					var nlt=(x>0 && player[y][x-1][0]<=101);
					var nrt=(x<gridx-1 && player[y][x+1][0]<=101);
					if ( pass == 0 ) {
						/* On first pass look for two explosions
   						   in a row - next shot will be inline
						*/
						var yup=(y>0 && player[y-1][x][0]==103);
						var ydn=(y<gridy-1 && player[y+1][x][0]==103);
						var ylt=(x>0 && player[y][x-1][0]==103);
						var yrt=(x<gridx-1 && player[y][x+1][0]==103);
						if ( nlt && yrt) { sx = x-1; selected=true; }
						else if ( nrt && ylt) { sx = x+1; selected=true; }
						else if ( nup && ydn) { sy = y-1; selected=true; }
						else if ( ndn && yup) { sy = y+1; selected=true; }
					}
					else {
						/* Second pass look for single explosion -
						   fire shots all around it
						*/
						if ( nlt ) { sx=x-1; selected=true; }
						else if ( nrt ) { sx=x+1; selected=true; }
						else if ( nup ) { sy=y-1; selected=true; }
						else if ( ndn ) { sy=y+1; selected=true; }
					}
				}
			}
		}
	}
	if ( !selected ) {
		/* Nothing found in 'shoot to kill' mode, so we're just taking
		   potshots. Random shots are in a chequerboard pattern for
		   maximum efficiency, and never twice in the same place
		*/
		do{
			sy = Math.floor(Math.random() * gridy);
			sx = Math.floor(Math.random() * gridx/2)*2+sy%2;
		} while( player[sy][sx][0]>101 );
	}
	if (player[sy][sx][0] < 101) {
		/* Hit something
		*/
		setImage(sy,sx,103,false);
		document.getElementById("log").innerHTML=getDateTimeString()+" <span style='color:magenta'>Bhuail an nàmhaid oirnn!</span>"+"</br>"+document.getElementById("log").innerHTML;
		var shipno = player[sy][sx][1];
		if ( --playersships[shipno][1] == 0 ) {
			sinkShip(player,shipno,false);
			document.getElementById("log").innerHTML=getDateTimeString()+" <span style='color:crimson;'>Chaidh "+shiptypes[playersships[shipno][0]][0]+" chun a' ghruinnd!"+"</span></br>"+document.getElementById("log").innerHTML;
			if ( --playerlives == 0 ) {
				knowYourEnemy();
				document.getElementById("log").innerHTML=getDateTimeString()+" <span style='color:crimson'><b>Chaill sinn an cabhlach againn!</b></span>"+"</br>"+document.getElementById("log").innerHTML;
				alert("Bhuannaich a' choimpiutair!");
				playflag = false;
			}
		}
	}
	else {
		/* Missed
		*/
		setImage(sy,sx,102,false);
		document.getElementById("log").innerHTML=getDateTimeString()+" "+missedMessageComp()+"</br>"+document.getElementById("log").innerHTML;
	}
}

/* When whole ship is hit, show it using changed graphics
*/
function sinkShip(grid,shipno,ispc) {
	var y,x;
	for (y=0;y<gridx;++y) {
		for (x=0;x<gridx;++x) {
			if ( grid[y][x][1] == shipno )
				if (ispc) setImage(y,x,computer[y][x][2],true);
				else setImage(y,x,player[y][x][2],false);
		}
	}
}

/* Show location of all the computers ships - when player has lost
*/
function knowYourEnemy() {
	var y,x;
	for (y=0;y<gridx;++y) {
		for (x=0;x<gridx;++x) {
			if ( computer[y][x][0] < 101 )
				setImage(y,x,computer[y][x][0],true);
		}
	}
}

/* Show how many ships computer has left
*/
function updateStatus() {
	var f=false,i,s = "";
	for (i=0;i<computersships.length;++i) {
		if (computersships[i][1] > 0) {
			if (f) s=s+"<br>"; else f=true;
			s = s + shiptypes[computersships[i][0]][0];
		}
	}
	if (!f) s = s + "Chuir thu iad uile fodha!";
	statusmsg = s;
	document.getElementById("longan").innerHTML=s;
}

/* user ends game
*/
function endGame() {
	knowYourEnemy();
	playflag = false;
	document.getElementById("log").innerHTML=getDateTimeString()+" <span style='color:red'><b>Ghèill sinn dhan nànhaid!</b></span>"+"</br>"+document.getElementById("log").innerHTML;
}

/* return current date/time as a string
*/
function getDateTimeString() {
	var currentDate = new Date();
	var day = currentDate.getDate();
	var month = currentDate.getMonth() + 1;
	var year = currentDate.getFullYear();
	var hours = currentDate.getHours();
	var minutes = currentDate.getMinutes();
	var seconds = currentDate.getSeconds();

	if (minutes < 10)
		minutes = "0" + minutes;
  
	if (seconds < 10)
		seconds = "0" + seconds;

	return "<hr><i>" + day + "/" + month + "/" + year  +" " + hours + ":" + minutes + ":" + seconds + " " + "</i><br>";
}

//document.write("test"+getDateTimeString());

/* Start the game!
*/
imagePreload();

player = setupPlayer(false);
computer = setupPlayer(true);
document.getElementById("cuan").style.backgroundimage=imgCuan.src;
//  End -->
</script>


<div align="center">
	<table>
		<tr>
			<td valign="top" align="left">
<p class="highlight">Air fhàgail</p>
<div id="longan" class="status"></div>
<input type="button" id="resolve" name="resolve" value="Fuasgail an geama" onclick="javascript:endGame()" style="font-size: 70%;">
			</td>
			<td id="cuan" align="center" class="cuan" width="638" height="479">
				<table cellpadding='5' border='0'>
					<tr>
						<td align=center>
							<p class='heading'>Cabhlach a' choimpiutair</p>
						</td>
						<td align=center>
							<p class='heading'>An cabhlach agadsa</p>
						</td>
					</tr>
					<tr>
						<td>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
showGrid(true);
document.write("</td><td>");
showGrid(false);

//  End -->
</script>

						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				</table>
			</td>
			<td valign="top" align="left">
<p class="highlight">Clàr an sgiobair</p>

<div id="log" class="log">
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
document.write(getDateTimeString());
//  End -->
</script>
 Thog sinn an nàmhaid!
 <hr>
</div>
			</td>
		</tr>
<tr><td></td><td colspan="2" align="left">
<br><input type="button" id="start" name="start" value="Tòisich air geama ùr" onclick="javascript:location.reload()" style="font-size: 90%;">
</td><td></td></tr>

	</table>
</div>



<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
updateStatus();
//  End -->
</script>




<br /><hr>
<p>Tha an geama seo stèidhichte air JavaScript le <a href="http://www.jsmadeeasy.com/javascripts/DHTML%20Games/Battleship/index.htm">Jason Hotchkiss</a>.</p>

<p>Craobhan le <a href="http://blender-archi.tuxfamily.org/Greenhouse">The Blender Greenhouse</a> <a href='http://creativecommons.org/licenses/by/2.5/'><br /><img src='CC_SomeRightsReserved.png' alt='' title=''><br />CC-BY</a>
</p>

<p>Cleachdaidh an geama seo JavaScript.</p>

<!-- End game HTML -->
</div>
	<div class="footer newline"></div>
	</div>
</body>
</html>