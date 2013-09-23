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
	<title>Fòram na Gàidhlig - Bullshit Bingo</title>
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<script	type="text/javascript" src="jquery.js"></script>
	<script	type="text/javascript" src="bs.js"></script>
	<link rel="stylesheet" href="../../../templates/fng/main.css" type="text/css">
	<link rel="stylesheet" href="bs.css" type="text/css">
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
		<h1 id="headerpagetitle" class="headerpagetitle newline">Bullshit Bingo</h1>

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
				<h2 class="pagetitle">Bullshit Bingo</h2>
				<p>A bheil thu air do shàrachadh le coinneamhan fada? Seo beagan spòrs dhut ach an tèid iad seachad ann an dòigh nas tlachdmhoire.
				</p><p>Mas urrain dhut laptop a thoirt leat is coltas neochiontach ort fhathast, cluich air loidhne e is briog air na faclan.
				Mur as urrainn, clò-bhuail a' chairt seo is thoir leatsa i. Gach turas a chleachdas duine aon de na faclan faoin a tha oirre, cuir comharra air.
				Nuair a bhios loidhne chomharraichte agad, seas is èigh a-mach: "Bullshit!"
				</p>
				<table class="grid">
					<tr>
						<td id="word0cell" class="word">
							<div id="word0" name="word0"> </div>
						</td>
						<td id="word1cell" class="word">
							<div id="word1" name="word1"> </div>
						</td>
						<td id="word2cell" class="word">
							<div id="word2" name="word2"> </div>
						</td>
						<td id="word3cell" class="word">
							<div id="word3" name="word3"> </div>
						</td>
						<td id="word4cell" class="word">
							<div id="word4" name="word4"> </div>
						</td>
					</tr>
					<tr>
						<td id="word5cell" class="word">
							<div id="word5" name="word5"> </div>
						</td>
						<td id="word6cell" class="word">
							<div id="word6" name="word6"> </div>
						</td>
						<td id="word7cell" class="word">
							<div id="word7" name="word7"> </div>
						</td>
						<td id="word8cell" class="word">
							<div id="word8" name="word8"> </div>
						</td>
						<td id="word9cell" class="word">
							<div id="word9" name="word9"> </div>
						</td>
					</tr>
					<tr>
						<td id="word10cell" class="word">
							<div id="word10" name="word10"> </div>
						</td>
						<td id="word11cell" class="word">
							<div id="word11" name="word11"> </div>
						</td>
						<td id="word12cell" class="word">
							<div id="word12" name="word12"> </div>
						</td>
						<td id="word13cell" class="word">
							<div id="word13" name="word13"> </div>
						</td>
						<td id="word14cell" class="word">
							<div id="word14" name="word14"> </div>
						</td>
					</tr>
					<tr>
						<td id="word15cell" class="word">
							<div id="word15" name="word15"> </div>
						</td>
						<td id="word16cell" class="word">
							<div id="word16" name="word16"> </div>
						</td>
						<td id="word17cell" class="word">
							<div id="word17" name="word17"> </div>
						</td>
						<td id="word18cell" class="word">
							<div id="word18" name="word18"> </div>
						</td>
						<td id="word19cell" class="word">
							<div id="word19" name="word19"> </div>
						</td>
					</tr>
					<tr>
						<td id="word20cell" class="word">
							<div id="word20" name="word20"> </div>
						</td>
						<td id="word21cell" class="word">
							<div id="word21" name="word21"> </div>
						</td>
						<td id="word22cell" class="word">
							<div id="word22" name="word22"> </div>
						</td>
						<td id="word23cell" class="word">
							<div id="word23" name="word23"> </div>
						</td>
						<td id="word24cell" class="word">
							<div id="word24" name="word24"> </div>
						</td>
					</tr>					
				</table>

			<p><input type="button" id="startgame" name="startgame" value ="Dèan cairt ùr" style="font-weight:bold; font-size:100%" />
			&nbsp;&nbsp;&nbsp;<input type="button" id="printgame" name="printgame" value ="Sealladh clò-bhualaidh na cairt seo" style="font-size:100%"/></p>

<p class="gen">Chaidh a' chairt seo a chruthachadh le 25 faclan a-mach à <span id="iomlan"></span>.</p>


<p class="gen">Ma tha thu air mearachd a lorg no ma tha beachd agad air facal, nach cuir thu <a href="http://www.foramnagaidhlig.net/contact.php">fios thugainn</a>.</p>

<p class="gen">Cleachdaidh an geama seo JavaScript.</p>

<h4>Bullshit Bingo ann an cànanan eile:</h4>

<p class="gen"><a href="http://www.bullshitbingo.net/">Beurla</a> - <a href="http://www.besprechungsbingo.de/">Gearmailtis</a></p>
<div id="messagebox"></div>

<!-- End game HTML -->
		</div>
	<div class="footer newline"></div>
	</div>
</body>
</html>